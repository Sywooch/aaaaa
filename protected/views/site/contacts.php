<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Контактная информация интернет-магазина ' . Yii::$app->params['name'] . ': телефоны, email, skype.',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'Интернет, магазин, интернет-магазин, ' . Yii::$app->params['name'] . ', контакты, телефон, email, skype',
]);
$this->title = 'Контактная информация интернет-магазина ' . Yii::$app->params['name'] . ': телефоны, email, skype.';

$this->params['breadcrumbs'] = ['label' => 'Контакты'];
?>

<h1>Напишите нам письмо!</h1>
<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
    <div class="alert alert-success">
        Спасибо за Ваше сообщение! Мы ответим Вам как можно быстрее :)
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'subject') ?>
            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php endif; ?>

