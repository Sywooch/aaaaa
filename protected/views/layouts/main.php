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

    <meta property="og:type" content="article">
    <meta property="og:title" content="Цитата #437395">
    <meta property="og:site_name" content="Цитатник Рунета">
    <meta property="og:description" content="<?php /*echo Html::encode($this->description);*/ ?>">
    <meta property="og:image" content="<?= Yii::$app->params['siteUrl']; ?>img/url-fb.gif">

    <link rel="alternate"
          type="application/rss+xml"
          title="<?= Html::encode($this->title) ?>"
          href="<?= Yii::$app->params['siteUrl']; ?>rss/">

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
                <li role="presentation"><a href="/best">Лучшие</a></li>
                <li role="presentation"><a href="/random">Случайные</a></li>
                <li role="presentation" class="active">
                    <a href="/add"><span class="glyphicon glyphicon-plus"></span> Добавить</a>
                </li>
            </ul>
        </nav>
        <h1 class="text-muted"><?= Yii::$app->params['name'] ?></h1>
    </div>

    <?= $content ?>

    <br><br>

    <footer class="footer">
        <p>&copy; <?= Yii::$app->params['name'] ?>, 2015-<?= date('Y') ?>. Все права защищены.</p>
    </footer>

</div> <!-- /container -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


