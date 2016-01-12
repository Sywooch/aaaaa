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
                <li role="presentation"><a href="/best">Лучшие</a></li>
                <li role="presentation"><a href="/random">Случайные</a></li>
                <li role="presentation" class="active">
                    <a href="/add"><span class="glyphicon glyphicon-plus"></span> Добавить</a>
                </li>
            </ul>
        </nav>
        <h1 class="text-muted"><?= Yii::$app->params['name'] ?></h1>
    </div>

    <div class="row">
        <div class="col-xs-12">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <form action="/search" method="get" class="form-horizontal">
                <input name="query" class="form-control" role="search"
                       value="<?= Html::encode($this->context->query) ?>"
                       placeholder="Поиск поста по фразе или тегу...">
            </form>
            <span class=" text-nowrap">
                Всего <b><?= \app\models\Post::find()->count() ?></b>,
                сегодня <b><?= 0 ?></b>,
                на модерации <b><?= \app\models\Moderation::find()->count() ?></b>
            </span>
        </div>
        <div class="col-xs-6">{...}</div>
    </div>

    <?= $content ?>

    <footer class="footer">
        <p>&copy; <?= Yii::$app->params['name'] ?>, 2015-<?= date('Y') ?>. Все права защищены.</p>
    </footer>

</div> <!-- /container -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


