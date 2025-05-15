<?php

require_once __DIR__ . '/BaseModel.php';

class Option extends BaseModel
{
	public ?int		$id				= NULL;
	public ?int 	$question_id	= NULL;
	public ?string	$text			= NULL;
	public ?int		$is_correct		= NULL;
}
