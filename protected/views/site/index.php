<?php
use yii\helpers\Html;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $title String */
/* @var $description String */
/* @var $carousel_items Array */

$this->registerMetaTag(['name' => 'description', 'content' => $description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
$this->title = $title;

?>
<!--<div class="jumbotron">
    <h1>Добро пожаловать<br> на <?/*= Yii::$app->params['name']; */?></h1>
</div>-->

<div class="vitrina">
    <?php foreach (Category::find()->where('parent_id = 0 OR parent_id is NULL')->orderBy('url')->all() as $category) { ?>
        <div class="vitrina_item">
            <a href="<?= "/" . $city->url . "/" . $category->id . $category->url; ?>"
               title="Купить <?= $category->name; ?> в <?= $city->v_name; ?>">
                <div class="vitrina_item_img">
                    <img src="/images/<?= $category->id; ?>.jpg"
                         alt="Купить <?= $category->name; ?> в <?= $city->v_name; ?>">
                </div>
                <div class="vitrina_item_title">
                    <?= Html::encode($category->name); ?>
                </div>
            </a>
        </div>
    <?php } ?>
</div>

<?php
$this->registerJs("
$('.vitrina_item_img').find('img').error(
    function () {
        $(this).attr('src', '/images/nophoto.jpg');
    }
);
", $this::POS_END);