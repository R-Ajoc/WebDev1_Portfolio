<?php
require_once __DIR__ . '/../models/User.php';

class RegisterController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    private function respondJson($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    //Validate user input without the passcode
    public function isUserInputValid($data)
    {
        $errors = [];

        if (empty($data['lastname'])) $errors['emptyLastname'] = 'Last name cannot be empty.';
        if (empty($data['firstname'])) $errors['emptyFirstname'] = 'First name cannot be empty.';
        if (empty($data['email'])) $errors['emptyEmail'] = 'Email cannot be empty.';
        if (empty($data['password'])) $errors['emptyPassword'] = 'Password cannot be empty.';
        if (empty($data['department_id'])) $errors['emptyDepartment'] = 'A Department must be selected.';

        if (!empty($data['email'])) {
            $existing = $this->userModel->getUserByEmail($data['email']);
            if ($existing) {
                $errors['emailExists'] = 'Email already exists.';
            }
        }

        return $errors;
    }

    //Validate user input together with the passcode
    public function isFinalInformationValid($data)
    {
        $errors = [];

        if (empty($data['lastname'])) $errors['emptyLastname'] = 'Last name cannot be empty.';
        if (empty($data['firstname'])) $errors['emptyFirstname'] = 'First name cannot be empty.';
        if (empty($data['email'])) $errors['emptyEmail'] = 'Email cannot be empty.';
        if (empty($data['password'])) $errors['emptyPassword'] = 'Password cannot be empty.';
        if (empty($data['passcode'])) $errors['emptyPasscode'] = 'Passcode is required';
        if (empty($data['department_id'])) $errors['emptyDepartment'] = 'A Department must be selected.';

        return $errors;
    }

    // Step 1: validate form (without passcode)
    public function validateInitial($data)
    {
        $errors = $this->isUserInputValid($data);

        if (!empty($errors)) {
            return $this->respondJson([
                'success' => false,
                'errors' => $errors
            ], 400);
        }

        return $this->respondJson([
            'success' => true,
            'message' => 'Initial validation passed.'
        ]);
    }

    // Step 2: final registration (with passcode)
    public function register($data)
    {
        $errors = $this->isFinalInformationValid($data);

        if (!empty($errors)) {
            return $this->respondJson([
                'success' => false,
                'errors' => $errors
            ], 400);
        }

        //if success, insert to the database
        $success = $this->userModel->register(
            $data['lastname'],
            $data['firstname'],
            $data['midinit'],
            $data['email'],
            $data['password'],
            $data['department_id'],
            $data['passcode'],
            $data['contactNo'] ?? null
        );

        if ($success) {
            return $this->respondJson([
                'success' => true,
                'message' => 'User registered successfully.'
            ]);
        }

        return $this->respondJson([
            'success' => false,
            'message' => 'Something went wrong during registration.'
        ], 500);
    }

    // Optional: for updating passcodes later
    public function updatePasscode($data)
    {
        if (empty($data['user_id']) || empty($data['current_passcode']) || empty($data['new_passcode'])) {
            return $this->respondJson([
                'success' => false,
                'error' => 'User ID, current password, and new passcode are required.'
            ], 400);
        }

        $result = $this->userModel->updatePasscode(
            $data['user_id'],
            $data['current_passcode'],
            $data['new_passcode']
        );

        if (isset($result['success']) && $result['success']) {
            return $this->respondJson([
                'success' => true,
                'message' => 'Passcode updated successfully.'
            ]);
        }

        return $this->respondJson([
            'success' => false,
            'error' => $result['error'] ?? 'Unknown error'
        ], 400);
    }


    public function getAllUsers()
    {
        try {
            $users = $this->userModel->getAllUsers();

            return $this->respondJson([
                'success' => true,
                'data' => $users
            ]);
        } catch (Exception $e) {
            return $this->respondJson([
                'success' => false,
                'error' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteUserById($data)
    {
        try {
            if (empty($data['user_id'])) {
                return $this->respondJson([
                    'success' => false,
                    'error' => 'User ID is required.'
                ], 400);
            }

            $result = $this->userModel->deleteUserById($data['user_id']);

            if (isset($result['success']) && $result['success']) {
                return $this->respondJson([
                    'success' => true,
                    'message' => 'User deleted successfully.'
                ]);
            }

            return $this->respondJson([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to delete user.'
            ], 500);
        } catch (Exception $e) {
            return $this->respondJson([
                'success' => false,
                'error' => $result['error'] ?? 'Unknown error'
            ], 500);
        }
    }
}
