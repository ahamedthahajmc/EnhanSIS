<?php
include('../../Data.php');
header('Content-Type: application/json');
session_start();

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // $userProfile = User('PROFILE');
    // $userName = User('USERNAME');

    // if ($userProfile === 'student') {
    //     $student_id = UserStudentID();
    // }
    
    // function UserStudentID() {
    //     return isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;
    // }

    if (isset($_GET['student_id'])) {
        $student_id = trim($_GET['student_id']);
    }

    if (isset($student_id)) {
        $stmt = $conn->prepare("SELECT s.first_name, s.last_name, s.middle_name, se.grade_id, se.section_id
                                FROM students s
                                JOIN student_enrollment se ON s.student_id = se.student_id
                                WHERE s.student_id = :student_id");

        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            echo json_encode([
                'status' => 'success',
                'first_name' => $student['first_name'],
                'last_name' => $student['last_name'],
                'middle_name' => $student['middle_name'], 
                'grade' => $student['grade_id'],
                'section' => $student['section_id']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Student ID not provided']);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]);
}
?>
