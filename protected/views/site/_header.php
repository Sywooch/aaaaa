<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col-xs-12">
    </div>
</div>
<div class="row" style="margin-bottom: 30px;">
    <div class="col-xs-6">
        <form action="/" method="get" class="form-horizontal">
            <input name="query" class="form-control" role="search"
                   value="<?= Html::encode($this->context->query) ?>"
                   placeholder="Поиск поста по фразе или тегу...">
        </form>
        <span class="text-nowrap">
            Всего <b><?= \app\models\Post::find()->where('visible=1')->count() ?></b>,
            сегодня <b><?= \app\models\Post::find()->where('visible=1 and created like :d', [':d' => date("Y-m-d")."%"])->count() ?></b>,
            на модерации <b><?= \app\models\Moderation::find()->count() ?></b>
        </span>

        <div class="text-left">
            <b>Популярные теги:</b>
            <?php
            $tagWeights = \app\models\Tag::getTagWeights(10);
            foreach ($tagWeights as $tag => $weight) {
                echo Html::a(
                    "#" . $tag,
                    Url::toRoute(['/', 'query' => "#" . $tag]),
                    ['style' => "display: inline-block; padding:0 4px; font-size:{$weight}pt;"]
                );
            }
            ?>
        </div>
    </div>
    <div class="col-xs-6"><?= Yii::$app->params['googleAds'] ?></div>
</div>
