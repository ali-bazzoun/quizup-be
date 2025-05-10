<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

class UserRepository
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getConnection();
	}

	public function existsByEmail(string $email): bool
	{
		$stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = :email LIMIT 1');
		$stmt->execute(['email' => $email]);
		return (bool) $stmt->fetchColumn();
	}

	public function findByEmail(string $email): ?User
	{
		$stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
		$stmt->execute(['email' => $email]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data ? new User($data) : null;
	}

	public function findById(int $id): ?User
	{
		$stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
		$stmt->execute(['id' => $id]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data ? new User($data) : null;
	}

	public function create(array $userData): bool
	{
		$stmt = $this->db->prepare(
			'INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)'
		);
		return $stmt->execute([
			'email'         => $userData['email'],
			'password_hash' => $userData['password_hash'],
		]);
	}

	public function updatePassword(int $userId, string $newPasswordHash): bool
	{
		$stmt = $this->db->prepare(
			'UPDATE users SET password_hash = :password_hash WHERE id = :id'
		);
		return $stmt->execute([
			'password_hash' => $newPasswordHash,
			'id'            => $userId,
		]);
	}

	public function delete(int $userId): bool
	{
		$stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
		return $stmt->execute(['id' => $userId]);
	}
}
