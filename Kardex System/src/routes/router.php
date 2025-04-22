<?php

class Router
{
    // Dispatch the request based on URI and HTTP method
    public function dispatch($uri, $method)
    {

        // Load the database connection
        require_once __DIR__ . '/../../config/database.php';

        $routes = $this->getRoutes();

        if (!isset($routes[$method])) {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }

        foreach ($routes[$method] as $route => $handler) {
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                list($controllerName, $action) = explode('@', $handler);

                require_once __DIR__ . '/../controllers/' . $controllerName . '.php';


                $controller = new $controllerName($pdo);

                if (method_exists($controller, $action)) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($method === 'POST') {
                        $controller->$action($data, ...$matches);
                    } else {
                        $controller->$action(...$matches);
                    }
                    return;
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Method not found']);
                    return;
                }
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

    // All routes are declared here
    private function getRoutes()
    {
        return [
            'POST' => [
                '/register/step1' => 'RegisterController@validateInitial',
                '/register/step2' => 'RegisterController@register',
                '/updatePasscode' => 'RegisterController@updatePasscode',
                '/deleteUser' => 'RegisterController@deleteUserById',
                '/login' => 'LoginController@login',
                '/verify' => 'PasscodeController@verify',
                '/addBed' => 'BedController@addBed',
                '/deleteBed' => 'BedController@deleteBedById',
                '/assignBed' => 'BedController@assignBedToPatient',
                '/removeBedAssignment' => 'BedController@removeBedAssignment',
                '/addPatient' => 'PatientController@create',
                '/updatePatient/{id}' => 'PatientController@update',
                '/deletePatient' => 'PatientController@deletePatientById',
                '/updatePatient/{patient_id}' => 'PatientController@update',
                '/addNote' => 'EndorsementController@create',
                '/addView/{user_id}/{patient_id}' => 'EndorsementViewController@addView',
                '/addAttachment/{patient_id}' => 'AttachmentController@add',
                '/addReferral/{patient_id}' => 'ReferralController@add',
                '/addTreatment/{patient_id}' => 'TreatmentController@add',
                '/addIVF/{patient_id}' => 'IVFInfusionController@add',
                '/deleteIVF/{patient_id}' => 'IVFInfusionController@delete',
                '/addProcedure/{patient_id}' => 'ProcedureController@add',
                '/addLaboratory/{patient_id}' => 'LaboratoryController@add',
                '/deleteLaboratory/{patient_id}' => 'LaboratoryController@delete',
            ],
            'GET' => [
                '/departments' => 'DepartmentController@getDepartments',
                '/departments/{id}' => 'DepartmentController@getDepartment',
                '/departmentsBedCount' => 'DepartmentController@getDepartmentBedCounts',
                '/users' => 'RegisterController@getAllUsers',
                '/allBeds' => 'BedController@getAllBeds',
                '/bed/{bed_number}' => 'BedController@getBedByNumber',
                '/viewPatient/{id}' => 'PatientController@getPatientById',
                '/loadPatientFolders' => 'PatientController@getPatientsByDepartment',
                '/allPatients' => 'PatientController@getAllPatients',
                '/latestViewers/{patient_id}' => 'EndorsementViewController@getLatestViewers',
                '/attachments/{patient_id}' => 'AttachmentController@getByPatient',
                '/referrals/{patient_id}' => 'ReferralController@getByPatient',
                '/treatments/{patient_id}' => 'TreatmentController@getByPatient',
                '/infusions/display_table1/{patient_id}' => 'IVFInfusionController@getByPatientTable1',
                '/infusions/display_table2/{patient_id}' => 'IVFInfusionController@getByPatientTable2',
                '/procedures/{patient_id}' => 'ProcedureController@getByPatient',
                '/laboratory/{patient_id}' => 'LaboratoryController@getByPatient',
                '/getNotes/{user_id}/{patient_id}' => 'EndorsementController@getNotes',
            ],
        ];
    }
}
