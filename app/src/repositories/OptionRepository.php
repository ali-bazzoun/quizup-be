<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../models/Option.php';

class OptionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('options', Option::class);
    }

    public function find_by_question_id(int $question_id): array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE question_id = :question_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['question_id' => $question_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new $this->model_class($row), $rows);
    }
}