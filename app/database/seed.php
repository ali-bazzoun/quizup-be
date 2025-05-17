<?php

require_once __DIR__ . '/../config/database.php';

function execute_query($db, $sql, $params = [])
{
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function are_tables_empty($db): bool
{
    $tables = ['users', 'quizzes', 'questions', 'options'];

    foreach ($tables as $table)
    {
        $sql = "SELECT COUNT(*) as count FROM $table";
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0)
            return false;
    }

    return true;
}

function seed_users($db)
{
    $users = [
        ['admin1@example.com', password_hash('adminPass', PASSWORD_DEFAULT)],
        ['admin2@example.com', password_hash('adminPass', PASSWORD_DEFAULT)],
        ['demo1@example.com',  password_hash('demoPass',  PASSWORD_DEFAULT)],
        ['demo2@example.com',  password_hash('demoPass',  PASSWORD_DEFAULT)],
    ];
    $insert_user_sql = "INSERT INTO users (email, password_hash) VALUES (?, ?)";
    foreach ($users as [$email, $hash])
        execute_query($db, $insert_user_sql, [$email, $hash]);
}

function seed_quizzes($db, $json_path)
{
    $data = json_decode(file_get_contents($json_path), true);

    if (!$data || !is_array($data))
        throw new Exception("Invalid or empty JSON file.");

    foreach ($data as $quiz)
    {
        $quiz_title = $quiz['title'];
        $quiz_description = $quiz['description'];

        $select_quiz_sql = "SELECT id FROM quizzes WHERE title = ?";
        $insert_quiz_sql = "INSERT INTO quizzes (title, description) VALUES (?, ?)";

        $quiz_stmt = execute_query($db, $select_quiz_sql, [$quiz_title]);
        $quiz_data = $quiz_stmt->fetch(PDO::FETCH_ASSOC);
        $quiz_id = $quiz_data ? $quiz_data['id'] : null;

        if (!$quiz_id)
        {
            execute_query($db, $insert_quiz_sql, [$quiz_title, $quiz_description]);
            $quiz_id = $db->lastInsertId();
        }

        $insert_question_sql = "INSERT INTO questions (quiz_id, text) VALUES (?, ?)";
        $insert_option_sql = "INSERT INTO options (question_id, text, is_correct) VALUES (?, ?, ?)";

        foreach ($quiz['questions'] as $question)
        {
            $question_text = $question['text'];

            execute_query($db, $insert_question_sql, [$quiz_id, $question_text]);
            $question_id = $db->lastInsertId();

            foreach ($question['options'] as $option)
            {
                $option_text = $option['text'];
                $is_correct = (int) $option['is_correct'];

                execute_query($db, $insert_option_sql, [$question_id, $option_text, $is_correct]);
            }
        }
    }
}

function seed_data()
{
    $db = Database::get_connection();

    if (!are_tables_empty($db))
        return;

    $db->beginTransaction();
    try
    {
        seed_users($db);
        seed_quizzes($db, __DIR__ . '/quizzes.json');
        $db->commit();
    }
    catch (Exception $e)
    {
        $db->rollBack();
        error_log("Seeding failed: " . $e->getMessage());
        throw $e;
    }
}

if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    seed_data();
}
