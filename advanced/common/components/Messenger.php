<?php

namespace common\components;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Yii;

/**
 * Class Messenger
 * @package app\components
 */
class Messenger implements MessageComponentInterface {
	
	const NEW_MESSAGE = 'new_message';
	const DELETE_MESSAGE = 'delete_message';

	protected $clients;

	public function __construct() {
		$this->clients = new \SplObjectStorage;

	}

	/**
	 * @param ConnectionInterface $conn
	 */
	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		echo "Open connection ({$conn->resourceId})\n";
	}

	/**
	 * @param ConnectionInterface $from
	 * @param string              $msg
	 */
	public function onMessage(ConnectionInterface $from, $msg) {
		foreach ($this->clients as $client) {
			if ($from !== $client) {

				if (isset($msg)) {

					$msg = json_decode($msg);
					switch ($msg->type) {
						case self::NEW_MESSAGE:
							$client->send(json_encode([
								'type'       => self::NEW_MESSAGE,
								'messageId' => $msg->messageId,
								'content'    => $msg->content,
								'userName'  => $msg->userName,
								'createdAt' => $msg->createdAt
							]));
							break;

						case self::DELETE_MESSAGE:
							$client->send(json_encode([
								'type'       => self::DELETE_MESSAGE,
								'messageId' => $msg->messageId,
							]));
							break;
					}
				}
			}
		}
	}

	/**
	 * @param ConnectionInterface $conn
	 */
	public function onClose(ConnectionInterface $conn) {
		$this->clients->detach($conn);
		echo "User {$conn->resourceId} disconected\n";
	}

	/**
	 * @param ConnectionInterface $conn
	 * @param \Exception          $e
	 */
	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "Error: {$e->getMessage()}\n";
		$conn->close();
	}
}