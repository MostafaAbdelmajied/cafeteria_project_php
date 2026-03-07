<?php

namespace Src\Classes;

use PDO;

class DB
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $host = env('DB_HOST');
        $dbName = env('DB_NAME');
        $username = env('DB_USER');
        $password = env('DB_PASS');
        $this->connection = new PDO(
            "mysql:host=$host;dbname=$dbName",
            $username,
            $password
        );

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function conn()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance->connection;
    }
}