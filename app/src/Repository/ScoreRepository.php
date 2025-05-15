<?php

require_once __DIR__ . '/../Model/Score.php';
require_once __DIR__ . '/BaseRepository.php';

class ScoreRepository extends BaseRepository
{
    public function __construct()
    {
        $field_config['create'] = ['user_id', 'quiz_id', 'score'];
        $field_config['update'] = ['score'];
        parent::__construct('scores', Score::class, $field_config);
    }
}
