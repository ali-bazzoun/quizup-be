<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Util/logging.php';

abstract class BaseRepository
{
    protected \PDO 		$db;
	protected string	$table;
    protected string	$model_class;
    protected array     $fillable;

    public function __construct(string $table, string $model_class, array $fillable)
    {
        $this->db = Database::get_connection();
        $this->table = $table;
        $this->model_class = $model_class;
        $this->fillable = $fillable;
    }

    public function find(int $id): ?object
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE id = :id LIMIT 1";
        $data = $this->execute_query($sql, ['id' => $id], 'fetch');
        if (!$data)
            return null;
        return new $this->model_class($data);
    }

    public function exists(int $id): bool
    {
        return ($this->find($id) != NULL);
    }

    public function all(): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        $rows = $this->execute_query($sql, [], 'fetch_all');
        return array_map(fn(array $data) => new $this->model_class($data), $rows);
    }

    public function create(array $data): ?object
    {
        if (empty($data))
		{
            log_error("Data array cannot be empty.");
            throw new InvalidArgumentException("Data array cannot be empty.");
        }
        $data = $this->filter_allowed_data($data);
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
        $data = $this->filter_allowed_data($data);
        $set_clauses = array_map(fn($col) => "`$col` = :$col", array_keys($data));
        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE id = :id",
            $this->table,
            implode(', ', $set_clauses)
        );
        $params = $data;
        $params['id'] = $id;
        $affected_rows = $this->execute_query($sql, $params);
        return $affected_rows > 0;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM `{$this->table}` WHERE id = :id";
        $affected_rows = $this->execute_query($sql, ['id' => $id]);
        return $affected_rows > 0;
    }

    protected function filter_allowed_data(array $data): array
    {
        $filtered_data = [];
        foreach($data as $key => $value)
            if (in_array($key, $this->fillable))
                $filtered_data[$key] = $value;
        return $filtered_data;
    }

    protected function execute_query(string $sql, array $params = [], string $resultType = 'row_count')
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
                throw new InvalidArgumentException("Invalid result type specified.");
        }
    }
}