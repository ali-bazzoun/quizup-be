<?php

require_once __DIR__ . '/BaseModel.php';

class Score extends BaseModel
{
	public ?int		$id			= NULL;
	public ?int 	$user_id	= NULL;
	public ?int		$quiz_id	= NULL;
	public ?int		$score		= NULL;
}
