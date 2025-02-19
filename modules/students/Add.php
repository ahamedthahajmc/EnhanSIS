<?php
include('../../Data.php');
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

$syear = $_SESSION['UserSyear'];
$singleId = $_POST['singleStuId'] ?? null;
parse_str($_POST['formData'] ?? null, $formDataArray);

$condition_var = $singleId == "" ? isset($_POST['student_id'], $_POST['book_fees'], $_POST['tuition_fees']) : isset($formDataArray['student_id'], $formDataArray['book_fees'], $formDataArray['tuition_fees']);

if ($condition_var) {
    $student_ids = $singleId == "" ? explode(',', $_POST['student_id']) : array($singleId);
    $book_fees = $singleId == "" ? $_POST['book_fees'] : $formDataArray['book_fees'];
    $tuition_fees = $singleId == "" ? $_POST['tuition_fees'] : $formDataArray['tuition_fees'];
    $responses = [];

    foreach ($student_ids as $student_id) {
        $student_id = trim($student_id);

        // Fetch student name
        $stmt = $conn->prepare("SELECT first_name FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            $responses[] = ['success' => false, 'message' => "No student found with ID: " . htmlspecialchars($student_id)];
            continue;
        }

        $student_name = $student['first_name'];

        // Fetch bus_no
        $busStmt = $conn->prepare("SELECT bus_no FROM student_address WHERE student_id = ?");
        $busStmt->execute([$student_id]);
        $bus = $busStmt->fetch(PDO::FETCH_ASSOC);
        $bus_no = $bus['bus_no'] ?? null;

        // Calculate transport fees
        $transport_fees = 0;
        if ($bus_no) {
            $transportStmt = $conn->prepare("SELECT transport_fees FROM bus_transport_fees WHERE bus_no = ?");
            $transportStmt->execute([$bus_no]);
            $transport = $transportStmt->fetch(PDO::FETCH_ASSOC);
            $transport_fees = $transport['transport_fees'] ?? 0;
        }

        $total_fees = $book_fees + $tuition_fees + $transport_fees;

        // Check existing fees
        $checkStmt = $conn->prepare("SELECT * FROM fees_details WHERE student_id = ?");
        $checkStmt->execute([$student_id]);
        $existingFees = $checkStmt->fetch(PDO::FETCH_ASSOC);

        $current_time = date('Y-m-d H:i:s');

        if ($existingFees) {
            // Update existing fees
            $new_book_fees = $existingFees['book_fees_amount'] + $book_fees;
            $new_tuition_fees = $existingFees['tuition_fees_amount'] + $tuition_fees;
            $new_transport_fees = $existingFees['transport_fees_amount'] + $transport_fees;
            $new_total_fees = $existingFees['total_fees'] + $total_fees;

            $updateSql = "UPDATE fees_details 
                          SET book_fees_amount = ?, tuition_fees_amount = ?, transport_fees_amount = ?, total_fees = ?, updated_at = ?
                          WHERE student_id = ?";
            $updateStmt = $conn->prepare($updateSql);

            if ($updateStmt->execute([$new_book_fees, $new_tuition_fees, $new_transport_fees, $new_total_fees, $current_time, $student_id])) {
                $responses[] = ['success' => true, 'message' => "Fees updated sucessfully!"];
            } else {
                $errorInfo = $updateStmt->errorInfo();
                $responses[] = ['success' => false, 'message' => "Error updating fees for student ID: " . $student_id . " - " . $errorInfo[2]];
            }
        } else {
            // Insert new fees
            $insertSql = "INSERT INTO fees_details (student_id, student_name, syear, book_fees_amount, tuition_fees_amount, transport_fees_amount, total_fees, updated_at) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);

            if ($insertStmt->execute([$student_id, $student_name, $syear, $book_fees, $tuition_fees, $transport_fees, $total_fees, $current_time])) {
                $responses[] = ['success' => true, 'message' => "Fees added sucessfully"];
            } else {
                $errorInfo = $insertStmt->errorInfo();
                $responses[] = ['success' => false, 'message' => "Error adding fees for student ID: " . $student_id . " - " . $errorInfo[2]];
            }
        }
    }

    echo json_encode($responses);
} else {
    echo json_encode(['success' => false, 'message' => "Invalid input. Please provide student_id, book_fees, and tuition_fees."]);
}
