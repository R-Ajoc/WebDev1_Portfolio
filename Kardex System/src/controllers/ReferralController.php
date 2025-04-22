<?php
require_once __DIR__ . '/../models/Referral.php';

class ReferralController
{
    private $referralModel;

    public function __construct($pdo)
    {
        $this->referralModel = new Referral($pdo);
    }

    // Handles adding a new referral (expects patient_id, department_id, reason)
    public function add($data)
    {
        if (empty($data['patient_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Patient ID is missing']);
            return;
        }

        $this->delete($data['patient_id']);

        $department_id = isset($data['department_id']) && $data['department_id'] !== '' && $data['department_id'] != '0'
            ? (int) $data['department_id']
            : null;

        $reason = isset($data['reason']) && $data['reason'] !== '' ? $data['reason'] : null;

        $success = $this->referralModel->addReferral(
            $data['patient_id'],
            $reason,
            $department_id
        );

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Referral added successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add referral.']);
        }
    }

    // Gets all referrals for a patient
    public function getByPatient($patient_id)
    {
        if (empty($patient_id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing patient ID.']);
            return;
        }

        $referrals = $this->referralModel->getReferralsByPatient($patient_id);
        echo json_encode($referrals);
    }

    // Deletes a referral by ID
    public function delete($referral_id)
    {
        if (empty($referral_id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing referral ID.']);
            return;
        }

        $success = $this->referralModel->delete($referral_id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Referral deleted successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete referral.']);
        }
    }
}
