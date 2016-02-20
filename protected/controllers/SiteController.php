<?php
namespace app\controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use app\components\ContentGenerator;
use app\components\Grabber;
use app\models\LoginForm;
use app\models\Moderation;
use app\models\Post;
use app\models\Source;
use app\models\Tag;
use app\models\Vote;
use Curl\Curl;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    const PAGE_SIZE = 10;
    public $query = null;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'post', 'search', 'add', 'best', 'random',
                            'captcha', 'login', 'error', 'sitemap',
                            'grab', 'test',
                        ],
                        'allow' => true,
                        //'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'moderate', 'edit', 'format', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
////                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

    /**
     * Страница входа в систему модерирования записей
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Выход из системы модерирования
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Страница модерирования постов
     * @return string|Response
     */
    public function actionModerate()
    {
        $data = Yii::$app->request->post('Moderation');
        $model = Moderation::findOne((int)$data['id']);

        if (
            $model &&
            $model->load(Yii::$app->request->post(), $model->formName()) &&
            ($saveHtmlData = $model->text) &&
            $model->validate()
        ) {
            $post = new Post();
            $post->attributes = $model->attributes;
            $post->text = $saveHtmlData; // сохранять HTML в обход фильтра в модели Moderation
//            $post->hash = md5($saveHtmlData); // оставлять прежний хеш для отслеживания уникальности записей
            $post->visible = true;
            $tags = Yii::$app->request->post($model->formName(), ['tags'=>[]])['tags'];

            if ($post->save()) {
                if (! empty($tags)) {
                    foreach ($tags as $tag) {
                        $modelTag = Tag::add($tag);
                        if ($modelTag) {
                            $post->link('tags', $modelTag);
                        }
                    }
                }
                $model->delete();
                // публикация в твиттере
                ContentGenerator::Twitter($post);
            }
        }

        $posts = new ActiveDataProvider([
            'query' => Moderation::find()->orderBy(['created' => SORT_ASC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('moderate', ['posts' => $posts, 'model' => $model]);
    }

    /*
     * Умное форматирование поста
     */
//    public function actionFormat()
//    {
//        $text = ArrayHelper::getValue(Yii::$app->request->post('Moderation', []), 'text');
//        if (!$text) {
//            throw new Exception('Error!');
//        }
//
//        return ContentGenerator::Format($text);
//    }

    public function actionEdit($id)
    {
        $model = Moderation::findOne((int)$id);
        if (!$model) {
            throw new Exception("Запись не найдена!");
        }

        if (
            Yii::$app->request->isPost &&
            $model->load(Yii::$app->request->post(), $model->formName()) &&
            $model->validate() &&
            !Yii::$app->request->post('nosave', false)
        ) {
            $post = new Post();
            $post->attributes = $model->attributes;
            $post->visible = true;
            $tags = Yii::$app->request->post($model->formName(), ['tags'=>[]])['tags'];
            if ($post->save()) {
                if (! empty($tags)) {
                    foreach ($tags as $tag) {
                        $modelTag = Tag::add($tag);
                        if ($modelTag) {
                            $post->link('tags', $modelTag);
                        }
                    }
                }
                $model->delete();
                // публикация в твиттере
                ContentGenerator::Twitter($post);

                $this->redirect('moderate');
            }
        }

        return $this->render('_edit', ['model' => $model]);
    }

    /**
     * Удаление записи
     * @return array
     */
    public function actionDelete($id)
    {
        Moderation::deleteAll('id = :id', [':id' => $id]);
        return $this->redirect(['moderate']);
    }

    /**
     * Добавление материала на сайт, в раздел на модерацию
     * @return string
     */
    public function actionAdd()
    {
        $model = new Moderation();
        $model->scenario = Moderation::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post())) {
            $model->loadDefaultValues();
            $model->ip = Yii::$app->request->getUserIP();
            $model->user_agent = Yii::$app->request->getUserAgent();
            $model->hash = md5($model->text);
            if ($model->save()) {
                Yii::$app->session->setFlash('success');
            }
            //return $this->refresh();
        }
        return $this->render('add', ['model' => $model]);
    }

    /**
     * Главная страница с записями
     * @param null $query - строка поиска
     * @param bool|false $random - случайные записи
     * @return string
     */
    public function actionIndex($query = null, $random = false)
    {
        $this->query = $query;
        $queryText = "";
        if (preg_match('/^#([\S]+)/i', $this->query, $queryTag)) {
            $postIds = (new Query())
                ->distinct()
                ->select('tag4post.post_id')
                ->from('tag4post')
                ->innerJoin('tag', 'tag.id=tag4post.tag_id')
                ->andFilterWhere(['like', 'tag.name', $queryTag[1]])
                ->all();

            $queryPostsByTag = [];
            if (!empty($postIds)) {
                foreach ($postIds as $postId) {
                    $queryPostsByTag[] = $postId['post_id'];
                }
            }
        } else {
            $queryText = $this->query;
            $queryPostsByTag = [];
        }

        $post_id = (int) Yii::$app->request->get('post_id', 0);
        if ($post_id != 0) {
            try {
                $vote = new Vote();
                $vote->post_id = abs($post_id);
                $vote->rating = $post_id > 0 ? 1 : -1;
                $vote->ip = Yii::$app->request->getUserIP();
                $vote->user_agent = Yii::$app->request->getUserAgent();
                $vote->created = date("Y-m-d H:i:s");
                $vote->save();

            } catch (Exception $e) { }
        }

        $posts = new ActiveDataProvider([
            'query' => Post::find()
                ->with(['tags', 'votes'])
                ->where('visible = 1')
                ->andFilterWhere(['in', 'post.id', $queryPostsByTag])
                ->andFilterWhere(['like', 'post.text', $queryText])
                ->orderBy($random ? (new Expression('rand()')) : ['created' => SORT_DESC]),
            'pagination' => ['pageSize' => self::PAGE_SIZE],
        ]);

        $aKeywords = [];
        $aDescriptions = [];
        foreach ($posts->getModels() as $pagePost) {
            if (!empty($pagePost->tags)) {
                foreach ($pagePost->tags as $tag) {
                    $aKeywords[] = ArrayHelper::getValue($tag, 'name');
                }
            }
            if (!json_decode($pagePost->text)) {
                $aDescriptions[] = Html::encode($pagePost->text); // plain text, not json
            }
        }

        $keywords = implode(', ', array_unique($aKeywords));
        $description = implode("\r\n\r\n\r\n", $aDescriptions);
        $title = Yii::$app->params['name'] . ' - развлекательный сайт для всех!';

        return $this->render('index', [
            'posts' => $posts,
            'title' => $title,
            'keywords' => $keywords,
            'description' => $description,
        ]);
    }

    /**
     * Страница с одним постом
     * @param $post_id - идентификатор поста
     * @return string
     */
    public function actionPost($post_id)
    {
        $post = Post::findOne((int)$post_id);
        if (!$post) {
            $this->redirect('/');
        }

        return $this->render('post', ['post' => $post]);
    }

    /**
     * Карта сайта
     * @param $file - имя карты сайта
     * @return string
     */
    public function actionSitemap($file)
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: text/xml; charset=utf-8');

        $lastPost = Post::find()->where('visible=1')->orderBy(['created' =>SORT_DESC])->one();

        switch ($file) {
            case 'main':
                $items = [
                    [
                        'loc' => Yii::$app->params['siteUrl'],
                        'changefreq' => 'daily',
                        'lastmod' => $lastPost->created,
                        'priority' => '1',
                    ],
                    [
                        'loc' => Yii::$app->params['siteUrl'] . "add",
                        'changefreq' => 'daily',
                        'lastmod' => $lastPost->created,
                        'priority' => '1',
                    ],
                ];
//                return $this->renderPartial('xml_sitemap', ['items' => $items]);
                break;

            case 'pages':
                $items = [];
                $postCount = Post::find()->where('visible=1')->count();
                $pageCount = ceil($postCount / self::PAGE_SIZE);
                for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++ ) {
                    $items[] = [
                        'loc' => Yii::$app->params['siteUrl'] . "index?page=" . $pageNumber,
                        'changefreq' => 'daily',
                        'lastmod' => $lastPost->created,
                        'priority' => '1',
                    ];
                }
//                return $this->renderPartial('xml_sitemap', ['items' => $items]);
                break;

            case 'posts':
                $items = [];
                $posts = Post::find()->where('visible = 1')->all();
                if (!empty($posts)) {
                    foreach ($posts as $post) {
                        $items[] = [
                            'loc' => Yii::$app->params['siteUrl'] . "post/" . $post->id,
                            'lastmod' => $post->created,
                            'changefreq' => 'daily',
                            'priority' => '1',
                        ];
                    }
                }
//                return $this->renderPartial('xml_sitemap', ['items' => $items]);
                break;

            case 'sitemap':
            default:
                $sitemaps = [
                    [
                        'loc' => Yii::$app->params['siteUrl'] . 'posts.xml',
                        'lastmod' => $lastPost->created,
                    ],
                    [
                        'loc' => Yii::$app->params['siteUrl'] . 'pages.xml',
                        'lastmod' => $lastPost->created,
                    ],
                ];
                return $this->renderPartial('xml_sitemapindex', ['sitemaps' => $sitemaps]);
                Yii::$app->end();
        }

        return $this->renderPartial('xml_sitemap', ['items' => $items]);
    }

