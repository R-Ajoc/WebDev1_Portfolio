<?php
require_once __DIR__ . '/../models/Laboratory.php';

class LaboratoryController
{
    private $labModel;

    // Constructor to link the model
    public function __construct($pdo)
    {
        $this->labModel = new Laboratory($pdo);
    }

    // Adds multiple laboratory records for a patient
    public function add($data)
    {
        $this->delete($data['patient_id']);

        $patientId = $data['patient_id'] ?? null;
        $laboratories = $data['laboratories'] ?? [];

        $allSuccess = true;

        foreach ($laboratories as $lab) {
            $date = $lab['date'] ?? '';
            $diagnostic = $lab['laboratory_diagnostic'] ?? '';
            $status = $lab['status'] ?? '';

            $success = $this->labModel->add($patientId, $date, $diagnostic, $status);

            if (!$success) {
                $allSuccess = false;
            }
        }

        echo json_encode(['success' => $allSuccess]);
    }

    // Fetches all laboratory records by patient
    public function getByPatient($patient_id)
    {
        $labs = $this->labModel->getLaboratoriesByPatient($patient_id);
        echo json_encode($labs);
    }

    public function delete($id)
    {
        $success = $this->labModel->deleteById($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Laboratory record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete laboratory record']);
        }
    }
}
