<?php

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/UserMeta.php';
require_once __DIR__ . '/../src/AuthService.php';

$db = (new Database())->getConnection();
$authService = new AuthService($db);


//define('API_KEY', 'your-static-api-key-here');// Set a static API key for the service or get from .htaccess
define('API_KEY', 'your-static-api-key-here');
// Function to check if the correct API key is provided
function checkApiKey() {
    $headers = getallheaders();
    if (!isset($headers['X-Api-Key']) || $headers['X-Api-Key'] !== API_KEY) {
        http_response_code(403); // Forbidden
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing API key']);
        exit();
    }
}


checkApiKey();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        if ($_GET['action'] === 'register') {
            $response = $authService->register($data['platform'], $data['username'], $data['password']);
            echo json_encode(['status' => 'success', 'data' => $response]);
        } elseif ($_GET['action'] === 'login') {
            $response = $authService->login($data['username'], $data['password']);
            echo json_encode(['status' => 'success', 'data' => $response]);
        } elseif ($_GET['action'] === 'add-meta') {
            // Add user meta data
            if (!isset($data['user_id'], $data['meta_key'], $data['meta_value'])) {
                throw new Exception('Missing parameters: user_id, meta_key, and meta_value are required.');
            }
            $userMeta = new UserMeta($db);
            $userMeta->addMeta($data['user_id'], $data['meta_key'], $data['meta_value']);
            echo json_encode(['status' => 'success', 'message' => 'User meta added successfully']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
