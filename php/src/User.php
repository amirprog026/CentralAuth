<?php

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($platform, $username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO users (platform, username, password) VALUES (:platform, :username, :password)");
        $stmt->execute([
            'platform' => $platform,
            'username' => $username,
            'password' => $hashedPassword,
        ]);

        return $this->db->lastInsertId();
    }
}
