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
	 * @param string              $messageInfo
	 */
	public function onMessage(ConnectionInterface $from, $messageInfo) {
		foreach ($this->clients as $client) {
			if ($from !== $client) {

				if (isset($messageInfo)) {

					$messageInfo = json_decode($messageInfo);
					switch ($messageInfo->type) {
						case self::NEW_MESSAGE:
							$client->send(json_encode([
								'type'      => self::NEW_MESSAGE,
								'idMessage' => $messageInfo->idMessage,
								'content'   => $messageInfo->content,
								'userName'  => $messageInfo->userName,
								'createdAt' => $messageInfo->createdAt
							]));
							break;

						case self::DELETE_MESSAGE:
							$client->send(json_encode([
								'type'      => self::DELETE_MESSAGE,
								'idMessage' => $messageInfo->idMessage,
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