<?php

class Database
{
    private $pdo;

    public function __construct()
    {
        $config = include __DIR__ . '/../config/database.php';
        $dsn = "mysql:host=" . $config['db']['host'] . ";dbname=" . $config['db']['dbname'] . ";charset=" . $config['db']['charset'];

        try {
            $this->pdo = new PDO($dsn, $config['db']['username'], $config['db']['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
