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
        <p>&copy; <?= Yii::$app->params['name'] ?>, 2015-<?= date('Y') ?>. Все права защищены.</p>
        <p>Мнение администратора может не совпадать с мнением автора поста.</p>
    </footer>

</div> <!-- /container -->

<!-- left ads block -->
<div class="visible-lg" style="position: fixed; left: 0; top:0; bottom: 0; width: 250px;">
    <?= Yii::$app->params['googleAds'] ?>
</div>
<div class="visible-md" style="position: fixed; left: 0; top:0; bottom: 0; width: 140px;">
    <?= Yii::$app->params['googleAds'] ?>
</div>

<!-- right ads block -->
<div class="visible-lg" style="position: fixed; right: 0; top:0; bottom: 0; width: 250px;">
    <?= Yii::$app->params['googleAds'] ?>
</div>
<div class="visible-md" style="position: fixed; right: 0; top:0; bottom: 0; width: 140px;">
    <?= Yii::$app->params['googleAds'] ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


