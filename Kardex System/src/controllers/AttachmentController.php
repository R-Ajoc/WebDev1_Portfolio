<?php
require_once __DIR__ . '/../models/Attachment.php';

class AttachmentController
{
    private $attachmentModel;

    public function __construct($pdo)
    {
        $this->attachmentModel = new Attachment($pdo);
    }

    // Adds a new attachment for a patient
    public function add($data)
    {
        // Basic validation: check for patient ID
        if (empty($data['patient_id'])) {
            http_response_code(409);
            echo json_encode(['errors' => ['patientIdMissing' => 'Patient ID is required.']]);
            return;
        }

        $this->delete($data['patient_id']);

        $flattenedData = array_merge(
            ['patient_id' => $data['patient_id']],
            $data['attachments']
        );

        // Attempt to save the attachments
        $success = $this->attachmentModel->add($data['patient_id'], $flattenedData);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Attachments saved successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save attachments.']);
        }
    }


    // Returns all attachments of a patient
    public function getByPatient($patient_id)
    {
        $attachments = $this->attachmentModel->getAttachmentsByPatient($patient_id);
        echo json_encode($attachments);
    }

    public function delete($patient_id)
    {
        if (empty($patient_id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing patient ID.']);
            return;
        }

        $success = $this->attachmentModel->deleteAttachmentsByPatient($patient_id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Attachments deleted successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete attachments.']);
        }
    }
}
