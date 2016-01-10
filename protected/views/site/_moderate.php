<?php

use yii\helpers\Url;
use kartik\detail\DetailView;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Tag;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

echo DetailView::widget([
    'model' => $model,
    'condensed' => true,
    'hover' => true,
    'mode' => DetailView::MODE_VIEW,
    'panel' => [
        'heading' => 'Пост # ' . $model->id,
        'type' => DetailView::TYPE_INFO,
    ],
    'deleteOptions' => [
        'url' => Url::toRoute('/delete'),
        'params' => ['id' => $model->id, 'mydelete' => true],
    ],
    'attributes' => [
        ['attribute' => 'id', 'type' => DetailView::INPUT_HIDDEN],
        ['attribute' => 'created', 'type' => DetailView::INPUT_TEXT],
        ['attribute' => 'text', 'type' => DetailView::INPUT_TEXTAREA],
        ['attribute' => 'tags', 'type' => DetailView::INPUT_SELECT2, 'widgetOptions' => [
            'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
            'options' => ['multiple' => true, 'placeholder' => 'Теги',],
            'pluginOptions' => ['tags' => true, 'maximumInputLength' => 255,],
        ]],
    ]
]);