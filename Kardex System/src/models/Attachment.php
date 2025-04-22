<?php

class Attachment
{

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Adds a new file attachment for a specific patient
    public function add($patientId, $data)
    {
        $sql = "INSERT INTO attachments (
                patient_id, o2_checkbox, o2_value, o2_lpm_value, ngt_checkbox, ngt_value, ogt_checkbox, ogt_value,
                bur_checkbox, bur_value, cardiac_monitor_checkbox, ctt_checkbox, ctt_value,
                ett_size_checkbox, ett_size_value, ett_level_value, fbc_checkbox, fbc_value,
                fio2_value, mechanical_ventilator_checkbox, mode_checkbox, mode_value,
                peep_value, pfr_value, ppv_checkbox, ppv_value, ppv_lmin_value, 
                pulse_oximeter_checkbox, tracheostomy_attached_checkbox, tv_checkbox, tv_value, 
                attachmentOthers_value
            ) VALUES (
                :patient_id, :o2_checkbox, :o2_value, :o2_lpm_value, :ngt_checkbox, :ngt_value, :ogt_checkbox, :ogt_value,
                :bur_checkbox, :bur_value, :cardiac_monitor_checkbox, :ctt_checkbox, :ctt_value,
                :ett_size_checkbox, :ett_size_value, :ett_level_value, :fbc_checkbox, :fbc_value,
                :fio2_value, :mechanical_ventilator_checkbox, :mode_checkbox, :mode_value,
                :peep_value, :pfr_value, :ppv_checkbox, :ppv_value, :ppv_lmin_value, 
                :pulse_oximeter_checkbox, :tracheostomy_attached_checkbox, :tv_checkbox, :tv_value, 
                :attachmentOthers_value
            )";

        $stmt = $this->pdo->prepare($sql);

        // Safely map each expected key, defaulting to null if it's missing
        $fields = [
            'o2_checkbox',
            'o2_value',
            'o2_lpm_value',
            'ngt_checkbox',
            'ngt_value',
            'ogt_checkbox',
            'ogt_value',
            'bur_checkbox',
            'bur_value',
            'cardiac_monitor_checkbox',
            'ctt_checkbox',
            'ctt_value',
            'ett_size_checkbox',
            'ett_size_value',
            'ett_level_value',
            'fbc_checkbox',
            'fbc_value',
            'fio2_value',
            'mechanical_ventilator_checkbox',
            'mode_checkbox',
            'mode_value',
            'peep_value',
            'pfr_value',
            'ppv_checkbox',
            'ppv_value',
            'ppv_lmin_value',
            'pulse_oximeter_checkbox',
            'tracheostomy_attached_checkbox',
            'tv_checkbox',
            'tv_value',
            'attachmentOthers_value'
        ];

        // Build the bind array
        $params = [':patient_id' => $patientId];
        foreach ($fields as $field) {
            $params[":$field"] = $data[$field] ?? null;
        }

        return $stmt->execute($params);
    }

    // Retrieves all attachments for a given patient
    public function getAttachmentsByPatient($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attachments WHERE patient_id = ?");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll();
    }

    // Deletes all attachments for a given patient ID
    public function deleteAttachmentsByPatient($patient_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM attachments WHERE patient_id = ?");
        return $stmt->execute([$patient_id]);
    }
}
