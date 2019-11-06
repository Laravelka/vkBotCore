<?php

namespace Bot\Commands;

use Engine\Classes\{Message, Logger};
use Engine\Libs\VkApi\Client;
use Engine\Libs\VkApi\Enums\Language;

class Start
{
	public function greetingUsers(Message $message, $args)
	{
		$api = new Client(
			USER_TOKEN,
			5.103,
			Language::RUSSIAN
		);
		$response = $api->users->get(['user_ids' => $message->get('from_id')]);
		
		if (!empty($response[0]))
		{
			$nick = $response[0]['first_name'].' '.$response[0]['last_name'];
		
			$message->send('Привет, '.$nick.'!');
		}
		else
		{
			Logger::file($response, 'greetingUsers');
		}
	}
}