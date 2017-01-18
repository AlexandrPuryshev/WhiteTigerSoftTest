<?php

namespace common\components\server;

use common\models\base\UserBase;
use common\components\MainDaemon;

//пример реализации чата
class Chat3WebsocketDaemonHandler extends MainDaemon
{
    public $userIds = [];

    //вызывается при соединении с новым клиентом
    protected function onOpen($connectionId, $info) 
    {

        $info['GET'];//or use $info['Cookie'] for use PHPSESSID or $info['X-Real-IP'] if you use proxy-server like nginx
        parse_str(substr($info['GET'], 1), $_GET);//parse get-query

        $userModel = UserBase::find()->where(['id' => $_GET['userId']])->one();
        $viewModel = UserBase::find()->where(['id' => $_GET['viewId']])->one();

        //var_export($userModel);

        //$message = 'пользователь ' . $userModel->username; /*$connectionId . ' : ' var_export($info, true)*/ // . ' ' . stream_socket_get_name($this->clients[$connectionId], true);
        $message = null;
        //foreach ($this->clients as $clientId => $client) {
        $this->sendToClient($_GET['userId'], $message);
        //}

        //var_export("\nUser id: " . $_GET['userId']);
        //var_export("View id: " . $_GET['viewId']);

        $this->userIds[$connectionId] = $_GET['userId'];
    }

    protected function onClose($connectionId) {//вызывается при закрытии соединения с существующим клиентом
        unset($this->userIds[$connectionId]);
    }

    protected function onMessage($connectionId, $data, $type) 
    {//вызывается при получении сообщения от клиента
        if (!strlen($data)) {
            return;
        }

        //var_export($data);
        //шлем всем сообщение, о том, что пишет один из клиентов
        //echo $data . "\n";
        $message = 'пользователь #' . $connectionId . ' : ' . strip_tags($data);

        //foreach ($this->clients as $clientId => $client) {
        $this->sendToClient($clientId, $message);
       // }
    }

    protected function onServiceMessage($connectionId, $data) {
        $data = json_decode($data);

        foreach ($this->userIds as $clientId => $userId) {
            if ($data->userId == $userId) {
                $this->sendToClient($clientId, $data->message);
            }
        }

        /*if (isset($this->clients[$data->clientId])) {
            $this->sendToClient($data->clientId, $data->message);
        }*/
    }
}