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
use yii\helpers\Html;
use yii\db\Query;

class SiteController extends Controller
{
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
                        'actions' => ['index', 'add', 'captcha', 'login', 'error'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'moderate'],
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
        $post = new Post();
        if ($post->load(Yii::$app->request->post())) {
            if ($post->save()) {
                foreach ($post->tags as $tag) {
                    $modelTag = Tag::add($tag);
                    if ($modelTag) {
                        $post->link('tags', $modelTag);
                    }
                }
                Moderation::deleteAll('hash = :hash', [':hash' => $post->hash]);
                Yii::$app->session->setFlash('success');
            }
        }

        $posts = new ActiveDataProvider([
            'query' => Moderation::find()->orderBy(['created' => SORT_DESC]), //->with('tags')
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('moderate', ['posts' => $posts, 'post' => $post]);
    }

    public function actionIndex()
    {
        return $this->render('test', [
//            'title' => $title,
//            'description' => $description,
//            'keywords' => $keywords,
//            'city' => $city,
//            'favorite' => $favorite,
        ]);
    }

    public function actionAdd()
    {
        $model = new Moderation();
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

    public function actionPost($post_id)
    {
        $post = Post::findOne((int)$post_id);
        if (!$post) {
            throw new Exception('!');
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
