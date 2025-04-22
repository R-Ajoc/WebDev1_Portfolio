<?php

class Treatment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Inserts a new treatment entry for a specific patient
    public function add($patientId, $data)
    {
        // Optional: Delete existing treatments for this patient (if required)
        $deleteStmt = $this->pdo->prepare("DELETE FROM treatments WHERE patient_id = ?");
        $deleteStmt->execute([$patientId]);

        $sql = "INSERT INTO treatments (
                    patient_id, vs_value, io_value, nvs_value, position_value, others_text, created_at
                ) VALUES (
                    :patient_id, :vs_value, :io_value, :nvs_value, :position_value, :others_text, NOW()
                )";

        $stmt = $this->pdo->prepare($sql);

        // Prepare parameters, ensuring all expected keys exist
        $params = [
            ':patient_id'     => $patientId,
            ':vs_value'       => $data['vs_value'] ?? null,
            ':io_value'       => $data['io_value'] ?? null,
            ':nvs_value'      => $data['nvs_value'] ?? null,
            ':position_value' => $data['position_value'] ?? null,
            ':others_text'    => $data['others_text'] ?? null
        ];

        return $stmt->execute($params);
    }


    // Retrieves all treatments linked to a specific patient, sorted by most recent
    public function getTreatmentsByPatient($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM treatments WHERE patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteByPatient($patient_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM treatments WHERE patient_id = ?");
        return $stmt->execute([$patient_id]);
    }
}
