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

    {ads}

    <div class="alert alert-success">
        Ваш пост был отправлен на модерацию и после проверки будет опубликован на сайте.<br>
        (через 3 секунды Вы будете перенаправлены на <a href="/">главную страницу</a>)
    </div>

    {ads}

    <?php $this->registerJs('setTimeout(function(){window.location="/";}, 3000);', $this::POS_END);?>

<?php else: ?>

    <h3>Отправить материал для сайта</h3>
    <p class="text-justify">
        Здесь Вы можете отправить любой интересный материал (новость, цитату, картинку, видео или ссылку) для размещения на сайте.
        Все сообщения предварительно модерируются.
    </p>

    <div class="alert alert-danger">
        <span class="glyphicon glyphicon-exclamation-sign"></span>
        Ваш пост <b>не должен</b> нарушать действующее законодательство Республики Казахстан и
        противоречить общепринятым нормам общения в сети Интернет!
    </div>

    <div class="col-xs-12">
        <?php
        $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]);
        echo
            $form->field($model, 'text')
                ->textArea(['rows' => 8, 'placeholder' => 'Текст вашего поста, рекомендуется не более 2 000 символов.'])->label(false) .
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
<br>
<br>