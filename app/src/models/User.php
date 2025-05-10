<?php

class User
{
	public $id;
	public $email;
	public $password_hash;
	public $created_at;
	public $updated_at;

	public function __construct(array $data)
	{
		$this->id            = $data['id'] ?? null;
		$this->email         = $data['email'] ?? '';
		$this->password_hash = $data['password_hash'] ?? '';
		$this->created_at    = $data['created_at'] ?? null;
		$this->updated_at    = $data['updated_at'] ?? null;
	}
}
