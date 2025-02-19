<?php
include('../../Data.php');
session_start();

if (!isset($_SESSION['UserInstitute'])) {
    echo json_encode(['success' => false, 'message' => 'Institute is not set in session']);
    exit;
}

$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$grade_id = $_POST['grade_id'] ?? null;
$section_id = $_POST['section_id'] ?? null;
$year = $_SESSION['UserSyear'];
$institute_id = $_SESSION['UserInstitute'];

// Ensure values are not null
if (!$grade_id || !$section_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid grade or section ID']);
    exit;
}

// Debugging: Check actual values
error_log("Executing query: SELECT student_id FROM student_enrollment WHERE grade_id = $grade_id AND section_id = $section_id AND syear = $year AND institute_id = $institute_id");

try {
    // Using the correct column names
    $stmt = $conn->prepare("
       SELECT student_id FROM student_enrollment  
            WHERE grade_id = :grade_id 
            AND section_id = :section_id 
            AND syear = :year 
            AND institute_id = :institute_id
    ");
    
    $stmt->execute([
        ':grade_id' => (int) $grade_id,
        ':section_id' => (string) $section_id,
        ':year' => (int) $year,
        ':institute_id' => (int) $institute_id
    ]);

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($students) {
        echo '<option value="">All Student</option>';
        foreach ($students as $student) {
            echo '<option value="' . htmlspecialchars($student['student_id']) . '">' . htmlspecialchars($student['student_id']) . '</option>';
        }
    } else {
        echo '<option value="">No Students Found</option>';
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
}
?>
