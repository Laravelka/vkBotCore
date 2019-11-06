<?php

namespace Bot\Events;

use Engine\Classes\Command;

class MessageNew
{
	public function handle($data, $request)
	{
		$command = new Command;
		
		$command->group('photo', function() {
			$this->add('anime', 'Bot\Commands\Photos@getRandomAnime');
			$this->add('upload', 'Bot\Commands\Photos@uploadPhoto');
		});
		
		$command->group('video', function() {
			$this->add('coub', 'Bot\Commands\Videos@getCoubRandom');
			$this->add('animem', 'Bot\Commands\Videos@getAniMemesRandom');
		});
		$command->add('Начать|Старт', 'Bot\Commands\Start@greetingUsers')->start();
			
		$command->dispatcher($data, $request);
	}
}
