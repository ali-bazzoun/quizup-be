<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Exception/DatabaseQueryException.php';
require_once __DIR__ . '/../Util/Logging.php';

abstract class BaseRepository
{
    protected \PDO 		$pdo;
	protected string	$table;
    protected string	$model_class;
    protected array     $creatable;
    protected array     $updatable;

    public function __construct(string $table, string $model_class, array $field_config = [])
    {
        $this->pdo          = Database::get_connection();
        $this->table        = $table;
        $this->model_class  = $model_class;
        $this->creatable    = $field_config['create'] ?? [];
        $this->updatable    = $field_config['update'] ?? [];
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
        if (!is_array($rows))
            return [];
        return array_map(fn($data) => new $this->model_class($data), $rows);
    }

    public function create(array $data): ?object
    {
        $data = $this->filter_allowed_data($data, $this->creatable);
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);
        $sql = sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $this->execute_query($sql, $data);
        $id = (int) $this->pdo->lastInsertId();
        return $this->find($id);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->filter_allowed_data($data, $this->updatable);
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

    protected function filter_allowed_data(array $data, array $allowed_fields): array
    {
        $filtered_data = [];
        foreach($data as $key => $value)
            if (in_array($key, $allowed_fields))
                $filtered_data[$key] = $value;
        return $filtered_data;
    }

    protected function execute_query(string $sql, array $params = [], string $resultType = 'row_count')
    {
        try
        {
            $stmt = $this->pdo->prepare($sql);
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
            throw new DatabaseQueryException("Database query failed", 0, $e);
        }
    }

    public function startTransactionIfNotActive(): void
    {
        if (!$this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }
    }

    public function commitTransaction(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }

    public function rollbackTransaction(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
}
