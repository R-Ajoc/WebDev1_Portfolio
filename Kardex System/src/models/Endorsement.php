<?php
require_once __DIR__ . '/../config/database.php';

class Endorsement
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createNote($user_id, $patient_id, $note)
    {
        $stmt = $this->pdo->prepare("INSERT INTO endorsements (user_id, patient_id, note, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $patient_id, $note]);
    }

    public function getNotesByUserAndPatient($user_id, $patient_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM endorsements WHERE user_id = ? AND patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id, $patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