//    public function actionPage($param)
//    {
//        if (!in_array($param, ['about','garanty','delivery','payments','contacts'])) {
//            throw new Exception();
//        }
//        switch ($param) {
//            case 'contacts':
//                $model = new ContactForm();
//                if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//                    Yii::$app->session->setFlash('contactFormSubmitted');
//                    return $this->refresh();
//                } else {
//                    return $this->render($param, ['model' => $model]);
//                }
//                break;
//            default:
//                // next
//        }
//        return $this->render($param, []);
//    }
    public function actionBest($p)
    {
        return sha1($p);
    }

    public function actionGrab()
    {
//        Yii::$app->response->format = Response::FORMAT_JSON;

        $success = true;

        $source = Source::find()
            ->joinWith('logs')
            ->where(['enable' => true])
            ->orderBy(['updated' => SORT_ASC])
            ->one();

        if ($source) {
            $grabber = new Grabber($source);
            $newPosts = $grabber->execute();
            if (!empty($newPosts)) {
                foreach ($newPosts as $post) {
                    $hashPost = md5($post);
                    // есть ли в базе Post или Moderation
                    $doubling = Post::findOne(['hash' => $hashPost]) || Moderation::findOne(['hash' => $hashPost]);
                    if ($doubling) {
                        continue;
                    }
                    // добавляем новый пост на модерацию
                    $model = new Moderation();
                    $model->text = $post;
                    $model->hash = $hashPost;
                    $model->ip = "127.0.0.1"; //Yii::$app->request->getUserIP();
                    $model->user_agent = "Auto Grabber"; //Yii::$app->request->getUserAgent();
                    $success = $model->save() && $success;
                }
            }
            $source->updateLog();
        }

        return $success;
    }
}
