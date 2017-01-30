<?php

namespace frontend\views\user;

use Yii;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

//$this->title = $modelThisView->username;
$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//Yii::warning($myModel->id) 
?>

<head>

</head>

<body>

    <div class="user-view">

        <h1><?= Html::encode($this->title) ?></h1>


        <?= DetailView::widget([
            //'model' => $modelThisView,
            'model' => $model,
            'attributes' => [
                //'id',
                'username',
                //'auth_key',
                //'password_hash',
                //'password_reset_token',
                'email:email',
                'role',
                'status',
                'createdAt',
                'updatedAt',
            ],
        ]) ?>

    </div>


</body>


