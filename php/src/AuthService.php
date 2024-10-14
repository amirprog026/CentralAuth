<?php

class AuthService
{
    private $user;
    private $userMeta;

    public function __construct($db)
    {
        $this->user = new User($db);
        $this->userMeta = new UserMeta($db);
    }

    public function register($platform, $username, $password)
    {
        if ($this->user->findByUsername($username)) {
            throw new Exception('User already exists.');
        }

        $userId = $this->user->create($platform, $username, $password);

        //additional metadata
        $this->userMeta->addMeta($userId, 'registration_time', date('Y-m-d H:i:s'));

        return ['id' => $userId, 'username' => $username];
    }

    public function login($username, $password)
    {
        $user = $this->user->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception('Invalid credentials.');
        }

        // Return user details or generate token here
        return ['id' => $user['id'], 'username' => $user['username']];
    }
}
