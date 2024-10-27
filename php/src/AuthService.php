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
        try {
            if ($this->user->findByUsername($username)) {
                return $this->response(['status' => 'error', 'message' => 'User already exists.'], 409);
            }

            $userId = $this->user->create($platform, $username, $password);

            // Add additional metadata
            $this->userMeta->addMeta($userId, 'registration_time', date('Y-m-d H:i:s'));

            return $this->response(['status' => 'success', 'data' => ['id' => $userId, 'username' => $username]], 201);
        } catch (Exception $e) {
            return $this->response(['status' => 'error', 'message' => 'Registration failed. ' . $e->getMessage()], 500);
        }
    }

    public function login($username, $password)
    {
        try {
            $user = $this->user->findByUsername($username);

            if (!$user || !password_verify($password, $user['password'])) {
                return $this->response(['status' => 'error', 'message' => 'Invalid credentials.'], 401);
            }

            // Get all user meta
            $userMeta = $this->userMeta->getAllMeta($user['id']);

            return $this->response([
                'status' => 'success',
                'data' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'platform' => $user['platform'],
                    'meta' => $userMeta
                ]
            ], 200);
        } catch (Exception $e) {
            return $this->response(['status' => 'error', 'message' => 'Login failed. ' . $e->getMessage()], 500);
        }
    }

    private function response($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
