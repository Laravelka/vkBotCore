<?php

namespace Engine\Classes;

use Engine\Libs\VkApi\Client;
use Engine\Libs\VkApi\Enums\Language;

class Message
{
	public $api;
	public $data = [];
	protected static $instance = null;
	
	private function __construct() {}
	
	public static function instance($data = [])
	{
		self::$instance = self::$instance ?? new self;
		
		self::$instance->api = new Client(
			ACCESS_TOKEN,
			5.103,
			Language::RUSSIAN
		);
		self::$instance->data = $data;
		
		return self::$instance;
	}
	
	public function send($text, $params = [])
	{
		if (TEST)
		{
			echo $text.PHP_EOL;
		}
		else
		{
			return $this->api->messages->send(array_merge([
				'peer_id' => $this->data->peer_id,
				'message' => $text,
				'random_id' => rand(11111, 21302414),
			], $params));
		}
	}
	
	public function sendPhoto($file, $message = false)
	{
		$api = $this->api;
		
		$response = $api->photos->getMessagesUploadServer(['peer_id' => $this->data->peer_id]);
		$response = $api->upload($response['upload_url'], 'photo', $file);
		$response = $api->photos->saveMessagesPhoto($response);
		
		return $this->send($message, [
			'attachment' => 'photo'.$response[0]['owner_id'].'_'.$response[0]['id']
		]);
	}
	
	public function get($key, $default = null)
	{
		$match = explode('.', $key);
		$array = toArray($this->data);
		
		foreach($match as $value)
		{
			if (!empty($array[$value]))
			{
				$array = $array[$value];
			}
			else
			{
				return $default;
			}
		}
		return $array;
	}
	
	public function attach($key = null)
	{
		return $this->get($key ? 'attachments.'.$key : 'attachments');
	}
	
	private function __clone() {}
	private function __wakeup() {}
}
