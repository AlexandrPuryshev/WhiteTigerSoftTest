<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

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
        foreach ($posts->models as $post) {
            echo $this->render('shortView', [
                'model' => $post
            ]);
        }
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