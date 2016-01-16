<?php
use yii\helpers\ArrayHelper;
?>
<div class="share42init pull-right" data-description="<?= ArrayHelper::getValue($model,' text') ?>"></div>
<script type="text/javascript" src="<?= Yii::$app->params['siteUrl'] ?>/share42/share42.js"></script>