<?php

use common\models\db\CommentModel;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use common\models\db\PostModel;
/* @var $this yii\web\View */
/* @var $model common\models\Post */
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php if (PostModel::isUserAuthor()) { ?>
        <p>
            <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'You sure delete a post?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>


    <?php 
        $image = null; 
        if (isset($model->image)) { $image = 'image/' . $model->image; } 
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'image',
                'value'=>$image,
                'format' => ['image',['width'=>'100','height'=>'100']],
            ],
            'title',
            'anons:ntext',
            'content:ntext',
            'category.name',
            'author.username',
            'publishStatus',
            'createdAt',
        ],
    ]) ?>

    <h2> Comments </h2>
    <?php Pjax::begin(); ?>
        <?= $this->render('shortViewComments',[
            'model' => $model,
        ]) ?>
    <?php Pjax::end(); ?>

    <?= $this->render('//comment/_form', [
        'model' => $commentForm
    ]) ?>

</div>