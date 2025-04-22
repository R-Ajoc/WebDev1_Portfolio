<?php

class Procedure
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Add a new procedure record
    public function add($patientId, $data)
    {
        $sql = "INSERT INTO procedures (
                    patient_id, iv_access, iv_tubing, ngt, date_contraption_others, procedure_text
                ) VALUES (
                    :patient_id, :iv_access, :iv_tubing, :ngt, :date_contraption_others, :procedure_text
                )";

        $stmt = $this->pdo->prepare($sql);

        $params = [
            ':patient_id' => $patientId,
            ':iv_access' => $data['iv_access'] ?? null,
            ':iv_tubing' => $data['iv_tubing'] ?? null,
            ':ngt' => $data['ngt'] ?? null,
            ':date_contraption_others' => $data['date_contraption_others'] ?? null,
            ':procedure_text' => $data['procedure_text'] ?? null
        ];

        return $stmt->execute($params);
    }

    // Get all procedures for a specific patient
    public function getProceduresByPatient($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM procedures WHERE patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // delete procedure
    public function delete($patient_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM procedures WHERE patient_id = ?");
        return $stmt->execute([$patient_id]);
    }

    // update procedure
    public function updateProcedure($data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE procedures SET 
                procedure_name = ?, 
                procedure_date = ?, 
                contraption_start_date = ?, 
                laboratory_diagnostic = ?, 
                status = ? 
            WHERE procedure_id = ?
        ");

        return $stmt->execute([
            $data['procedure_name'],
            $data['procedure_date'],
            $data['contraption_start_date'],
            $data['laboratory_diagnostic'],
            $data['status'],
            $data['procedure_id']
        ]);
    }
}
