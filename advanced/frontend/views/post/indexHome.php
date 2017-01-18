<?php

/* @var $this yii\web\View */

namespace frontend\views\post;

use Yii;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col-sm-8 post-index post-home">

    <h1 align="center"><?= Html::encode($this->title) ?></h1>

    <hr class = "bigHrHome">

    <?php Pjax::begin(); ?>
        <?php
            foreach ($models->models as $post) {
                echo $this->render('shortViewHome', [
                    'model' => $post
                ]);
            }
            echo LinkPager::widget([
                'pagination'=>$models->pagination,
            ]);
        ?>
    <?php Pjax::end(); ?>
</div>
