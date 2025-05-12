<?php

require_once __DIR__ . '/BaseModel.php';

class Option extends BaseModel
{
	public ?int		$id				= NULL;
	public ?int 	$question_id	= NULL;
	public ?string	$option_text	= NULL;
	public ?bool	$is_correct		= NULL;
}
