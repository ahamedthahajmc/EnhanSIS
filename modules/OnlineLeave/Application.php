<?php 

include('../../Data.php');
header('Content-Type: application/json'); 

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $first_name = trim($_POST["first_name"]);

    $last_name = trim($_POST["last_name"]);

    $middle_name = trim($_POST["middle_name"]);

    $student_id = trim($_POST["student_id"]);

    $grade = trim($_POST["grade"]);

    $section = trim($_POST["section"]);

    $leave_type = trim($_POST["leave_type"]);

    $start_date = trim($_POST["start_date"]);

    $end_date = trim($_POST["end_date"]);

    $reason = trim($_POST["reason"]);

    if (!empty($errors)) {
        echo json_encode(["status" => "error", "message" => implode(", ", $errors)]);
        exit;
    }

    try {        
        $stmt = $conn->prepare("INSERT INTO leave_application 
            (student_id, first_name, last_name, middle_name, grade, section, leave_type, start_date, end_date, reason, status) 
            VALUES 
                (:student_id, :first_name, :last_name, :middle_name, :grade, :section, :leave_type, :start_date, :end_date, :reason, :status)");

        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
        $stmt->bindParam(':grade', $grade, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':leave_type', $leave_type, PDO::PARAM_STR);
        $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
        $status = 'pending';
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Your form has been submitted successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error inserting data: " . $e->getMessage()]);
    }
}
?>
