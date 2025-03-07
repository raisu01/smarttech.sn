<?php
require_once '../models/EmployeeModels.php';

class EmployeeController {
    private $employeeModel;

    public function __construct() {
        $this->employeeModel = new EmployeeModels();
    }

    public function getEmployees() {
        return $this->employeeModel->getAllEmployees();
    }

    public function createEmployee() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"] ?? null;
            $email = $_POST["email"] ?? null;
            $phone = $_POST["phone"] ?? null;
            $position = $_POST["position"] ?? null;

            if ($name && $email && $phone && $position) {
                if ($this->employeeModel->createEmployee($name, $email, $phone, $position)) {
                    header("Location: employees.php?success=1");
                    exit;
                } else {
                    header("Location: employees.php?error=1");
                    exit;
                }
            } else {
                header("Location: employees.php?error=2");
                exit;
            }
        }
    }

    public function deleteEmployee() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["employee_id"])) {
            $id = $_POST["employee_id"];
            if ($this->employeeModel->deleteEmployee($id)) {
                header("Location: employees.php?deleted=1");
                exit;
            } else {
                header("Location: employees.php?error=3");
                exit;
            }
        }
    }
}

// Gestion des actions du contrôleur
$employeeController = new EmployeeController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_employee"])) {
    $employeeController->createEmployee();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_employee"])) {
    $employeeController->deleteEmployee();
}
