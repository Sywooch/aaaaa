<?php

use yii\helpers\Url;
use kartik\detail\DetailView;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\Dialog;
use app\models\Tag;

/* @var $this yii\web\View */
/* @var $model \app\models\Post */

$btnFormat = <<<html
<button type="button" class="kv-action-btn kv-btn-format" title=""
    data-toggle="tooltip" data-container="body" data-original-title="Обработать"
    ><i class="glyphicon glyphicon-compressed"></i></button>
html;

echo DetailView::widget([
    'model' => $model,
    'condensed' => true,
    'hover' => true,
    'mode' => DetailView::MODE_VIEW,
    'panel' => [
        'heading' => 'Пост # ' . $model->id,
        'type' => DetailView::TYPE_INFO,
    ],
    'deleteOptions' => [
        'url' => Url::toRoute('/delete'),
        'params' => ['id' => $model->id, 'mydelete' => true],
    ],
    'buttons2' => $btnFormat . ' {view} {reset} {save}',
    'attributes' => [
        ['attribute' => 'id', 'type' => DetailView::INPUT_HIDDEN],
        ['attribute' => 'hash', 'type' => DetailView::INPUT_HIDDEN],
        ['attribute' => 'created', 'type' => DetailView::INPUT_TEXT],
        ['attribute' => 'text', 'type' => DetailView::INPUT_TEXTAREA],
        ['attribute' => 'tags', 'type' => DetailView::INPUT_SELECT2, 'widgetOptions' => [
            'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
            'options' => ['multiple' => true, 'placeholder' => 'Теги',],
            'pluginOptions' => ['tags' => true, 'maximumInputLength' => 255,],
        ]],
    ]
]);

$this->registerJs(<<<js
$('.kv-btn-format').click(function() {
    var _form = $(this).closest('form');
    $.post("/format", _form.serialize(), function(response) {
        _form.find('[name=Moderation\\\[text\\\]]').val(response);
        // предпросмотр в диалоговом окне
        try {

            function PolyItem(data) {
                var div = $("<div/>");
                for (var i in data) {
                    div.append($("<h3/>").addClass('text-center').html(data[i].title));
                    div.append($("<img/>").addClass('img-responsive center-block').attr("src", data[i].src));
                    div.append($("<p/>").html(data[i].description));
                }
                return div;
            }

            var _json = $.parseJSON(response);
            if (typeof _json != 'object') return false;
            $('#dlg_preview')
                .empty()
                .addClass('post')
                .append($("<h3/>").addClass('text-center').html(_json.title))
                .append(
                    _json.type == undefined
                    ? PolyItem(_json)
                    : (
                        _json.type == 'image'
                        ? $("<img/>").addClass('img-responsive center-block').attr("src", _json.src)
                        : $("<iframe/>").addClass('embed-responsive-item').attr("src", _json.src)
                            .attr("allowfullscreen", 1).attr("height", "400").attr("width", "100%")
                    )
                )
                .append($("<p/>").html(_json.description))
                .dialog('open');

        } catch (err) {
            alert(response);
        }
    });
});

js
, $this::POS_END);