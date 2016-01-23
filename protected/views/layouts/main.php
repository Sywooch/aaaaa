<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= Html::encode($this->title) ?></title>
    <!--link rel="alternate"
          type="application/rss+xml"
          title="<?= Html::encode($this->title) ?>"
          href="<?= Yii::$app->params['siteUrl']; ?>rss/"-->

    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container">

    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="/">Новые</a></li>
<!--                <li role="presentation"><a href="/best">Лучшие</a></li>-->
                <li role="presentation"><a href="/random">Случайные</a></li>
                <li role="presentation" class="active">
                    <a href="/add"><span class="glyphicon glyphicon-plus"></span> Добавить</a>
                </li>
            </ul>
        </nav>
        <h1 class="text-muted"><?= Yii::$app->params['name'] ?></h1>
    </div>

    <?= $content ?>

    <footer class="footer">
        <div class="row">
            <div class="col-xs-6">
                <p>&copy; <?= Yii::$app->params['name'] ?>, 2015-<?= date('Y') ?>. Все права защищены.</p>
                <p>Мнение администратора может не совпадать с мнением автора поста.</p>
            </div>
            <div class="col-xs-6">
                <a href="https://twitter.com/aaaaakz2" class="pull-right"
                   title="<?= Yii::$app->params['name'] ?> в Twitter"
                >Читайте нас в Twitter <i class="glyphicon glyphicon-retweet"></i> </a>
            </div>
        </div>
    </footer>

</div> <!-- /container -->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


