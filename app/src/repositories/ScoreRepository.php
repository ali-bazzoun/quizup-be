<?php

require_once __DIR__ . '/../models/Score.php';
require_once __DIR__ . '/BaseRepository.php';

class ScoreRepository extends BaseRepository
{
    public function __construct()
    {
        $fillable = ['user_id', 'quiz_id', 'score'];
        parent::__construct('scores', Score::class, $fillable);
    }
}