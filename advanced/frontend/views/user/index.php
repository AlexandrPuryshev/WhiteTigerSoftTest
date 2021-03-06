<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--<p>-->
        <!--<?/*= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) */?>-->
    <!--</p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            //'role',
            //'status',
            // 'created_at',
            // 'updated_at',
            [   
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => 'View',
                            'class' => 'btn btn-success btn-sm',
                            'style' => 'width: 75%;'
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
