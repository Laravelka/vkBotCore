# vkBotCore
The core of the bot for callback api vk

[TOC]

## Установка
> Прописать нужные данные в index.php

## События
> Пример событий лежит в **Bot/Events**

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

# Команды

> Подключение команд можно глянуть в **Bot\Events**, а пример комманд в **Bot/Commands**

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
> Работает так же для группы комманд и глобольно:

```php
$cmd = new Command([
	'start' => '!'
]);
// или
$cmd = new Command([
	'start' => ['!', '^', '/']
]);
```
> Группы

```php
$cmd->group('string', function() {
	
})->start('!');
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

Остальное потом
