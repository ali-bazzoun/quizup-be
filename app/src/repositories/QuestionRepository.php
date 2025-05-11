<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/OptionRepository.php';
require_once __DIR__ . '/../models/Question.php';

class QuestionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('questions', Question::class);
    }

    public function find_by_quiz_id(int $quiz_id): array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE quiz_id = :quiz_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['quiz_id' => $quiz_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new $this->model_class($row), $rows);
    }

    public function find_by_quiz_id_with_options(int $quiz_id): array
    {
        $questions = $this->find_by_quiz_id($quiz_id);

        if (empty($questions))
        {
            return [];
        }

        $option_repo = new OptionRepository();
        foreach($questions as $question)
        {
            $question->options = $option_repo->find_by_question_id($question->id);
        }
        return $questions;
    }
}