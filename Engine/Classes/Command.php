<?php

namespace Engine\Classes;

use \Closure;

class Command
{
	public $settings = [
		'start' => '/',
		'isEnd' => true,
		'groupSeparator' => ' ' // разделитель групповых регулярок
	];
	
	public $patterns = [
		'{*}' => '(.*)',
		'{int}' => '(?<int>[0-9]+)',
		'{multiInt}' => '([0-9,]+)',
		'{title}' => '(?<title>[a-z_-]+)',
		'{key}' => '(?<key>[a-z0-9_]+)',
		'{name}' => '(?<name>[a-zа-я0-9_-]+)',
		'{multiKey}' => '([a-z0-9_,]+)',
		'{isoCode2}' => '([a-z]{2})',
		'{isoCode3}' => '([a-z]{3})',
		'{multiIsoCode2}' => '([a-z,]{2,})',
		'{multiIsoCode3}' => '([a-z,]{3,})'
	];
	private $currentGroup = [];
	private $group = [];
	private $isGroup = false;
	private $commands = [];
	private $middlewares = [];
	private $currentCommand;
	
	public function __construct($settings = [])
	{
		$this->settings = array_merge($this->settings, $settings);
	}
	
	public function add($regex, $callback = false)
	{
		if (!empty($this->group))
		{
			$prefix = $this->group['regex'];
			$start = (!empty($this->group['start']) ? $this->group['start'] : null);
			$regex = $prefix.(empty($regex) ? '' : $this->settings['groupSeparator'].$regex);
		}
		else
		{
			$start = (!empty($this->settings['start']) ? $this->settings['start'] : null);
		}
		$isEnd = (!empty($this->group['isEnd']) || !empty($this->settings['isEnd']) || empty($regex));
		
		if (!$callback instanceof Closure)
		{
			$this->commands[] = [
				'start' => $start,
				'isEnd' => $isEnd,
				'regex' => $regex,
				'callback' => $callback
			];
		}
		else
		{
			$this->commands[] = [
				'start' => $start,
				'isEnd' => $isEnd,
				'regex' => $regex,
				'callback' => $callback
			];
		}
		return $this;
	}
	
	public function group($regex, $callback)
	{
		$current = $this->groupConstruct($regex);
		if ($callback instanceof Closure)
		{
			$callback = Closure::bind($callback, $this, get_class());
			call_user_func($callback, $this);
		}
		$this->groupDestruct($current);
		
		return $this;
	}
	
	public function start($start = null)
	{
		if (empty($this->group))
		{
			$index = count($this->commands) -1;
			$this->commands[$index]['start'] = $start;
		}
		else
		{
			$this->group['start'] = $start;
		}
		return $this;
	}
	
	public function filter($callback)
	{
		if (empty($this->group))
		{
			$index = count($this->commands) -1;
			$this->commands[$index]['filter'] = $callback;
		}
		else
		{
			$this->group['filter'] = $callback;
		}
		return $this;
	}
	
	public function end()
	{
		if (empty($this->group))
		{
			$index = count($this->commands) -1;
			$this->commands[$index]['isEnd'] = true;
		}
		else
		{
			$this->group['isEnd'] = true;
		}
		return $this;
	}
	
	
	public function dispatcher($data, $request)
	{
		foreach($this->commands as $command)
		{
			$regex = $this->prepare($command);
			
			if (preg_match($regex, $data->text, $match))
			{
				$this->currentCommand = [
					'regex' => $regex,
					'data' => $command
				];
				
				if ($command['callback']  instanceof Closure)
				{
					$callback = Closure::bind($command['callback'], $this, get_class());
					
					call_user_func_array($callback, [Message::instance($data), $match]);
				}
				else
				{
					call_user_func_array(
						$this->callbackToArray($command['callback']), 
						[Message::instance($data), $match]
					);
				}
			}
		}
	}
	
	public function getAll()
	{
		return $this->commands;
	}
	
	public function getCurrent()
	{
		return $this->currentCommand;
	}
	
	private function prepare($command)
	{
		if (preg_match('/\{.*\}/ui', $command['regex']))
		{
			$text = str_replace(array_keys($this->patterns), $this->patterns, $command['regex']);
		}
		else
		{
			$text = '('.$command['regex'].')';
		}
		$prefix = !empty($command['start']) ? '(?:'.$this->prefix($command['start']).')' : '';
		
		return '/^'.$prefix.$text.($command['isEnd'] ? '$' : '').'/ui';
	}
	
	private function prefix($starts)
	{
		if (is_array($starts))
		{
			$prefixs = '';
			foreach($starts as $key => $start)
			{
				$prefixs .= (!empty($start) ? "\\".$start : '').($key+1 != count($starts) ? '|' : '');
			}
			return $prefixs;
		}
		else
		{
			return "\\".$starts;
		}
	}
	
	private function groupConstruct($regex)
	{
		$currentGroup = $this->group;
		$this->group['start'] = !empty($this->settings['start']) ? $this->settings['start'] : null;
		
		if (empty($currentGroup))
		{
			$this->group['regex'] = $regex;
		}
		else
		{
			$this->group['regex'] = $currentGroup['regex'].$this->settings['groupSeparator'].$regex;
		}
		return $currentGroup;
	}
	
	private function groupDestruct($group)
	{
		$this->group = $group;
	}
	
	private function callbackToArray($callback)
	{
		$match = explode('@', $callback);
		
		if (!empty($match[1]))
		{
			return [
				new $match[0],
				$match[1]
			];
		}
		else
		{
			throw new \Exception('Invalid Command name');
		}
	}
}
