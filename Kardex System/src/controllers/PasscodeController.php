<?php
require_once __DIR__ . '/../models/User.php';

class PasscodeController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function verify($data)
    {

        $user = $this->userModel->getUserById($data['user_id']);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found.']);
            return;
        }

        if ($this->userModel->verifyPasscode($data['inputPasscode'], $user['passcode'])) {
            echo json_encode(['success' => true, 'message' => 'Passcode verified.']);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid passcode.']);
        }
    }
}
