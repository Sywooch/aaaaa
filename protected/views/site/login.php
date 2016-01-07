<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => '{label}<div class="col-xs-4">{input}</div><div class="col-xs-6">{error}</div>',
            'labelOptions' => ['class' => 'col-xs-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?php /*= $form->field($model, 'rememberMe')->checkbox() */ ?>

    <div class="form-group">
        <div class="col-xs-offset-1 col-xs-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary col-xs-4', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
