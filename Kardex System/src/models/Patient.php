<?php
// This will handle all database interactions related to patients.

class Patient
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all patients
    public function getAllPatients()
    {
        $stmt = $this->pdo->query("SELECT 
                                    p.patient_id AS id,
                                    CONCAT_WS(' ',
                                        CONCAT(UPPER(LEFT(p.firstname, 1)), LOWER(SUBSTRING(p.firstname, 2))),
                                        CASE 
                                            WHEN p.midinit IS NOT NULL AND p.midinit != '' THEN UPPER(p.midinit)
                                            ELSE NULL 
                                        END,
                                        CONCAT(UPPER(LEFT(p.lastname, 1)), LOWER(SUBSTRING(p.lastname, 2))) ) AS fullname,                                     
                                    p.gender,
                                    p.diagnosis,
                                    p.created_at,
                                    p.status,
                                    p.nationality,
                                    p.religion,
                                    p.physician,
                                    p.date_of_birth,
                                    b.bed_number,
                                    d.deptname as bed_deptname
                                FROM patients p
                                LEFT JOIN beds b ON p.bed_uuid = b.bed_uuid
                                LEFT JOIN departments d ON b.department_id = d.department_id;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch patient by ID
    public function getPatientById($patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT 
                                        p.lastname,
                                        p.firstname,
                                        p.midinit,
                                        p.gender,
                                        p.diagnosis,
                                        p.created_at,
                                        p.status,
                                        p.nationality,
                                        p.religion,
                                        p.physician,
                                        p.date_of_birth,
                                        b.bed_number,
                                        d.deptname
                                    FROM patients p
                                    LEFT JOIN beds b ON p.bed_uuid = b.bed_uuid
                                    LEFT JOIN departments d ON b.department_id = d.department_id
                                    WHERE p.patient_id = ?;
                                    ");
        $stmt->execute([$patient_id]);

        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        // return $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient) {
            error_log("✅ Patient found: " . print_r($patient, true)); // success
        } else {
            error_log("❌ No patient found for ID 12");
        }

        return $patient;
    }

    public function getPatientByUuid($patient_uuid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM patients WHERE patient_uuid = ?");
        $stmt->execute([$patient_uuid]);
        return $stmt->fetch();
    }

    // Fetch patient by bed ID
    public function getPatientByBedId($bed_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM patients WHERE bed_id = ?");
        $stmt->execute([$bed_id]);
        return $stmt->fetch();
    }

    public function getPatientsByDepartmentId()
    {
        $stmt = $this->pdo->prepare("SELECT 					
                p.patient_id AS id,
                CONCAT_WS(' ',
                    CONCAT(UPPER(LEFT(p.firstname, 1)), LOWER(SUBSTRING(p.firstname, 2))),
                    CASE 
                        WHEN p.midinit IS NOT NULL AND p.midinit != '' THEN UPPER(p.midinit)
                        ELSE NULL 
                    END,
                    CONCAT(UPPER(LEFT(p.lastname, 1)), LOWER(SUBSTRING(p.lastname, 2))) ) AS fullname,                                     
                           
                    b.bed_number
                FROM patients p
                JOIN beds b ON p.bed_uuid = b.bed_uuid
                JOIN departments d ON b.department_id = d.department_id
                WHERE d.department_id = ?;");
        $stmt->execute([$_SESSION['department_id']]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Create a new patient
    public function createPatient($lastname, $firstname, $midinit, $gender, $diagnosis, $status, $nationality, $religion, $physician, $date_of_birth, $patient_uuid = null)
    {
        $stmt = $this->pdo->prepare("INSERT INTO patients (lastname, firstname, midinit, gender, diagnosis, status, nationality, religion, physician, date_of_birth, patient_uuid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        return $stmt->execute([
            $lastname,
            $firstname,
            $midinit,
            $gender,
            $diagnosis,
            $status,
            $nationality,
            $religion,
            $physician,
            $date_of_birth,
            $patient_uuid
        ]);
    }

    // Delete a patient
    public function deletePatientById($patient_id)
    {
        error_log($patient_id . ' AHALSFAKJKLFJ');
        $stmt = $this->pdo->prepare("DELETE FROM patients WHERE patient_id = ?");
        $success = $stmt->execute([$patient_id]);

        if ($success) {
            return ['success' => true];
        } else {
            return ['error' => 'Failed to delete bed.'];
        }
    }

    // Update a patient
    public function updatePatient($patient_id, $lastname, $firstname, $midinit, $bed_id, $gender, $diagnosis, $status, $nationality, $religion, $physician, $date_of_birth)
    {
        $stmt = $this->pdo->prepare("UPDATE patients SET lastname = ?, firstname = ?, midinit = ?, bed_id = ?, gender = ?, diagnosis = ?, status = ?, nationality = ?, religion = ?, physician = ?, date_of_birth = ? WHERE patient_id = ?");

        return $stmt->execute([
            $lastname,
            $firstname,
            $midinit,
            $bed_id,
            $gender,
            $diagnosis,
            $status,
            $nationality,
            $religion,
            $physician,
            $date_of_birth,
            $patient_id
        ]);
    }
}
