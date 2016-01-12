<?php
namespace app\controllers;

use app\models\LoginForm;
use app\models\Moderation;
use app\models\Post;
use app\models\Tag;
use app\models\Vote;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
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
                        'actions' => ['index', 'post', 'search', 'add', 'best', 'random', 'captcha', 'login', 'error'],
                        'allow' => true,
                        //'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'moderate', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

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

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionModerate()
    {
        $data = Yii::$app->request->post('Moderation');
        $model = Moderation::findOne((int)$data['id']);

        if ($model && $model->load(Yii::$app->request->post(), $model->formName()) && $model->validate()) {
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
            }
        }

        $posts = new ActiveDataProvider([
            'query' => Moderation::find()->orderBy(['created' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('moderate', ['posts' => $posts, 'model' => $model]);
    }

    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $ok = Moderation::deleteAll('id = :id', [':id' => $id]);
        return ['success' => $ok];
    }

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

    public function actionIndex($query = null)
    {
        $this->query = $query;
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
                ->joinWith(['tags', 'votes'])
                ->where('visible = 1')
                ->andFilterWhere(['or',
                    ['like', "concat('#', tag.name)", $query],
                    ['like', 'post.text', $query]
                ])
                ->orderBy(['created' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);

        $aKeywords = [];
        foreach ($posts->getModels() as $pagePost) {
            if (!empty($pagePost->tags)) {
                foreach ($pagePost->tags as $tag) {
                    $aKeywords[] = ArrayHelper::getValue($tag, 'name');
                }
            }
        }

        $keywords = implode(', ', array_unique($aKeywords));
        $description = '';
        $title = '';

        return $this->render('index', [
            'posts' => $posts,
            'title' => $title,
            'keywords' => $keywords,
            'description' => $description,
        ]);
    }

    public function actionBest()
    {
        return 2;
    }

    public function actionRandom()
    {
        return 3;
    }
//
//    public function actionSearch($query)
//    {
//        return $query;
//    }

    public function actionPost($post_id)
    {
        $post = Post::findOne((int)$post_id);
        if (!$post) {
            $this->redirect('/');
        }

        return $this->render('post', ['post' => $post]);
    }


//    public function actionSitemap($file)
//    {
//        header('Cache-Control: no-cache, must-revalidate');
//        header('Content-type: text/xml; charset=utf-8');
//
//        switch ($file) {
//            case 'sitemap':
//                $sitemaps = [];
//                $categories = Offer::find()->distinct()->select('category_id')->orderBy('category_id')->all();
//                if (!empty($categories)) {
//                    $lastmod = date("Y-m-d");
//                    foreach ($categories as $category) {
//                        $sitemaps[] = [
//                            'loc' => Yii::$app->params['siteUrl'] . $category->category_id . '.xml',
//                            'lastmod' => $lastmod,
//                        ];
//                    }
//                }
//                return $this->renderPartial('xml_sitemapindex', ['sitemaps' => $sitemaps]);
//                exit;
//                break;
//
//            case 'main':
//                $items = [];
//                $categories = Category::find()->all();
//                if (!empty($categories)) {
//                    $lastmod = date("Y-m-d");
//                    foreach ($categories as $category) {
//                        $cities = City::find()->all();
//                        foreach ($cities as $city) {
//                            $items[] = [
//                                'loc' => str_replace(
//                                    "&",
//                                    "&amp;",
//                                    Yii::$app->params['siteUrl'] . $city->url . "/" . $category->id . $category->url
//                                ),
//                                'changefreq' => 'daily',
//                                'lastmod' => $lastmod,
//                                'priority' => '1',
//                            ];
//                        }
//                    }
//                }
////                return $this->renderPartial('xml_sitemap', ['items' => $items]);
//                break;
//
//            default:
//                $items = [];
//                $offers = Offer::find()->where('category_id = :category_id', [':category_id' => $file])->all();
//                if (!empty($offers)) {
//                    $lastmod = date("Y-m-d");
//                    foreach ($offers as $offer) {
//                        $cities = City::find()->all();
//                        foreach ($cities as $city) {
//                            $items[] = [
//                                'loc' => str_replace(
//                                    "&",
//                                    "&amp;",
//                                    Yii::$app->params['siteUrl'] . $city->url . "/" .
//                                    $offer->category_id . $offer->category->url . "/" .
//                                    $offer->id . "-" . $offer->seo_url . ".html"
//                                ),
//                                'lastmod' => $lastmod,
//                                'changefreq' => 'daily',
//                                'priority' => '0.8',
//                            ];
//                        }
//                    }
//                }
////                return $this->renderPartial('xml_sitemap', ['items' => $items]);
//                break;
//        }
//        return $this->renderPartial('xml_sitemap', ['items' => $items]);
//    }
//
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
}
