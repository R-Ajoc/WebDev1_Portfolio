<?php
require_once __DIR__ . '/../models/Department.php';

class DepartmentController
{
    private $departmentModel;

    // Accept $pdo and pass it to the model
    public function __construct($pdo)
    {
        $this->departmentModel = new Department($pdo);
    }

    // Get all departments
    public function getDepartments()
    {
        $departments = $this->departmentModel->getAllDepartments();
        echo json_encode($departments);
    }

    // ✅ Get bed count for a department
    // Get all departments with their bed counts
    public function getDepartmentBedCounts()
    {
        $data = $this->departmentModel->getAllDepartmentBedCounts();
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }


    // Get a department by ID
    public function getDepartment($department_id)
    {
        $department = $this->departmentModel->getDepartmentById($department_id);
        if ($department) {
            echo json_encode($department);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Department not found.']);
        }
    }
}
