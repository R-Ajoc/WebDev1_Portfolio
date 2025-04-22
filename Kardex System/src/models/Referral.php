<?php

class Referral
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Inserts a new referral record for a patient to a department
    public function addReferral($patient_id, $reason, $department_id = null)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO referrals (patient_id, department_id, reason, referred_at) 
            VALUES (?, ?, ?, NOW())"
        );

        return $stmt->execute([$patient_id,  $department_id !== '' ? $department_id : null, $reason]);
    }

    // Retrieves all referrals for a specific patient, most recent first
    public function getReferralsByPatient($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT r.*, d.deptname AS department_name 
                                        FROM referrals r
                                        LEFT JOIN departments d ON r.department_id = d.department_id
                                        WHERE r.patient_id = ?
                                        ORDER BY referred_at DESC;
                                        ");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Deletes a referral record by its ID
    public function delete($patient_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM referrals WHERE patient_id = ?");
        return $stmt->execute([$patient_id]);
    }
}
