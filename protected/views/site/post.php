<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $post \app\models\Post */
/* @var $description string */
/* @var $keywords string */

$this->registerMetaTag(['name' => 'og:type', 'content' => 'article' ]);
$this->registerMetaTag(['name' => 'og:title', 'content' => 'Пост №' . ArrayHelper::getValue($post, 'id')]);
$this->registerMetaTag(['name' => 'og:site_name', 'content' => 'Сборник свободного народного творчества']);
$this->registerMetaTag(['name' => 'og:description', 'content' => Html::encode(ArrayHelper::getValue($post, 'text')) ]);
$this->registerMetaTag(['name' => 'og:keywords', 'content' => implode(', ', ArrayHelper::map(ArrayHelper::getValue($post, 'tags'), 'id', 'name')) ]);
$this->registerMetaTag(['name' => 'og:image', 'content' => Yii::$app->params['siteUrl'] . 'img/url-fb.gif' ]);

echo $this->render('_header');

echo '{ads}';

echo $this->render('_post', ['model' => $post]);

echo '{ads}';