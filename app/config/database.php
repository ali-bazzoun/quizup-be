<?php

class Database
{
    private static $host = 'db';
    private static $db   = 'quizapp';
    private static $user = 'quizuser';
    private static $pass = 'quizpass';
    private static $charset = 'utf8mb4';

    private static $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    private static $pdo = null;

    public static function get_connection(): PDO
    {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=" . self::$charset;

            try {
                self::$pdo = new PDO($dsn, self::$user, self::$pass, self::$options);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
