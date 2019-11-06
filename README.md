# vkBotCore
The core of the bot for callback api vk

Установка
-----------------------------------
Прописать нужные данные в index.php

События
-----------------------------------

### Через класс
```php
$event->set(Bot\Events\MessageNew::class); // message_new
```

### Через метод on
```php
$event->on('message_new', function($event, $request) {
  // ...
});
```

Команды
-----------------------------------

Подключение команд можно глянуть в Bot\Events

### Через класс
```php
$command->add('Начать|Старт', 'Bot\Commands\Start@greetingUsers')->start(); // бот ответит на Начать и старт
```

### Через callback
```php
$command->add('Начать|Старт', function($message, $args) {
  $message->send('Привет!');
})->start();
```

### Групповые команды
```php
$command->group('video', function() {
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

И т.д...
