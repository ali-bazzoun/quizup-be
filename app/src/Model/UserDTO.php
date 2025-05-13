<?php

require_once __DIR__ . '/User.php';

class UserDTO
{
    public string   $email;
    public int      $id;

    public function __construct(User $user)
    {
        $this->email    = $user->email;
        $this->id       = $user->id;
    }
}
