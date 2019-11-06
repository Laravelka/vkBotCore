<?php

namespace Bot\Events;

class Confirmation
{
	public function handle($data, $request)
	{
		if ($data->group_id == GROUP_ID)
		{
			exit(CONFIRM);
		}
		else
		{
			exit('Пашол нахой');
		}
	}
}
