<?php

require_once __DIR__ . '/../Model/User.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Exception/DatabaseQueryException.php';
require_once __DIR__ . '/../Util/Logging.php';

class UserRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::get_connection();
    }

    public function exists_by_email(string $email): bool
    {
        $sql = 'SELECT 1 FROM users WHERE email = :email LIMIT 1';
        $result = $this->execute_query($sql, ['email' => $email], 'fetch');
        return (bool) $result;
    }

    public function find_by_email(string $email): ?User
    {
        $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
        $data = $this->execute_query($sql, ['email' => $email], 'fetch');
        return $data ? new User($data) : null;
    }

    public function find_by_id(int $id): ?User
    {
        $sql = 'SELECT * FROM users WHERE id = :id LIMIT 1';
        $data = $this->execute_query($sql, ['id' => $id], 'fetch');
        return $data ? new User($data) : null;
    }

    public function create(array $user_data): bool
    {
        $sql = 'INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)';
        $affected = $this->execute_query($sql, [
            'email'         => $user_data['email'],
            'password_hash' => $user_data['password_hash'],
        ]);
        return $affected > 0;
    }

    public function update_password(int $user_id, string $new_password_hash): bool
    {
        $sql = 'UPDATE users SET password_hash = :password_hash WHERE id = :id';
        $affected = $this->execute_query($sql, [
            'password_hash' => $new_password_hash,
            'id'            => $user_id,
        ]);
        return $affected > 0;
    }

    public function delete(int $user_id): bool
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $affected = $this->execute_query($sql, ['id' => $user_id]);
        return $affected > 0;
    }

    protected function execute_query(string $sql, array $params = [], string $resultType = 'row_count')
    {
        try
        {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            switch ($resultType)
            {
                case 'fetch': 
                    return $stmt->fetch(PDO::FETCH_ASSOC);

                case 'fetch_all':
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);

                case 'row_count':
                    return $stmt->rowCount();

                default:
                    error_handler('Error', "Invalid result type: $resultType", __FILE__, __LINE__);
                    throw new InvalidArgumentException("Invalid result type specified.");
            }
        }
        catch (PDOException $e)
        {
            error_handler('Error', $e->getMessage(), $e->getFile(), $e->getLine());
            throw new DatabaseQueryException("Query failed: $sql", 0, $e);
        }
    }
}
