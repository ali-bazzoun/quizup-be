<?php

require_once __DIR__ . '/BaseModel.php';

class Question extends BaseModel
{
	public ?int		$id				= NULL;
	public ?int 	$quiz_id		= NULL;
	public ?string	$text			= NULL;
	public array	$options		= [];
}
