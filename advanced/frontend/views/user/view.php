<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<head>
    <script>
        $(function(){
            document.cookie = '';
            function wsStart() {
                ws = new WebSocket("ws://127.0.0.1:8004/userId=" + Math.round(Math.random()*10000));

                ws.onopen = function() { $("#chat").append("<p>система: соединение открыто</p>"); };
                ws.onclose = function() { $("#chat").append("<p>система: соединение закрыто, пытаюсь переподключиться</p>"); setTimeout(wsStart, 1000);};
                ws.onmessage = function(evt) { $("#chat").append("<p>"+evt.data+"</p>"); $('#chat').scrollTop($('#chat')[0].scrollHeight);};
            }

            wsStart();

            $('#chat').height($(window).height() - 80);

            $('#input').focus();
        });
    </script>
</head>

<body>

    <div class="user-view">

        <h1><?= Html::encode($this->title) ?></h1>


        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
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

        <div id="chat" style="overflow: auto;"><p>Система: пожалуйста подождите, идёт соединение с сервером.</p></div>
        <div class="navbar-fixed-bottom">
            <form style= "display: flex;" onsubmit="ws.send($('input').val()); $('input').val(''); return false; ">
                <input id="input" type="text" class="form-control" placeholder="Text input" style="width: 100%;" maxlength="140" autocomplete="off">
            <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>

    </div>


</body>


