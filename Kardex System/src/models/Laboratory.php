<?php

class Laboratory
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Add a new laboratory record
    public function add($patient_id, $date, $laboratory_diagnostic, $status)
    {
        $stmt = $this->pdo->prepare("INSERT INTO laboratories (patient_id, date, laboratory_diagnostic, status, created_at) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$patient_id, $date, $laboratory_diagnostic, $status]);
    }

    // Get all lab records for a patient ordered by latest first
    public function getLaboratoriesByPatient($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM laboratories WHERE patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a laboratory record by its ID
    public function deleteById($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM laboratories WHERE patient_id = ?");
        return $stmt->execute([$id]);
    }
}
