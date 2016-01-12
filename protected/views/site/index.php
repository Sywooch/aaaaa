<?php
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $posts \yii\data\ActiveDataProvider */

$this->registerMetaTag(['name' => 'og:description', 'content' => $description ]);
$this->registerMetaTag(['name' => 'og:description', 'content' => $description ]);
$this->registerMetaTag(['name' => 'og:keywords', 'content' => $keywords ]);
$this->title = '';

echo ListView::widget([
    'dataProvider' => $posts,
    'layout' => '{items}{pager}',
    'itemView' => '_post',
    'emptyText' => 'Нет новых постов.',
]);
