<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

class UserRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::get_connection();
    }

    public function exists_by_email(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    public function find_by_email(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    public function find_by_id(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    public function create(array $user_data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)'
        );
        return $stmt->execute([
            'email'         => $user_data['email'],
            'password_hash' => $user_data['password_hash'],
        ]);
    }

    public function update_password(int $user_id, string $new_password_hash): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET password_hash = :password_hash WHERE id = :id'
        );
        return $stmt->execute([
            'password_hash' => $new_password_hash,
            'id'            => $user_id,
        ]);
    }

    public function delete(int $user_id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $user_id]);
    }
}
