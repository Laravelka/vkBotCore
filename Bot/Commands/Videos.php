<?php

namespace Bot\Commands;

use Engine\Classes\{Logger, Keyboard, Message};
use Engine\Libs\VkApi\Client;
use Engine\Libs\VkApi\Enums\Language;

class Videos
{
	public function getCoubRandom(Message $message, $args)
	{
		$api = new Client(
			USER_TOKEN,
			5.103,
			Language::RUSSIAN
		);
		$groupId = -46172262;
		$random = rand(0, 10000);
		
		$response = (object) $api->video->get([
			'owner_id' => $groupId,
			'offset' => $random,
			'count' => 1
		]);
		
		if (!empty($response->items))
		{
			$video = toObject($response->items[0]);
			
			$message->send('Coub видос #'.($random++), [
				'attachment' => 'video'.$video->owner_id.'_'.$video->id
			]);
		}
		else
		{
			$message->send('Сервис временно недоступен.');
		}
	}
	
	public function getAniMemesRandom(Message $message, $args)
	{
		$api = new Client(
			USER_TOKEN,
			5.103,
			Language::RUSSIAN
		);
		$groupId = -84054237;
		$random = rand(0, 1700);
		
		$response = (object) $api->video->get([
			'owner_id' => $groupId,
			'offset' => $random,
			'count' => 1
		]);
		
		if (!empty($response->items))
		{
			$video = toObject($response->items[0]);
			
			$message->send('Анимем видос #'.($random++), [
				'attachment' => 'video'.$video->owner_id.'_'.$video->id,
				'keyboard' => Keyboard::create([
					[
						Keyboard::textButton([
							'label' => '/video coub',
							'color' => Keyboard::POSITIVE,
							'payload' => ['command' => 'coubVideo']
						])
					]
				])
			]);
		}
		else
		{
			$message->send('Сервис временно недоступен.');
		}
	}
}