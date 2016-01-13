<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col-xs-12">
    </div>
</div>
<div class="row">
    <div class="col-xs-6">
        <form action="/" method="get" class="form-horizontal">
            <input name="query" class="form-control" role="search"
                   value="<?= Html::encode($this->context->query) ?>"
                   placeholder="Поиск поста по фразе или тегу...">
        </form>
        <span class="text-nowrap">
            Всего <b><?= \app\models\Post::find()->where('visible=1')->count() ?></b>,
            сегодня <b><?= 0 ?></b>,
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
                    ['style' => "font-size:{$weight}pt; padding: 4px;"]
                );
            }
            ?>
        </div>
    </div>
    <div class="col-xs-6">{...}</div>
</div>
