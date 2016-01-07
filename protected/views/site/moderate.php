<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\select2\Select2;
use app\models\Tag;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Question */

$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Вы можете задать любой вопрос на ' . Yii::$app->params['name'] . '.',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'Спросить, задать вопрос, получить ответ, ' . Yii::$app->params['name'] . ', iphone, проблема, не работает, как сделать',
]);
$this->title = 'На ' . Yii::$app->params['name'] . ' Вы можете задать любой вопрос про IPhone';

$this->params['breadcrumbs'] = ['label' => 'Задать вопрос'];

echo ListView::widget([
    'dataProvider' => $posts,
    'layout' => '{items}{pager}',
    'itemView' => '_moderate',
    'viewParams' => ['post' => $post],
    'emptyText' => 'Нет новых постов.',
]);

