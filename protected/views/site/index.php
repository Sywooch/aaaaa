<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $title String */
/* @var $description String */

//$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
//$this->title = $title;
?>
    <div class="row">
        <div class="col-xs-6">
            <form action="/search" method="get" class="form-horizontal">
                <input name="query" class="form-control" role="search"
                       placeholder="Поиск по фразе, тегу или номеру поста...">
            </form>
        </div>
    </div>

<?php
echo ListView::widget([
    'dataProvider' => $posts,
    'layout' => '{items}{pager}',
    'itemView' => '_post',
    'emptyText' => 'Нет новых постов.',
]);
