<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/QuestionRepository.php';
require_once __DIR__ . '/../models/Quiz.php';

class QuizRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('quizzes', Quiz::class);
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM `quizzes`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $data) => new $this->model_class($data), $rows);
    }

    public function get_all_with_questions(): array
    {
        $quizzes = $this->get_all();

        if (empty($quizzes))
        {
            return [];
        }

        $question_repo = new QuestionRepository();
        foreach ($quizzes as $quiz)
        {
            $quiz->questions = $question_repo->find_by_quiz_id_with_options($quiz->id);
        }
        return $quizzes;
    }
}
