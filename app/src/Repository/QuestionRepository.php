<?php

require_once __DIR__ . '/../Model/Question.php';
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/OptionRepository.php';

class QuestionRepository extends BaseRepository
{
    private OptionRepository $option_repo;

    public function __construct()
    {
        $fillable = ['quiz_id', 'text'];
        parent::__construct('questions', Question::class, $fillable);
        $this->option_repo = new OptionRepository();
    }

    public function all_by_quiz_id(int $quiz_id): array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE quiz_id = :quiz_id";
        $rows = $this->execute_query($sql, ['quiz_id' => $quiz_id], 'fetch_all');
        return array_map(fn($row) => new $this->model_class($row), $rows);
    }

    public function all_by_quiz_id_with_options(int $quiz_id): array
    {
        $questions = $this->all_by_quiz_id($quiz_id);
        if (empty($questions))
            return [];
        foreach($questions as $question)
            $question->options = $this->option_repo->all_by_question_id($question->id);
        return $questions;
    }
}