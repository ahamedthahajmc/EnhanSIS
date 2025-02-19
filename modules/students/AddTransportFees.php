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
    $place = trim(htmlspecialchars($_POST['place'] ?? ''));
    $bus_no = trim(htmlspecialchars($_POST['bus_no'] ?? ''));
    $transport_fees = trim($_POST['transport_fees'] ?? '');

    // Validate form inputs
    if (empty($place)) {
        $errors[] = "Place is required.";
    }
    if (empty($bus_no)) {
        $errors[] = "Bus No is required.";
    }
    if (!is_numeric($transport_fees) || $transport_fees < 0) {
        $errors[] = "Transport Fees must be a valid positive number.";
    }

    // If there are no errors, proceed to add or update
    if (empty($errors)) {
        try {
            if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
                // Update existing record
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("UPDATE bus_transport_fees SET place = :place, bus_no = :bus_no, transport_fees = :transport_fees WHERE id = :id");
                $stmt->execute([':place' => $place, ':bus_no' => $bus_no, ':transport_fees' => $transport_fees, ':id' => $id]);
                echo json_encode(['success' => true, 'message' => 'Transport fee updated successfully!', 'new_id' => $id]);
            } else {
                // Add new record
                $stmt = $conn->prepare("INSERT INTO bus_transport_fees (place, bus_no, transport_fees) VALUES (:place, :bus_no, :transport_fees)");
                $stmt->execute([':place' => $place, ':bus_no' => $bus_no, ':transport_fees' => $transport_fees]);
                $newId = $conn->lastInsertId(); // Get the new ID
                echo json_encode(['success' => true, 'message' => 'Transport fee added successfully!', 'new_id' => $newId]);
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
?>
