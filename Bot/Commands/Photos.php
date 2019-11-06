<?php

namespace Bot\Commands;

use Engine\Classes\{Logger, Message};
use Engine\Libs\VkApi\Client;
use Engine\Libs\VkApi\Enums\Language;

class Photos
{
	public function getRandomAnime(Message $message, $args)
	{
		$photos = glob(ROOT.'/files/*');
		$random = array_rand($photos, 1);
		
		$message->sendPhoto($photos[$random], 'Пикча #'.($random++));
	}
	
	public function uploadPhoto(Message $message, $args)
	{
		if ($attachments = $message->attach())
		{
			$uploadFiles = [];
			$uploadPath = ROOT.'/files/';
			
			foreach($attachments as $key => $file)
			{
				$file = toObject($file);
				
				if ($file->type == 'photo')
				{
					$photo = $file->photo;
					
					$url = $photo->sizes[count($photo->sizes)-1]->url;
					$name = 'upload_'.$photo->id.'_'.$photo->owner_id.'.jpg';
					
					if (!file_exists($uploadPath.$name))
					{
						$uploadFiles[] = copy($url, $uploadPath.$name);
					}
					else
					{
						$uploadFiles[] = false;
					}
				}
				else
				{
					$uploadFiles[] = false;
				}
			}
			
			if (in_array(false, $uploadFiles))
			{
				if (count($uploadFiles) > 1)
				{
					$message->send(
						'Некоторые изображения не были загружены.'.
						"\n Возможные причины:\n1) Такое изображение уже есть.\n2) Вы прикрепили не изображение."
					);
				}
				else
				{
					$message->send(
						'Изображение не было загружено.'.
						"\n Возможные причины:\n1) Такое изображение уже есть.\n2) Вы прикрепили не изображение."
					);
				}
			}
			else
			{
				if (count($uploadFiles) > 1)
				{
					$message->send('Все изображения успешно загружены.');
				}
				else
				{
					$message->send('Изображение успешно загружено.');
				}
			}
		}
		else
		{
			$message->send('Прикрепи одно или несколько изображений.');
		}
	}
	
	public function getAniMemesRandom(Message $message, $args)
	{
		$api = new Client(
			SERVICE_KEY,
			5.103,
			Language::RUSSIAN
		);
		$groupId = -84054237;
		$random = rand(0, 33000);
		
		$response = (object) $api->wall->get([
			'owner_id' => $groupId,
			'offset' => $random,
			'count' => 1
		]);
		
		if (!empty($response->items))
		{
			$wall = toObject($response->items[0]);
			
			if (!empty($attachments = $wall->attachments))
			{
				$arrPhotos = [];
				foreach($attachments as $value)
				{
					$value = (object) $value;
					
					if ($value->type == 'photo')
					{
						$arrPhotos[] = 'photo'.$value->photo->owner_id.'_'.$value->photo->id;
					}
				}
				
				if (!empty($arrPhotos))
				{
					$message->send('Анимем пикча #'.($random++), [
						'attachment' => implode(', ', $arrPhotos)
					]);
				}
			}
		}
		else
		{
			$message->send('Сервис временно недоступен.');
		}
	}
}
