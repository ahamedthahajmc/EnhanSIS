<?php
include('../../Data.php');
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $student_id = $_GET['id'];
    try {
        $sql = "SELECT * FROM fees_details WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$student_id]);
        $fee = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($fee);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();

        $student_id = $_POST['student_id'];
        $book_fees = floatval($_POST['book_fees_amount'] ?? 0);
        $tuition_fees = floatval($_POST['tuition_fees_amount'] ?? 0);
        $transport_fees = floatval($_POST['transport_fees_amount'] ?? 0);
        $discounted_book_fees = floatval($_POST['discounted_book_fees'] ?? 0);
        $discounted_tuition_fees = floatval($_POST['discounted_tuition_fees'] ?? 0);
        $discounted_transport_fees = floatval($_POST['discounted_transport_fees'] ?? 0);
        $final_book_fees = floatval($_POST['final_book_fees'] ?? 0);
        $final_tuition_fees = floatval($_POST['final_tuition_fees'] ?? 0);
        $final_transport_fees = floatval($_POST['final_transport_fees'] ?? 0);

        $total_fees = $book_fees + $tuition_fees + $transport_fees;
        $total_discount_fees = $discounted_book_fees + $discounted_tuition_fees + $discounted_transport_fees;
        $final_total_fees = $total_fees - $total_discount_fees;
        $discount_percentage = $total_fees > 0 ? ($total_discount_fees / $total_fees) * 100 : 0;

        // Update fees details
        $sql = "UPDATE fees_details SET book_fees_amount = ?, tuition_fees_amount = ?, transport_fees_amount = ?, total_fees = ? WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$final_book_fees, $final_tuition_fees, $final_transport_fees, $final_total_fees, $student_id]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("No rows updated in `fees_details` for student_id: $student_id");
        }

        // Retrieve fees_id
        $sql3 = "SELECT fees_id FROM fees_details WHERE student_id = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->execute([$student_id]);
        $fees_id = $stmt3->fetchColumn();

        if (!$fees_id) {
            throw new Exception("Fees ID not found for student ID: $student_id");
        }

        // Get the current max delete_flag for this fees_id or student_id
        $sql4 = "SELECT MAX(delete_flag) FROM discount_fees WHERE fees_id = ? OR student_id = ?";
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute([$fees_id, $student_id]);
        $current_max_flag = $stmt4->fetchColumn();
        $next_delete_flag = ($current_max_flag !== null) ? $current_max_flag + 1 : 0;

        // Insert a new record with the incremented delete_flag
        $sql2 = "INSERT INTO discount_fees (fees_id, student_id, discounted_book_fees, discounted_tuition_fees, discounted_transport_fees, total_discounted_fees, discount_percentage, delete_flag) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$fees_id, $student_id, $discounted_book_fees, $discounted_tuition_fees, $discounted_transport_fees, $total_discount_fees, $discount_percentage, $next_delete_flag]);

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Fees updated and discounts applied successfully.']);
    } catch (Exception $e) {
        $conn->rollBack();
        error_log($e->getMessage(), 3, 'EditFees.log');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
