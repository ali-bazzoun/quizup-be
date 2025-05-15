<?php

require_once __DIR__ . '/../Model/Quiz.php';
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/QuestionRepository.php';

class QuizRepository extends BaseRepository
{
    private QuestionRepository $question_repo;

    public function __construct()
    {
        $fillable = ['title', 'description', 'image_path'];
        parent::__construct('quizzes', Quiz::class, $fillable);
        $this->question_repo = new QuestionRepository();
    }

    public function exists_by_title(string $title): bool
    {
        $sql = "SELECT 1 FROM `{$this->table}` WHERE title = :title LIMIT 1";
        $result = $this->execute_query($sql, ['title' => $title], 'fetch');
        return $result !== false;
    }

    public function all_with_questions_and_options(): array
    {
        $quizzes = $this->all();
        if (empty($quizzes))
            return [];
        foreach ($quizzes as $quiz)
            $quiz->questions = $this->question_repo->all_by_quiz_id_with_options($quiz->id);
        return $quizzes;
    }
}
