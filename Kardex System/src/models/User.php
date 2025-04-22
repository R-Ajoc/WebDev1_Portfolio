<?php
// Handles user-related database operations, such as registering users, logging them, etc.

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT 
                                    u.user_id AS id,
                                    CONCAT_WS(' ',
                                        CONCAT(UPPER(LEFT(u.FIRSTNAME, 1)), LOWER(SUBSTRING(u.FIRSTNAME, 2))),
                                        CASE 
                                        WHEN u.MIDINIT IS NOT NULL AND u.MIDINIT != '' THEN CONCAT(UPPER(MIDINIT),'.')
                                        ELSE NULL 
                                        END,
                                        CONCAT(UPPER(LEFT(u.LASTNAME, 1)), LOWER(SUBSTRING(u.LASTNAME, 2)))
                                    ) AS fullname,
                                    u.email,
                                    d.deptname AS department,
                                    COALESCE(u.contactNo, 'N/A') AS contact,
                                    DATE(u.created_at) AS created_at
                                FROM USERS u JOIN DEPARTMENTS d ON u.department_id = d.department_id;");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT u.*, d.deptname FROM users u JOIN departments d ON u.department_id = d.department_id WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function register($lastname, $firstname, $midinit, $email, $password, $department_id, $passcode, $contactNo)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $hashedPasscode = password_hash($passcode, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO users (lastname, firstname, midinit, email, password, passcode, department_id, contactNo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$lastname, $firstname, $midinit, $email, $hashedPassword, $hashedPasscode, $department_id, $contactNo]);
    }

    public function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }

    public function verifyPasscode($inputPasscode, $hashedPasscode)
    {
        return password_verify($inputPasscode, $hashedPasscode);
    }

    public function getUserById($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function deleteUserById($user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $success = $stmt->execute([$user_id]);

        if ($success) {
            return ['success' => true];
        } else {
            return ['error' => 'Failed to delete user.'];
        }
    }

    public function updatePasscode($user_id, $current_passcode, $newPasscode)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['error' => 'User not found.'];
        }

        if (!password_verify($current_passcode, $user['passcode'])) {
            return ['error' => 'Current passcode is incorrect.'];
        }

        if (!preg_match("/^\d{4}$/", $newPasscode)) {
            return ['error' => 'Passcode must be exactly 4 digits.'];
        }

        $hashedPasscode = password_hash($newPasscode, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("UPDATE users SET passcode = ? WHERE user_id = ?");
        if ($stmt->execute([$hashedPasscode, $user_id])) {
            return ['success' => true];
        } else {
            return ['error' => 'Failed to update passcode.'];
        }
    }

    public function updatePatientById($patient_id, $data)
    {
        $stmt = $this->pdo->prepare("
        UPDATE patients SET
            lastname = :lastname,
            firstname = :firstname,
            midinit = :midinit,
            gender = :gender,
            diagnosis = :diagnosis,
            status = :status,
            nationality = :nationality,
            religion = :religion,
            physician = :physician,
            date_of_birth = :date_of_birth
        WHERE patient_id = :patient_id
    ");

        $success = $stmt->execute([
            ':lastname' => $data['lastname'],
            ':firstname' => $data['firstname'],
            ':midinit' => $data['midinit'],
            ':gender' => $data['gender'],
            ':diagnosis' => $data['diagnosis'],
            ':status' => $data['status'],
            ':nationality' => $data['nationality'],
            ':religion' => $data['religion'],
            ':physician' => $data['physician'],
            ':date_of_birth' => $data['date_of_birth'],
            ':patient_id' => $patient_id
        ]);

        if ($success) {
            return ['success' => true];
        } else {
            return ['error' => 'Failed to update patient.'];
        }
    }
}
