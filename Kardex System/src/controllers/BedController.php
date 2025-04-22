<?php
require_once __DIR__ . '/../models/Bed.php';
require_once __DIR__ . '/../models/Patient.php';

class BedController
{
    private $bedModel;
    private $patientModel;

    public function __construct($pdo)
    {
        $this->bedModel = new Bed($pdo);
        $this->patientModel = new Patient($pdo);
    }

    private function respondJson($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function isNewBedInformationEmpty($data)
    {
        $errors = [];

        if (empty($data['bed_number'])) $errors['emptyBed'] = 'Bed number cannot be empty.';
        if (!is_numeric($data['bed_number'])) $errors['invalidBedNumber'] = 'Invalid bed number.';
        if (empty($data['department_id'])) $errors['emptyDepartment'] = 'Please select a department.';

        return $errors;
    }

    // Fetch all beds
    public function getAllBeds()
    {
        try {
            $beds = $this->bedModel->getAllBeds();

            return $this->respondJson([
                'success' => true,
                'data' => $beds
            ]);
        } catch (Exception $e) {
            return $this->respondJson([
                'success' => false,
                'error' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get bed details by bed number
    public function getBedByNumber($bed_number)
    {
        $bed = $this->bedModel->getBedByNumber($bed_number);
        if (!empty($bed)) {
            echo json_encode($bed);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Bed not found.']);
        }
    }

    // Assign a patient to a bed (check if the bed is available first)
    public function assignBedToPatient($patient_uuid, $bed_number)
    {
        // Check if bed exists
        $bed = $this->bedModel->getBedByNumber($bed_number);
        if ($bed) {
            // Check if the patient already has a bed
            $patient = $this->patientModel->getPatientByUuid($patient_uuid);
            if ($patient && empty($patient['bed_id'])) {
                $this->bedModel->assignBedToPatient($patient_uuid, $bed_number);
                echo json_encode(['success' => true, 'message' => 'Bed assigned to patient.']);
            } else {
                echo json_encode(['errors' => 'Patient already has a bed or invalid patient ID.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Bed not found.']);
        }
    }

    // Remove bed assignment (optional, depend sa atong flow)
    public function removeBedAssignment($patient_id)
    {
        $patient = $this->patientModel->getPatientById($patient_id);
        if ($patient) {
            $this->bedModel->assignBedToPatient($patient_id, null);
            echo json_encode(['success' => true, 'message' => 'Bed assignment removed.']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Patient not found.']);
        }
    }

    // Add a new bed (with duplicate bed number check)
    public function addBed($data)
    {
        $errors = $this->isNewBedInformationEmpty($data);

        if (!empty($errors)) {
            http_response_code(409);
            echo json_encode(['errors' => $errors]);
            return;
        }

        // Check if bed number already exists
        $existingBed = $this->bedModel->getBedByNumber($data['bed_number']);
        if ($existingBed) {
            http_response_code(409);
            echo json_encode(['errors' => ['bedNumberTaken' => 'Bed number is already Taken.']]);
            return;
        }

        // Insert the new bed
        $success = $this->bedModel->addBed($data['bed_number'], $data['department_id']);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Bed added successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add bed.']);
        }
    }

    public function deleteBedById($data)
    {
        try {
            if (empty($data['bed_id'])) {
                return $this->respondJson([
                    'success' => false,
                    'error' => 'Bed ID is required.'
                ], 400);
            }

            $result = $this->bedModel->deleteBedById($data['bed_id']);

            if (isset($result['success']) && $result['success']) {
                return $this->respondJson([
                    'success' => true,
                    'message' => 'Bed deleted successfully.'
                ]);
            }

            return $this->respondJson([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to delete bed.'
            ], 500);
        } catch (Exception $e) {
            return $this->respondJson([
                'success' => false,
                'error' => $result['error'] ?? 'Unknown error'
            ], 500);
        }
    }
}
