<?php

namespace common\components;
use morozovsk\websocket\Daemon;

abstract class MainDaemon extends Daemon
{
    public function __construct($server, $service, $master) {
        $this->_server = $server;
        $this->_service = $service;
        $this->_master = $master;
        $this->pid = getmypid();
    }
}
