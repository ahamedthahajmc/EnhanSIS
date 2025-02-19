<?php

include('../../Data.php');
$pdo = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    if (!empty($_POST['student_id']) || !empty($_POST['first_name']) || !empty($_POST['last_name'])) {
        $conditions = [];
        $params = [];

        if (!empty($_POST['student_id'])) {
            $conditions[] = "TRIM(LOWER(f.student_id)) = TRIM(LOWER(:student_id))";
            $params[':student_id'] = $_POST['student_id'];
        }
        if (!empty($_POST['first_name'])) {
            $conditions[] = "LOWER(s.first_name)=LOWER(:first_name)";
            $params[':first_name'] = $_POST['first_name'];
        }
        if (!empty($_POST['last_name'])) {
            $conditions[] = "LOWER(s.last_name)= LOWER(:last_name)";
            $params[':last_name'] =  $_POST['last_name'] ;
        }

        $sql = "
            SELECT f.fees_id, f.student_id, f.book_fees_amount, f.tuition_fees_amount, 
                   f.transport_fees_amount, s.first_name, s.last_name
            FROM fees_details f
            INNER JOIN students s ON f.student_id = s.student_id
        ";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $studentStmt = $pdo->prepare($sql);
        foreach ($params as $key => &$val) {
            $studentStmt->bindParam($key, $val);
        }
        $studentStmt->execute();
        $feesData = $studentStmt->fetch(PDO::FETCH_ASSOC);

        if ($feesData) {
            $paidQuery = "
                SELECT 
                    SUM(book_fees_paid) AS total_book_fees,
                    SUM(tuition_fees_paid) AS total_tuition_fees,
                    SUM(transport_fees_paid) AS total_transport_fees
                FROM fee_payment 
                WHERE student_id = :student_id
            ";
            $paidStmt = $pdo->prepare($paidQuery);
            $paidStmt->execute(['student_id' => $feesData['student_id']]);
            $feesPaid = $paidStmt->fetch(PDO::FETCH_ASSOC);

            $response = [
                'success' => true,
                'student_name' => $feesData['first_name'] . ' ' . $feesData['last_name'],
                'student_id' => $feesData['student_id'],
                'book_fees' => $feesData['book_fees_amount'] - ($feesPaid['total_book_fees'] ?? 0),
                'tuition_fees' => $feesData['tuition_fees_amount'] - ($feesPaid['total_tuition_fees'] ?? 0),
                'transport_fees' => $feesData['transport_fees_amount'] - ($feesPaid['total_transport_fees'] ?? 0)
            ];

            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No student identifier provided']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
