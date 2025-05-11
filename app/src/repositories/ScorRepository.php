<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../models/Score.php';

class ScoreRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('scores', Score::class);
    }
}