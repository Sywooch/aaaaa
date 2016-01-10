<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $title String */
/* @var $description String */

//$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
//$this->title = $title;


echo ListView::widget([
    'dataProvider' => $posts,
    'layout' => '{items}{pager}',
    'itemView' => '_post',
    'emptyText' => 'Нет новых постов.',
]);

$this->registerJs(<<<js
    //$('.vote').click(function(){
    //    console.log($(this).data("id") );
    //    //var _id = $(this).content.dataset("id");
    //    //alert(_id);
    //    $.pjax.reload("#left-form");
    //
    //});
js
    , $this::POS_END);