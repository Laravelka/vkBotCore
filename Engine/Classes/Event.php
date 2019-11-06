<?php

namespace Engine\Classes;

class Event
{
	public $request;
	public static $toArray = false;
	protected static $instance = null;
	public static $callbackName = 'handle';
	
	private function __construct() {}
	
	public static function instance($request)
	{
		if (self::$instance === null) self::$instance = new self;
		
		self::$instance->request = $request;
		
		foreach($request->getData(true) as $key => $value)
		{
			self::$instance->{$key} = $value;
		}
		return self::$instance;
	}
	
	public static function on($type, $callback)
	{
		$event = self::$instance;
		
		if (!empty($event->type) && $event->type == $type)
		{
			if (!empty($event->object))
			{
				$object = (
					$event->type == 'message_new' ? (
						self::convertType($event->object->message)
					) : (
						self::convertType($event->object)
					)
				);
			}
			elseif (!empty($event->group_id))
			{
				$object = $event;
			}
			else
			{
				$object = false;
			}
			return call_user_func_array($callback, [$object, $event->request]);
		}
	}
	
	public static function set($event)
	{
		if (!empty($event))
		{
			$type = self::getEventName($event);
			
			return call_user_func_array([self::$instance, 'on'], [$type, [new $event, self::$callbackName]]);
		}
		return false;
	}
	
	protected static function getEventName($string)
	{
		preg_match_all('/[A-Z][a-z]+/m', str_ireplace('Bot\\Events\\', '', $string), $match);
		
		return strtolower(implode('_', $match[0]));
	}
	
	protected static function convertType($data)
	{
		return self::$toArray ? (array) $data : (object) $data;
	}
	
	private function __clone() {}
	private function __wakeup() {}
}
