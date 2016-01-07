<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use kartik\select2\Select2;
use app\models\Tag;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Post */

$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Вы можете отправить свой пост на ' . Yii::$app->params['name'] . '.',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'Пост, отправить, сообщение, ' . Yii::$app->params['name'] . ', Ааааа, Aaaaa',
]);
$this->title = 'Здесь вы можете отправить свой пост на ' . Yii::$app->params['name'] . '. Мы не гарантируем его публикацию!';

$this->params['breadcrumbs'] = ['label' => 'Отправить пост'];
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        Ааааа, спасибо!<br>
        Ваш пост был отправлен на модерацию и после проверки будет опубликован на сайте.
    </div>

<?php else: ?>

    <div>
        <?php
        $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]);
        echo
            $form->field($model, 'text')
                ->textArea(['rows' => 8, 'placeholder' => 'Текст вашего поста, не более 2000 символов.'])->label(false) .
            $form->field($model, 'verifyCode')
                ->widget(Captcha::className(), [
                    'template' =>
                        '<div class="row">
    <div class="col-xs-2">{image}</div>
    <div class="col-xs-3">{input}</div>
    <div class="col-xs-2"></div>
    <div class="col-xs-5">' .
    Html::submitButton('Отправить на рассмотрение', ['class' => 'btn btn-success btn-block', 'name' => 'add-button']) .
    '</div>
</div>',
                ])->label(false);

        $form::end();
        ?>
    </div>

<?php endif; ?>