<?php
require_once __DIR__ . '/../models/Treatment.php';

class TreatmentController
{
    private $treatmentModel;

    public function __construct($pdo)
    {
        $this->treatmentModel = new Treatment($pdo);
    }

    // Adds a new treatment record (expects patient_id and treatment_text from frontend)
    public function add($data)
    {
        // Basic validation: check for patient ID
        if (empty($data['patient_id'])) {
            http_response_code(409);
            echo json_encode(['errors' => ['patientIdMissing' => 'Patient ID is required.']]);
            return;
        }

        $this->delete(['patient_id']);

        // Flatten and prepare the data for saving
        $flattenedData = array_merge(
            ['patient_id' => $data['patient_id']],
            $data['treatments']
        );

        // Attempt to save the treatment
        $success = $this->treatmentModel->add($data['patient_id'], $flattenedData);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Treatment saved successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save treatment.']);
        }
    }

    // Fetches all treatments by patient ID
    public function getByPatient($patient_id)
    {
        if (empty($patient_id)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Missing patient ID.']);
            return;
        }

        $treatments = $this->treatmentModel->getTreatmentsByPatient($patient_id);

        if ($treatments) {
            echo json_encode($treatments);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'No treatments found for this patient.']);
        }
    }

    // Deletes all treatments for a patient
    public function delete($patient_id)
    {
        if (empty($patient_id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing patient ID.']);
            return;
        }

        $success = $this->treatmentModel->deleteByPatient($patient_id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'All treatments deleted for the patient.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete treatments.']);
        }
    }
}
