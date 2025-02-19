<?php
// Include database connection
include('../../Data.php');

header('Content-Type: application/json'); // Ensure JSON response

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Initialize variables for form data and error handling
$place = $bus_no = $transport_fees = "";
$errors = [];

// Validate CSRF token (if implemented)
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $acd_year = trim($_POST['acd_year'] ?? '');
    $usr_role = trim($_POST['usr_role'] ?? '');
    $leave_typ = trim(htmlspecialchars($_POST['leave_typ'] ?? ''));
    $leave_notes = trim(htmlspecialchars($_POST['leave_notes'] ?? ''));
    $tot_credits = trim($_POST['tot_credits'] ?? '');

    // Validate form inputs
    if (empty($acd_year)) {
        $errors[] = "Academic Year is required.";
    }
    if (empty($usr_role)) {
        $errors[] = "User role is required.";
    }
    if (!is_numeric($tot_credits) || $tot_credits < 0) {
        $errors[] = "Valid credits must be a valid positive number.";
    }
    if (empty($leave_typ)) {
        $errors[] = "Leave type is required.";
    }
    if (empty($leave_notes)) {
        $errors[] = "Leave notes is required.";
    }

    // If there are no errors, proceed to add or update
    if (empty($errors)) {
        try {
            if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
                // Update existing record
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("UPDATE leave_management SET academic_year = :acd_year, user_role = :usr_role, leave_type = :leave_typ,total_credits=:tot_credits, notes=:leave_notes WHERE id = :id");
                $stmt->execute([':acd_year' => $acd_year, ':usr_role' => $usr_role, ':leave_typ' => $leave_typ, ':tot_credits'=>$tot_credits,':leave_notes'=>$leave_notes, ':id' => $id]);
                echo json_encode(['success' => true, 'message' => 'Leave type updated successfully!', 'new_id' => $id]);
            } else {
                // Add new record
                $stmt = $conn->prepare("INSERT INTO leave_management (academic_year, user_role, leave_type,total_credits,notes) VALUES (:acd_year, :usr_role, :leave_typ, :tot_credits, :leave_notes)");
                $stmt->execute([':acd_year' => $acd_year, ':usr_role' => $usr_role, ':leave_typ' => $leave_typ, ':tot_credits'=>$tot_credits,':leave_notes'=>$leave_notes]);
                $newId = $conn->lastInsertId(); // Get the new ID
                echo json_encode(['success' => true, 'message' => 'Leave type added successfully!', 'new_id' => $newId]);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
     else {
        // Return validation errors
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
}
else if($_SERVER["REQUEST_METHOD"] == "DELETE"){
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = intval($_GET['id'] ?? $_DELETE['id'] ?? 0);
    if (empty($errors)) {
        try {
                $stmt = $conn->prepare("DELETE FROM leave_management WHERE id = :id");
                $stmt->execute([':id' => $id]);
                echo json_encode(['success' => true, 'message' => 'Leave type deleted successfully!', 'new_id' => $id]);
            
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
     else {
        // Return validation errors
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
}
?>
