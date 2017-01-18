<?php

namespace common\components;

use Yii;
use morozovsk\yii2websocket\console\controllers\WebsocketController;


class MainWebsocketController extends WebsocketController
{
    public $component = 'websocket';

    public function actionStart($server)
    {
        $WebsocketServer = new MainServer(Yii::$app->get($this->component)->servers[$server]);
        call_user_func(array($WebsocketServer, 'start'));
    }

    public function actionStop($server)
    {
        $WebsocketServer = new MainServer(Yii::$app->get($this->component)->servers[$server]);
        call_user_func(array($WebsocketServer, 'stop'));
    }

    public function actionRestart($server)
    {
        $WebsocketServer = new MainServer(Yii::$app->get($this->component)->servers[$server]);
        call_user_func(array($WebsocketServer, 'restart'));
    }
}
