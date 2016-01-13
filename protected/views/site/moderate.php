<?php
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $posts \yii\data\ActiveDataProvider */

$this->title = 'Модерирование записей на ' . Yii::$app->params['name'];

?>
    <div class="alert alert-info">
        <b>Синтаксис JSON для встраиваемых объектов:</b><br>
        Изображение - {"type":"image", "src":"полный URL адрес", "alt":"Описание"}<br>
        Видеоролик - {"type":"video", "src":"полный URL адрес", "alt":"Описание"}<br>
        Аудиофайл - {"type":"audio", "src":"полный URL адрес", "alt":"Описание"}<br>
        Гиперссылка - {"type":"link", "src":"полный URL адрес", "alt":"Описание"}<br>
    </div>
<?php
echo ListView::widget([
    'dataProvider' => $posts,
    'layout' => '{items}{pager}',
    'itemView' => '_moderate',
//    'viewParams' => ['post' => $post],
    'emptyText' => 'Нет новых постов.',
]);

