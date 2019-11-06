<?php

mb_internal_encoding("UTF-8"); 

ini_set('error_reporting', E_ALL); 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 

define('TEST', ($_GET['test'] ?? false));
define('TIME', time());
define('ROOT', __DIR__);
define('SECRET', 'key'); // секретный ключ
define('CONFIRM', 'code'); // ключ подтверждения сервера
define('GROUP_ID', 0); // id группы

define('USER_TOKEN', 'Токен пользователя');
define('ACCESS_TOKEN', 'Токен группы');

include ROOT.'/Bot/bootstrap.php';

use Engine\Classes\{Request, Logger, Event};

$peerId = 2000000001; // тестовый чат
$message = '/photo animem'; // тестовая команда

/*
 * Тестовый режим, включается если перейти по domain.ru/?test=1
 * Вместо отправки сообщений будет выводится их текст
*/
$input = TEST ? '{"type":"message_new","object":{"message":{"date":1572548006,"from_id":310122979,"id":0,"out":0,"peer_id":'.$peerId.',"text":"'.$message.'","conversation_message_id":281211,"fwd_messages":[],"important":false,"random_id":0,"attachments":[],"is_hidden":false},"client_info":{"button_actions":["text","vkpay","open_app","location"],"keyboard":true,"lang_id":0}},"group_id":'.GROUP_ID.',"secret":"'.SECRET.'"}' : null;

$request = Request::instance($input);

if (!$request->verify())
{
	exit('Хуле надо?');
}
else
{
	Logger::file($request->getData(), 'last-data');
	
	$event = Event::instance($request);
	
	$event->set(Bot\Events\MessageNew::class);
	$event->set(Bot\Events\Confirmation::class);
	
	$request->getOK();
}
