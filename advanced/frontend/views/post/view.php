<?php

use common\models\db\CommentModel;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model common\models\Post */
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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