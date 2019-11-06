<?php

namespace Engine\Classes;

class Request
{
	public $data = false;
	public $toArray = false;
	protected static $instance = null;
	
	private function __construct() {}
	
	public static function instance($yourInput = null)
	{
		if (self::$instance === null) self::$instance = new self;
		
		$input = $yourInput ?? file_get_contents('php://input');
		
		if (self::isJson($input))
		{
			self::$instance->data = json_decode($input, self::$instance->toArray);
		}
		return self::$instance;
	}
	
	public static function getData($toArray = null)
	{
		$data = self::$instance->data;
		$toArray = $toArray ?? self::$instance->toArray;
		
		if (!empty($data))
			return $toArray ? (array) $data : (object) $data;
		else
			return false;
	}
	
	public static function verify()
	{
		$request = self::$instance;
		
		if (!empty($request->data->secret) && !empty($request->data->group_id))
		{
			return $request->data->secret == SECRET && $request->data->group_id == GROUP_ID;
		}
		return false;
	}
	
	public static function isJson($json)
	{
		return json_decode($json) !== null;
	}
	
	public static function getOK()
	{
		exit('OK');
	}
	
	private function __clone() {}
	private function __wakeup() {}
}
