<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);

require(__DIR__ . '/protected/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config.php');

$application = new yii\web\Application($config);
$application->run();
