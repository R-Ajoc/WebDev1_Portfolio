<?php
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Bed.php';
require_once __DIR__ . '/../../config/session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PatientController
{
    private $patientModel;
    private $bedModel;

    public function __construct($pdo)
    {
        $this->patientModel = new Patient($pdo);
        $this->bedModel = new Bed($pdo);
    }

    private function respondJson($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit; // ← Make sure to add this!
    }

    // Validate patient input
    public function validatePatientData($data)
    {
        $errors = [];

        if (empty($data['lastname'])) $errors['emptyLastname'] = 'Last name is required.';
        if (empty($data['firstname'])) $errors['emptyFirstname'] = 'First name is required.';
        if (empty($data['diagnosis'])) $errors['emptyDiagnosis'] = 'Diagnosis is required.';

        return $errors;
    }

    // Fetch all patients
    public function getAllPatients()
    {
        try {
            $patients = $this->patientModel->getAllPatients();

            return $this->respondJson([
                'success' => true,
                'data' => $patients
            ]);
        } catch (Exception $e) {
            return $this->respondJson([
                'success' => false,
                'message' => 'No patients found.'
            ], 404);
        }
    }


    public function create($data)
    {
        $errors = $this->validatePatientData($data);

        if (!empty($errors)) {
            return $this->respondJson([
                'success' => false,
                'errors' => $errors
            ], 400);
        }

        $uuid = $this->generateUuidV4(); // generate random uuid
        $patient_uuid = pack("H*", str_replace('-', '', $uuid)); // convert to 16-byte binary

        $bed_uuid = null;

        // Handle optional bed number
        if (!empty($data['bedNumber'])) {
            $bed = $this->bedModel->getBedByNumber($data['bedNumber']);

            if (empty($bed)) {
                return $this->respondJson([
                    'success' => false,
                    'message' => 'Bed not found.'
                ], 400);
            }

            $bed_uuid = $bed['bed_uuid']; // assume bed has a UUID
        }

        // Pass null if no bed was found or provided
        $success = $this->patientModel->createPatient(
            $data['lastname'],
            $data['firstname'],
            $data['midInit'],
            $data['gender'],
            $data['diagnosis'],
            $data['civilStatus'],
            $data['nationality'],
            $data['religion'],
            $data['physician'],
            $data['dob'],
            $patient_uuid,
            $bed_uuid // now nullable
        );

        if ($success) {
            // Only try assigning if a bedNumber was provided
            if (!empty($data['bedNumber'])) {
                $bedAssignmentSuccess = $this->bedModel->assignBedToPatient($patient_uuid, $data['bedNumber']);

                if (!$bedAssignmentSuccess) {
                    return $this->respondJson([
                        'success' => false,
                        'message' => 'An error occurred when assigning a bed to the patient.'
                    ]);
                }
            }

            return $this->respondJson([
                'success' => true,
                'message' => 'Patient added successfully.'
            ]);
        }

        return $this->respondJson([
            'success' => false,
            'message' => 'Failed to add patient.'
        ], 500);
    }

    function generateUuidV4()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // Version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // Variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function update($patient_id, $data)
    {

        if (is_string($data)) {
            error_log("Raw update data (string): " . $data);
            $data = json_decode($data, true);
        }

        if (!empty($errors)) {
            return $this->respondJson([
                'success' => false,
                'errors' => $errors
            ], 400);
        }


        $success = $this->patientModel->updatePatient(
            $patient_id,
            $data['lastname'] ?? null,
            $data['firstname'] ?? null,
            $data['midinit'] ?? null,
            $data['bed_id'] ?? null,
            $data['gender'] ?? null,
            $data['diagnosis'] ?? null,
            $data['status'] ?? null,
            $data['nationality'] ?? null,
            $data['religion'] ?? null,
            $data['physician'] ?? null,
            $data['date_of_birth'] ?? null
        );

        if ($success) {
            return $this->respondJson([
                'success' => true,
                'message' => 'Patient updated successfully.'
            ]);
        }

        return $this->respondJson([
            'success' => false,
            'message' => 'Failed to update patient.'
        ], 500);
    }


    public function getPatientsByDepartment()
    {
        // Make sure the user is logged in and department_id exists in session
        if (!isset($_SESSION['department_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        try {
            $patients = $this->patientModel->getPatientsByDepartmentId();
            echo json_encode(['success' => true, 'data' => $patients]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to fetch patients', 'error' => $e->getMessage()]);
        }
    }

    public function getPatientById($patient_id)
    {
        $patient = $this->patientModel->getPatientById($patient_id);

        if ($patient) {
            return $this->respondJson([
                'success' => true,
                'patient' => $patient
            ]);
        }

        return $this->respondJson([
            'success' => false,
            'error' => 'No patient found for this bed.'
        ], 404);
    }

    public function deletePatientById($data)
    {

        try {
            if (empty($data['patient_id'])) {
                return $this->respondJson([
                    'success' => false,
                    'error' => 'Patient ID is required.'
                ], 400);
            }

            $result = $this->patientModel->deletePatientById($data['patient_id']);

            if (isset($result['success']) && $result['success']) {
                return $this->respondJson([
                    'success' => true,
                    'message' => 'Patient deleted successfully.'
                ]);
            }

            return $this->respondJson([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to delete patient.'
            ], 500);
        } catch (Exception $e) {
            return $this->respondJson([
                'success' => false,
                'error' => $result['error'] ?? 'Unknown error'
            ], 500);
        }
    }
}
