<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $post \app\models\Post */
/* @var $description string */
/* @var $keywords string */

$title = 'Пост №' . ArrayHelper::getValue($post, 'id') . " - " . Yii::$app->params['siteName'];
$this->registerMetaTag(['name' => 'og:type', 'content' => 'article']);
$this->registerMetaTag(['name' => 'og:title', 'content' => $title]);
$this->registerMetaTag(['name' => 'og:site_name', 'content' => Yii::$app->params['siteName']]);
$this->registerMetaTag(['name' => 'og:description', 'content'=> ($data=json_decode(ArrayHelper::getValue($post,'text')))
    ? Html::encode(ArrayHelper::getValue($data, 'title') . ArrayHelper::getValue($data, 'description'))
    : Html::encode(strip_tags(ArrayHelper::getValue($post, 'text')))
]);
$this->registerMetaTag(['name' => 'og:keywords', 'content' => implode(', ', ArrayHelper::map(ArrayHelper::getValue($post, 'tags'), 'id', 'name')) ]);
$this->registerMetaTag(['name' => 'og:image', 'content' => Yii::$app->params['siteUrl'] . 'img/url-fb.gif' ]);
$this->title = $title;

echo $this->render('_header');

echo Yii::$app->params['googleAds'];

echo $this->render('_post', ['model' => $post]);
echo $this->render('_share42', ['model' => $post]);

echo Yii::$app->params['googleAds'];

echo $this->render('_ads');