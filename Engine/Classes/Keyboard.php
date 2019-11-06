<?php

namespace Engine\Classes;

use \InvalidArgumentException;

class Keyboard
{
	const DEFAULT = 'default';
	const POSITIVE = 'positive';
	const PRIMARY = 'primary';
	const NEGATIVE = 'negative';
	
	public static function create($buttons, $oneTime = false)
	{
		if (count($buttons) > 10)
		{
			throw new Exception('Максимальное кол-во кнопок в высоту: 10');
		}
		else
		{
			return json_encode(['one_time' => $oneTime, 'buttons' => $buttons], JSON_UNESCAPED_UNICODE);
		}
	}
	
	public static function textButton($button)
	{
		$type = 'text';
		$color = static::DEFAULT;
		
		$buttons = [];
		foreach($button as $key => $value)
		{
			if ($key == 'type')
			{
				$type = $value;
			}
			elseif ($key == 'color')
			{
				$color = $value;
			}
			elseif ($key == 'payload')
			{
				$buttons[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
			}
			else
			{
				$buttons[$key] = $value;
			}
		}
		return ['color' => $color, 'action' => array_merge(['type' => $type], $buttons)];
	}
}
