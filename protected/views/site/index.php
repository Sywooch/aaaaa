<?php
use yii\widgets\ListView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $posts \yii\data\ActiveDataProvider */
/* @var $title string */
/* @var $description string */
/* @var $keywords string */

$this->registerMetaTag(['name' => 'og:type', 'content' => 'article']);
$this->registerMetaTag(['name' => 'og:title', 'content' => $title]);
$this->registerMetaTag(['name' => 'og:site_name', 'content' => Yii::$app->params['siteName']]);
$this->registerMetaTag(['name' => 'og:description', 'content' => $description]);
$this->registerMetaTag(['name' => 'og:keywords', 'content' => $keywords]);
$this->registerMetaTag(['name' => 'og:image', 'content' => Yii::$app->params['siteUrl'] . 'img/url-fb.gif' ]);
$this->title = $title;

echo $this->render('_header');

echo ListView::widget([
    'dataProvider' => $posts,
    'layout' => '{items}{pager}',
    'itemView' => '_post',
    'emptyText' => 'Нет новых постов.',
]);

echo $this->render('_ads');
