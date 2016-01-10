<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $model \app\models\Post */
?>
<div class="post">
    <div class="row">
        <div class="col-xs-3">№
            <a href="/post/<?= $model->id ?>" title="Пост № <?= $model->id ?> - <?= Yii::$app->params['name'] ?>">
                <?= $model->id ?>
            </a>
        </div>
        <div class="col-xs-6 text-muted"><?= $model->created ?></div>
        <div class="col-xs-3">
            <?php Pjax::begin(['enablePushState' => false, 'timeout' => 10000]); ?>
            <div class="pull-right">
                <a title="Полезный"
                   href="?post_id=<?= $model->id .
                    (Yii::$app->request->get("page") ? "&page=".Yii::$app->request->get("page") : "")
                ?>"><span class="vote glyphicon glyphicon-thumbs-up text-success"><?= $model->getGood() ?></span></a>
                <a title="Бесполезный"
                   href="?post_id=-<?= $model->id .
                    (Yii::$app->request->get("page") ? "&page=".Yii::$app->request->get("page") : "")
                ?>"><span class="vote glyphicon glyphicon-thumbs-down text-danger"><?= $model->getBad() ?></span></a>
            </div>
            <?php Pjax::end(); ?>
        </div>
    </div>
    <p>
        <?= str_replace("\n", "<br>", Html::encode($model->text)) ?>
        <?php if (!empty($model->tags)): ?>
            <br>--
            <br>
            <?php foreach($model->tags as $tag): ?>
                <?= Html::a("#" . $tag->name, Url::toRoute('tag/'.$tag->name)) ?>
            <?php endforeach;?>
        <?php endif; ?>
    </p>
</div>