<?php
require_once __DIR__ . '/../models/IVFInfusion.php';

class IVFInfusionController
{
    private $ivfModel;

    // Constructor to link the model
    public function __construct($pdo)
    {
        $this->ivfModel = new IVFInfusion($pdo);
    }

    // Adds a new IVF record for a patient
    public function add($data)
    {
        $this->delete(['patient_id']);

        $patientId = $data['patient_id'] ?? null;
        $infusions = $data['ifusions'] ?? [];

        // Step 2: Add each new infusion
        $allSuccess = true;

        foreach ($infusions as $infusion) {
            $date      = $infusion['date'] ?? '';
            $bottle_no = $infusion['bottle_no'] ?? '';
            $ivf       = $infusion['ivf'] ?? '';
            $rate      = $infusion['rate'] ?? '';
            $display_table = $infusion['display_table'] ?? '1';


            $success = $this->ivfModel->addIVF($patientId, $date, $bottle_no, $ivf, $rate, $display_table);

            if (!$success) {
                $allSuccess = false;
            }
        }

        echo json_encode(['success' => $allSuccess]);
    }
    // Fetches all IVF/Infusion records by patient
    public function getByPatientTable1($patient_id)
    {
        $ivfs = $this->ivfModel->getIVFsByPatientTable1($patient_id);
        echo json_encode($ivfs);
    }

    public function getByPatientTable2($patient_id)
    {
        $ivfs = $this->ivfModel->getIVFsByPatientTable2($patient_id);
        echo json_encode($ivfs);
    }

    public function delete($patient_id)
    {
        $success = $this->ivfModel->deleteIVFByPatientId($patient_id);
        if (!$success) {
            echo json_encode(['error' => 'Failed to delete']);
        } else {
            echo json_encode(['success' => true, 'message' => 'deleted Successful']);
        }
    }
}
