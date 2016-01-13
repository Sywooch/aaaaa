<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $post \app\models\Post */
/* @var $description string */
/* @var $keywords string */

$title = 'Пост №' . ArrayHelper::getValue($post, 'id');
$this->registerMetaTag(['name' => 'og:type', 'content' => 'article']);
$this->registerMetaTag(['name' => 'og:title', 'content' => $title]);
$this->registerMetaTag(['name' => 'og:site_name', 'content' => Yii::$app->params['siteName']]);
$this->registerMetaTag(['name' => 'og:description', 'content' => Html::encode(ArrayHelper::getValue($post, 'text')) ]);
$this->registerMetaTag(['name' => 'og:keywords', 'content' => implode(', ', ArrayHelper::map(ArrayHelper::getValue($post, 'tags'), 'id', 'name')) ]);
$this->registerMetaTag(['name' => 'og:image', 'content' => Yii::$app->params['siteUrl'] . 'img/url-fb.gif' ]);
$this->title = $title;

echo $this->render('_header');

echo '{ads}';

echo $this->render('_post', ['model' => $post]);

echo '{ads}';