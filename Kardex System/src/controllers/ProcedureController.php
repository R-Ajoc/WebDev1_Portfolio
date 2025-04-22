<?php
require_once __DIR__ . '/../models/Procedure.php';

class ProcedureController
{
    private $procedureModel;

    public function __construct($pdo)
    {
        $this->procedureModel = new Procedure($pdo);
    }

    public function add($data)
    {
        // Basic validation
        if (empty($data['patient_id'])) {
            http_response_code(409);
            echo json_encode(['errors' => ['patientIdMissing' => 'Patient ID is required.']]);
            return;
        }

        $this->delete($data['patient_id']);

        // Flatten and merge payload
        $flattenedData = array_merge(
            ['patient_id' => $data['patient_id']],
            $data['contraption_dates'],
            $data['procedure_text']
        );

        // Save the procedure data (adjust your model's add method to accept these keys)
        $success = $this->procedureModel->add($data['patient_id'], $flattenedData);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Contraption data saved successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save contraption data.']);
        }
    }


    // Get procedures for a patient
    public function getByPatient($patient_id)
    {
        $procedures = $this->procedureModel->getProceduresByPatient($patient_id);
        echo json_encode($procedures);
    }

    // Delete a procedure
    public function delete($id)
    {
        $success = $this->procedureModel->delete($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Laboratory record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete laboratory record']);
        }
    }
    // Update a procedure
    public function updateProcedure()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['procedure_id']) || !isset($data['procedure_name']) || !isset($data['procedure_date']) || !isset($data['contraption_start_date']) || !isset($data['laboratory_diagnostic']) || !isset($data['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'All fields are required']);
            return;
        }

        $result = $this->procedureModel->updateProcedure($data);

        echo json_encode(['success' => $result]);
    }
}
