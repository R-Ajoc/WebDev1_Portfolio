<?php

class Bed
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Function to fetch all beds (useful for the bed list)
    public function getAllBeds()
    {
        $stmt = $this->pdo->query(
            "SELECT 
                b.bed_id,
                CAST(b.bed_number AS UNSIGNED) AS bed_number,
                d.deptname,
            CASE 
                WHEN b.patient_uuid IS NULL THEN 'Available'
                ELSE 'Occupied'
            END AS status
            FROM beds b 
            INNER JOIN departments d ON b.department_id = d.department_id
            ORDER BY 2;
        "
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a bed by its bed number (used for showing patient bed assignments)
    public function getBedByNumber($bed_number)
    {
        $stmt = $this->pdo->prepare(
            "SELECT 
                b.bed_id, 
                b.bed_number, 
                HEX(b.bed_uuid) AS bed_uuid, 
                d.deptname
            FROM beds b 
            INNER JOIN departments d ON b.department_id = d.department_id
            WHERE bed_number = ?
        "
        );
        $stmt->execute([$bed_number]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getAllBedsWithAssignedDepartment()
    {
        $stmt = $this->pdo->query(
            "SELECT 
                b.bed_uuid,
                b.bed_id,
                b.bed_number,
                d.deptname
            FROM departments d
            JOIN beds b ON d.department_id = b.department_id
            LEFT JOIN patients p ON p.bed_uuid = b.bed_uuid
            WHERE p.bed_uuid IS NULL;
	    "
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Assign a patient to a bed (not necessary if this is handled in the patient record)
    public function assignBedToPatient($patient_uuid, $bed_number)
    {
        // First update the patient record
        $stmt1 = $this->pdo->prepare("
        UPDATE patients 
        SET bed_uuid = (SELECT bed_uuid FROM beds WHERE bed_number = ?)
        WHERE patient_uuid = ?
    ");
        $success1 = $stmt1->execute([$bed_number, $patient_uuid]);

        // Then update the bed record
        $stmt2 = $this->pdo->prepare("
        UPDATE beds 
        SET patient_uuid = ?
        WHERE bed_number = ?
    ");
        $success2 = $stmt2->execute([$patient_uuid, $bed_number]);

        return $success1 && $success2;
    }


    // Remove a bed (optional)
    public function deleteBedById($bed_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM beds WHERE bed_id = ?");
        $success = $stmt->execute([$bed_id]);

        if ($success) {
            return ['success' => true];
        } else {
            return ['error' => 'Failed to delete bed.'];
        }
    }

    function generateUuidV4()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // Version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // Variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Add Bed
    public function addBed($bed_number, $department_id)
    {
        $uuid = $this->generateUuidV4(); //generate random uuid
        $binaryUuid = pack("H*", str_replace('-', '', $uuid)); // convert to 16-byte binary

        $stmt = $this->pdo->prepare("INSERT INTO beds (bed_number, department_id, bed_uuid) VALUES (?, ?, ?)");
        return $stmt->execute([$bed_number, $department_id, $binaryUuid]);
    }
}
