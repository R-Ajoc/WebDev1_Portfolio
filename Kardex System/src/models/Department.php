<?php
class Department
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllDepartments()
    {
        $stmt = $this->pdo->query("SELECT * FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDepartmentBedCounts()
    {
        $stmt = $this->pdo->query("
            SELECT 
                d.deptname, 
                COUNT(b.bed_id) AS bed_count 
            FROM 
                departments d 
            LEFT JOIN 
                beds b ON b.department_id = d.department_id 
            GROUP BY 
                d.department_id, d.deptname
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartmentById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM departments WHERE department_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
