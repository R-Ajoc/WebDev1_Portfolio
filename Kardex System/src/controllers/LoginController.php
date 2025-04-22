<?php
require_once __DIR__ . '/../models/User.php';

class LoginController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function isLoginInputValid($data)
    {
        $errors = [];

        if (empty($data['email'])) $errors['emptyEmail'] = 'Email cannot be empty.';
        if (empty($data['password'])) $errors['emptyPassword'] = 'Password cannot be empty.';

        return $errors;
    }

    public function login($data)
    {
        $errors = $this->isLoginInputValid($data);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }

        // Check if the user exists by email
        $user = $this->userModel->getUserByEmail($data['email']);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['errors' => ['userNotFound' => 'User not found.']]);
            return;
        }

        // Verify password
        if (!$this->userModel->verifyPassword($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['errors' => ['incorrectPassword' => 'Incorrect password.']]);
            return;
        }

        // Password is correct, generate a session or token (simple example)
        require_once __DIR__ . '/../../config/session.php';

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['department_id'] = $user['department_id'];
        $_SESSION['deptname'] = $user['deptname'];

        $_SESSION['last_regeneration'] = time();

        echo json_encode(['success' => true, 'message' => 'Login successful.']);
    }
}
