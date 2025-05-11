<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../utils/logging.php';

abstract class BaseRepository
{
    protected \PDO 		$db;
	protected string	$table;
    protected string	$model_class;

    public function __construct(string $table, string $model_class)
    {
        $this->table = $table;
        $this->model_class = $model_class;
        $this->db = Database::get_connection();
    }

    public function find(int $id): ?object
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE id = :id LIMIT 1";
        $data = $this->execute_query($sql, ['id' => $id], true);

        if (!$data)
		{
            return null;
        }

        return new $this->model_class($data);
    }

    public function create(array $data): ?object
    {
        if (empty($data))
		{
            log_error("Data array cannot be empty.");
            throw new InvalidArgumentException("Data array cannot be empty.");
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);

        $sql = sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $this->execute_query($sql, $data);

        $id = (int) $this->db->lastInsertId();
        return $this->find($id);
    }

    public function update(int $id, array $data): bool
    {
        if (empty($data))
		{
            log_error("Data array cannot be empty.");
            throw new InvalidArgumentException("Data array cannot be empty.");
        }

		unset($data['id']);

        $set_clauses = array_map(fn($col) => "`$col` = :$col", array_keys($data));
        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE id = :id",
            $this->table,
            implode(', ', $set_clauses)
        );

        $params = $data;
        $params['id'] = $id;

        $affectedRows = $this->execute_query($sql, $params);
        return $affectedRows > 0;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM `{$this->table}` WHERE id = :id";
        $affectedRows = $this->execute_query($sql, ['id' => $id]);
        return $affectedRows > 0;
    }

    private function execute_query(string $sql, array $params = [], bool $fetch = false)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        if ($fetch)
		{
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $stmt->rowCount();
    }
}