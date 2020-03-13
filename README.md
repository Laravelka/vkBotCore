# vkBotCore
The core of the bot for callback api vk

## Установка
> Прописать нужные данные в index.php

## События
> Пример событий лежит в [Bot/Events](https://github.com/Laravelka/vkBotCore/blob/master/Bot/Events/)

#### Через класс
```php
$event->set(Bot\Events\MessageNew::class); // message_new
```
#### Через метод on
```php
$event->on('message_new', function($event, $request) {
  // ...
});
```

## Команды

> Пример событий лежит в [Bot/Events](https://github.com/Laravelka/vkBotCore/blob/master/Bot/Events/) а пример комманд в [Bot/Commands](https://github.com/Laravelka/vkBotCore/blob/master/Bot/Commands/)

#### Через класс
> Бот ответит на Начать и старт

```php
$command->add('Начать|Старт', 'Bot\Commands\Class@method')->start(); 
```

> Метод start() задает префикс команды.

```php
->start('/');
```
> Без параметров - снимает.
> Работает так же для группы комманд и глобально:

```php
$cmd = new Command([
	'start' => '!'
]);
// или
$cmd = new Command([
	'start' => ['!', '^', '/']
]);
```

#### Через callback
```php
$cmd->add('Начать|Старт', function($message, $args) {
  $message->send('Привет!');
})->start();
```

#### Групповые команды
```php
$cmd->group('video', function() {
  $this->add(null, function($message, $args) { // ответ на команду /video
    $message->send(
      'Список разделов:'
      ."\n"
      .'/video coub - рандом coub видео'
      ."\n"
      .'/video animem - рандом анимем видео'
    );
  })
  $this->add('coub', 'Bot\Commands\Videos@getCoubRandom'); // ответит на /video coub
  $this->add('animem', 'Bot\Commands\Videos@getAniMemesRandom'); // ответит на /video animem
});
```

**P.S Глубина группы может быть бесконечной**

## Работа с VKAPI

```php
use Engine\Libs\VkApi\Client;
use Engine\Libs\VkApi\Enums\Language;

$api = new Client(
	SERVICE_KEY,
	5.103,
	Language::RUSSIAN
);
$response = $api->messages->send([
	'peer_id' => 1,
	'message' => 'Test',
	'random_id' => rand(11111, 21302414)
]);

// или
$user = $api->users->get(['user_ids' => 1]);
```

#### Отправка сообщений и файлов
```php
$message->send('Привет'); // ответ в текущий чат
$message->send('Привет', [
	'peer_id' => 1
]); // ответ в другой чат

$message->send(ROOT.'/files/img/test.jpg');
$message->send(ROOT.'/files/img/test.jpg', [
	'message' => 'lol kek cheburek'
]);
```
