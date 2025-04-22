<?php
require_once __DIR__ . '/../models/EndorsementView.php';

class EndorsementViewController
{
    private $endorsementViewModel;

    public function __construct()
    {
        $this->endorsementViewModel = new EndorsementView();
    }

    // Add a new view for a patient by a user
    public function addView($user_id, $patient_id)
    {
        $success = $this->endorsementViewModel->addView($user_id, $patient_id);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'View recorded successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to record view.']);
        }
    }

    // Get the latest 4 viewers of the notes for a specific patient
    public function getLatestViewers($patient_id)
    {
        $viewers = $this->endorsementViewModel->getLatestViewers($patient_id);
        echo json_encode($viewers);
    }
}
