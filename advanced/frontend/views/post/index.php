<?php

namespace frontend\views\post;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $posts yii\data\ActiveDataProvider */
/* @var $categories yii\data\ActiveDataProvider */
/* @var $post app\models\Post */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="col-sm-8 post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr class = "bigHr">

    <p>
        <?= Html::a('Create post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <hr class = "bigHr">

    <?php Pjax::begin(); ?>
    <?php
        foreach ($models->models as $post) {
            echo $this->render('shortView', [
                'model' => $post
            ]);
        }
        echo LinkPager::widget([
            'pagination'=>$models->pagination,
        ]);
    ?>
    <?php Pjax::end(); ?>


</div>

<div class="col-sm-3 col-sm-offset-1 blog-sidebar sidebar">
    <h1 style = "margin-top: -100%;">Category</h1>
    <ul>
    <?php
    foreach ($categories->models as $category) {
        echo $this->render('//category/shortViewCategory', [
            'model' => $category
        ]);
    }
    ?>
    </ul>
</div>