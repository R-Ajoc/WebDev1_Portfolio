<?php
require_once __DIR__ . '/../config/database.php';

class EndorsementView
{

    // Insert a new view by a user for a specific patient
    public function addView($user_id, $patient_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO endorsementview (user_id, patient_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $patient_id]);
    }

    // Get the latest 4 users who viewed the notes for a specific patient
    public function getLatestViewers($patient_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT u.user_id, u.lastname, u.firstname, ev.viewed_at 
                               FROM endorsementview ev
                               JOIN users u ON ev.user_id = u.user_id
                               WHERE ev.patient_id = ?
                               ORDER BY ev.viewed_at DESC
                               LIMIT 4");
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll();
    }
}
