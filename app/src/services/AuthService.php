<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserDTO.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../utils/RegisterValidator.php';
require_once __DIR__ . '/../utils/logging.php';

class AuthService
{
    private UserRepository $user_repo;
    private RegisterValidator $register_validator;

    public function __construct()
    {
        $this->user_repo = new UserRepository();
    }

    public function attemptLogin(string $email, string $password): ?UserDTO
    {
        $user = $this->user_repo->findByEmail($email);
        if (!$user) {
            return null;
        }
        if (!password_verify($password, $user->password)) {
            return null;
        }
        return new UserDTO($user);
    }

    public function attemptRegister(string $email, string $password): ?UserDTO
    {
        $this->register_validator = new RegisterValidator();
        if (!$this->register_validator->isValid($email, $password)) {
            return null;
        }
        if ($this->user_repo->existsByEmail($email)) {
            log_error("email already exists.");
            return null;
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $success = $this->user_repo->create([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);
        if (!$success) {
            return null;
        }
        return new UserDTO($this->user_repo->findByEmail($email));
    }
}
