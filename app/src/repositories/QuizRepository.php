<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/QuestionRepository.php';
require_once __DIR__ . '/../models/Quiz.php';

class QuizRepository extends BaseRepository
{
    public function __construct()
    {
        $fillable = [
            'title',
            'quiz_description',
            'image_path'
        ];
        parent::__construct('quizzes', Quiz::class, $fillable);
    }

    public function all_with_questions(): array
    {
        $quizzes = $this->all();

        if (empty($quizzes))
        {
            return [];
        }

        $question_repo = new QuestionRepository();
        foreach ($quizzes as $quiz)
        {
            $quiz->questions = $question_repo->all_by_quiz_id_with_options($quiz->id);
        }
        return $quizzes;
    }
}
