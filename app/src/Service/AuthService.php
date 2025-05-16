<?php

require_once __DIR__ . '/../Model/User.php';
require_once __DIR__ . '/../Model/UserDTO.php';
require_once __DIR__ . '/../Repository/UserRepository.php';
require_once __DIR__ . '/../Util/RegisterValidator.php';
require_once __DIR__ . '/../Exception/RegisterExistedEmailException.php';
require_once __DIR__ . '/../Util/Logging.php';

class AuthService
{
    private UserRepository $user_repo;
    private RegisterValidator $register_validator;

    public function __construct()
    {
        $this->user_repo = new UserRepository();
    }

    public function attempt_login(string $email, string $password): ?UserDTO
    {
        $user = $this->user_repo->find_by_email($email);
            if (!$user)
                return null;
        if (!password_verify($password, $user->password_hash))
            return null;
        return new UserDTO($user);
    }

    public function attempt_register(string $email, string $password): ?UserDTO
    {
        if ($this->user_repo->exists_by_email($email))
        {
            error_handler('Exception', "Email already exists.", __FILE__, __LINE__);
            throw new RegisterExistedEmailException();
        }
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $success = $this->user_repo->create(['email' => $email, 'password_hash' => $password_hash]);
        if (!$success)
            return null;
        return new UserDTO($this->user_repo->find_by_email($email));
    }
}
