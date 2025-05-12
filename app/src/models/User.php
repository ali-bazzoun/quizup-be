<?php

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
	public ?int		$id				= NULL;
	public ?string	$email			= NULL;
	public ?string	$password_hash	= NULL;
	public ?string	$created_at		= NULL;
	public ?string	$updated_at		= NULL;
}
