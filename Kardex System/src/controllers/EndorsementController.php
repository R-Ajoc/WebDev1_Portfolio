<?php
require_once __DIR__ . '/../models/Endorsement.php';

class EndorsementController
{
    private $endorsementModel;

    public function __construct($pdo)
    {
        $this->endorsementModel = new Endorsement($pdo);
    }

    //addNote
    public function create($data)
    {
        if (empty($data['user_id']) || empty($data['patient_id']) || empty($data['note'])) {
            http_response_code(400);
            echo json_encode(['error' => 'All fields are required.']);
            return;
        }

        $success = $this->endorsementModel->createNote($data['user_id'], $data['patient_id'], $data['note']);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Note saved successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save note.']);
        }
    }


    public function getNotes($data, $user_id, $patient_id)
    {
        $notes = $this->endorsementModel->getNotesByUserAndPatient($user_id, $patient_id);
        echo json_encode($notes);
    }
}
