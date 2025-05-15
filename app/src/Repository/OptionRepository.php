<?php

require_once __DIR__ . '/../Model/Option.php';
require_once __DIR__ . '/BaseRepository.php';

class OptionRepository extends BaseRepository
{
    public function __construct()
    {
        $field_config['create'] = ['question_id', 'text', 'is_correct'];
        $field_config['update'] = ['text', 'is_correct'];
        parent::__construct('options', Option::class, $field_config);
    }

    public function all_by_question_id(int $question_id): array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE question_id = :question_id";
        $rows = $this->execute_query($sql, ['question_id' => $question_id], 'fetch_all');
        return array_map(fn($row) => new $this->model_class($row), $rows);
    }
}