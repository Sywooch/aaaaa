<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\ContentGenerator;
use app\models\Tag;

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
            <div class="pull-right">
                <a href="/delete?id=<?= $model->id ?>" title="Удалить пост"
                ><span class="glyphicon glyphicon-remove text-danger"></span></a>
            </div>
        </div>
    </div>
    <div class="well">
        <?php
        $model->text = ContentGenerator::Format($model->text);
        if ($data = json_decode($model->text)) {
            // анализ и вывод соответствующего объекта: картинка, галлерея, файл, музыка, видео, гиперссылка
            echo ContentGenerator::parse($data);
        } else {
            echo str_replace("\n", "<br>", $model->text); /*Html::encode()*/  // plain text, not json
        }
        ?>
        <?php if (!empty($model->tags)): ?>
            <br>--
            <br>
            <?php foreach($model->tags as $tag): ?>
                <?= Html::a("#" . $tag->name, Url::toRoute(['/', 'query' => "#" . $tag->name])) ?>
            <?php endforeach;?>
        <?php endif; ?>
        <hr>
        <?php
        $form = ActiveForm::begin([
            'action' => '',
            'method' => 'post',
        ]);
        echo Html::tag(
            "div",
            $form->field($model, 'id')->hiddenInput() . $form->field($model, 'text')->hiddenInput() .
            $form->field($model, 'hash')->hiddenInput() . $form->field($model, 'created')->hiddenInput() .
            $form->field($model, 'ip')->hiddenInput() . $form->field($model, 'user_agent')->hiddenInput(),
            ['style' => "display: none;"]
        );

        echo $form->field($model, 'tags')->widget(Select2::className(), [
            'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
            'options' => ['multiple' => true, 'placeholder' => 'Теги', 'id' => 'moderation-tags-' . $model->id,],
            'pluginOptions' => ['tags' => true, 'maximumInputLength' => 255,],
        ]);

        echo Html::tag(
            "div",
            Html::a('Редактировать', ['edit', 'id' => $model->id], ['class' => 'btn btn-warning']) .
            " " .
            Html::submitButton('Опубликовать', ['class' => 'btn btn-success']),
            ['style' => "text-align: center;"]
        );

        $form->end();
        ?>
    </div>
</div>