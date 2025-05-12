<?php

function normalize_create_quiz_data(array $data): array
{
    foreach ($data['questions'] ?? [] as &$question)
    {
        foreach ($question['options'] ?? [] as &$option)
        {
            $option['is_correct'] = !empty($option['is_correct']) ? 1 : 0;
        }
    }

    return $data;
}

function normalize_create_question_data(array $data): array
{
    foreach ($data['options'] ?? [] as &$option)
    {
        $option['is_correct'] = !empty($option['is_correct']) ? 1 : 0;
    }
    return $data;
}
