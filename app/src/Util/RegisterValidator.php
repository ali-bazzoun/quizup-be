<?php

class RegisterValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['email']))
        {
            $errors['email'] = 'Email is required.';
        }
        elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $errors['email'] = 'Email is invalid.';
        }

        if (empty($data['password']))
        {
            $errors['password'] = 'Password is required.';
        }
        else
        {
            $password = $data['password'];
            $length = strlen($password);

            if ($length < 8 || $length > 64)
            {
                $errors['password'] = 'Password must be between 8 and 64 characters.';
            }
            elseif (!preg_match('/^[\x20-\x7E]*$/', $password))
            {
                $errors['password'] = 'Password contains invalid characters.';
            }
        }

        return $errors;
    }
}
