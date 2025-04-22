<?php

class IVFInfusion
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Adds a new IV fluid/infusion record for a patient
    public function addIVF($patient_id, $date, $bottle_no, $ivf, $rate, $display_table)
    {
        $stmt = $this->pdo->prepare("INSERT INTO ivf_infusions (patient_id, date, bottle_no, ivf, rate, display_table, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$patient_id, $date, $bottle_no, $ivf, $rate, $display_table]);
    }

    public function deleteIVFByPatientId($patient_id)
    {
        // If patient_id is accidentally passed as an array, extract the first value
        if (is_array($patient_id)) {
            $patient_id = $patient_id[0];
        }

        $stmt = $this->pdo->prepare("DELETE FROM ivf_infusions WHERE patient_id = ?");
        return $stmt->execute([$patient_id]);

        exit;
    }

    // Retrieves all IV fluid/infusion records for a specific patient
    public function getIVFsByPatientTable1($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ivf_infusions WHERE patient_id = ? AND display_table = '1' ORDER BY created_at DESC");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getIVFsByPatientTable2($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ivf_infusions WHERE patient_id = ?  AND display_table = '2' ORDER BY created_at DESC");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
