<?php

namespace console\controllers;

use common\components\Messenger;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Yii;
use yii\console\Controller;

/**
 * Class MessengerController
 * @package app\commands
 */
class MessengerController extends Controller {

	public function actionIndex() {
		$server = IoServer::factory(
			new HttpServer(
				new WsServer(
					new Messenger()
				)
			),
			3030
		);

		$server->run();
	}
}
