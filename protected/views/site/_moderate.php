<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\models\Tag;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="row">
    <?php
    $form = ActiveForm::begin(['action' => 'publish', 'options' => ['class' => 'form-horizontal']]);
    echo
        $form->field($post, 'text')
            ->textArea(['rows' => 10, 'placeholder' => 'Подробная формулировка вопроса', 'value' => $model->text])->label(false) .
        $form->field($post, 'tags')
            ->widget(Select2::className(), [
                //'id' => 'krtk-select2-'.$index,
                'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
                'options' => ['multiple' => true, 'placeholder' => 'Теги',],
                'pluginOptions' => ['tags' => true, 'maximumInputLength' => 255,], //'minimumInputLength' => 1,
            ])->label(false) .
        Html::beginTag('div', ['class' => 'form-group text-center']) .
        Html::submitButton('Approve', ['class' => 'btn btn-success']) .
        Html::endTag('div');
    $form::end();

    $formDelete = ActiveForm::begin(['action' => 'delete', 'options' => ['class' => 'pull-right']]);
    echo
        //$formDelete->field($model, 'id')->hiddenInput() .
        Html::activeHiddenInput($model, 'id') .
        Html::beginTag('div', ['class' => 'form-group text-center']) .
        Html::submitButton('Delete', ['class' => 'btn btn-danger']) .
        Html::endTag('div');
    $formDelete::end();
    ?>

</div>

