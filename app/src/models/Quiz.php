<?php

require_once __DIR__ . '/BaseModel.php';

class Quiz extends BaseModel
{
	public ?int		$id					= NULL;
	public ?string	$title				= NULL;
	public ?string	$quiz_description	= NULL;
	public ?string	$image_path			= NULL;
	public array	$questions			= [];
}
