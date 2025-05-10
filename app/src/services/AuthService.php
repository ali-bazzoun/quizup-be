<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

class AuthService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function attemptLogin(string $email, string $password): ?User
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) {
            return null;
        }
        if (!password_verify($password, $user->password)) {
            return null;
        }
        return $user;
    }
}
