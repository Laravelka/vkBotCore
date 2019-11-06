<?php 

spl_autoload_register(function ($class) {
	$file = ROOT.'/'.str_replace('\\', '/', $class).'.php';
	
	if (file_exists($file))
	{
		require_once $file;
	}
});

function pre(...$data)
{
	echo '<pre>';
	if (count($data) == 1)
	{
		var_dump($data[0]);
	}
	else
	{
		foreach($data as $value)
		{
			var_dump($value);
		}
	}
	echo '</pre>';
}

function toArray($data)
{
	return json_decode(json_encode($data), true);
}

function toObject($data)
{
	return json_decode(json_encode($data));
}