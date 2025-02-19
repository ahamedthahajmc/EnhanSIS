<?php
include('../../Data.php');

// Database connection
try {
    $pdo = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If searching by Payment ID
    if (isset($_GET['payment_id'])) {
        $payment_id = $_GET['payment_id'];

        $sql = "SELECT f.fees_id, f.student_id, f.book_fees_amount, f.tuition_fees_amount, 
                       f.transport_fees_amount, f.total_fees, p.payment_date, 
                       SUM(p.amount_paid) AS amount_paid,
                       s.first_name, s.last_name
                FROM fee_payment p
                INNER JOIN fees_details f ON p.fees_id = f.fees_id
                INNER JOIN students s ON f.student_id = s.student_id
                WHERE p.payment_id = :payment_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->execute();
        $feesData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch total paid fees
        $totalPaidStmt = $pdo->prepare("
            SELECT SUM(book_fees_paid) AS total_book_fees_paid,
                   SUM(tuition_fees_paid) AS total_tuition_fees_paid,
                   SUM(transport_fees_paid) AS total_transport_fees_paid
            FROM fee_payment
            WHERE payment_id = :payment_id
        ");
        $totalPaidStmt->bindParam(':payment_id', $payment_id);
        $totalPaidStmt->execute();
        $totalPaidData = $totalPaidStmt->fetch(PDO::FETCH_ASSOC);

        // Calculate remaining balances
        $book_fees_remaining = $feesData['book_fees_amount'] - ($totalPaidData['total_book_fees_paid'] ?? 0);
        $tuition_fees_remaining = $feesData['tuition_fees_amount'] - ($totalPaidData['total_tuition_fees_paid'] ?? 0);
        $transport_fees_remaining = $feesData['transport_fees_amount'] - ($totalPaidData['total_transport_fees_paid'] ?? 0);
        $total_fees_remaining = $book_fees_remaining + $tuition_fees_remaining + $transport_fees_remaining;

        echo json_encode([
            'success' => true,
            'student_name' => trim($feesData['first_name'] . ' ' . $feesData['last_name']),
            'student_id' => $feesData['student_id'],
            'book_fees' => $book_fees_remaining, 
            'tuition_fees' => $tuition_fees_remaining, 
            'transport_fees' => $transport_fees_remaining,
            'total_fees' => $total_fees_remaining,
            'amount_paid' => $feesData['amount_paid'] ?? 0,
            'payment_date' => $feesData['payment_date']
        ]);
        exit;
    }

    // If searching by student ID or Name
    if (isset($_GET['student_id']) || isset($_GET['first_name']) || isset($_GET['last_name'])) {
        $sql = "SELECT f.fees_id, f.student_id, f.book_fees_amount, f.tuition_fees_amount, 
                       f.transport_fees_amount, f.total_fees, 
                       s.first_name, s.last_name
                FROM fees_details f
                INNER JOIN students s ON f.student_id = s.student_id";

        $conditions = [];
        $params = [];

        if (!empty($_GET['student_id'])) {
            $conditions[] = "TRIM(LOWER(f.student_id)) = TRIM(LOWER(:student_id))";
            $params[':student_id'] = $_GET['student_id'];
        }

        if (!empty($_GET['first_name'])) {
            $conditions[] = "LOWER(s.first_name) = LOWER(:first_name)";
            $params[':first_name'] = $_GET['first_name'];
        }

        if (!empty($_GET['last_name'])) {
            $conditions[] = "LOWER(s.last_name) = LOWER(:last_name)";
            $params[':last_name'] = $_GET['last_name'];
        }

        if (empty($conditions)) {
            echo json_encode(['success' => false, 'message' => 'Enter student ID or name']);
            exit;
        }

        $sql .= " WHERE " . implode(' OR ', $conditions);
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        $feesData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$feesData) {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
            exit;
        }

        // Fetch latest payment details
        $latestPaymentStmt = $pdo->prepare("
            SELECT payment_type, payment_method, payment_date, amount_paid
            FROM fee_payment
            WHERE fees_id = :fees_id
            ORDER BY payment_id DESC
            LIMIT 1
        ");
        $latestPaymentStmt->bindParam(':fees_id', $feesData['fees_id']);
        $latestPaymentStmt->execute();
        $latestPaymentData = $latestPaymentStmt->fetch(PDO::FETCH_ASSOC);

        if (!$latestPaymentData || $latestPaymentData['amount_paid'] == 0) {
            echo json_encode(['success' => false, 'message' => 'No payment found']);
            exit;
        }
        

        // Fetch total paid amounts
        $totalPaidStmt = $pdo->prepare("
            SELECT SUM(book_fees_paid) AS total_book_fees_paid,
                   SUM(tuition_fees_paid) AS total_tuition_fees_paid,
                   SUM(transport_fees_paid) AS total_transport_fees_paid
            FROM fee_payment
            WHERE fees_id = :fees_id
        ");
        $totalPaidStmt->bindParam(':fees_id', $feesData['fees_id']);
        $totalPaidStmt->execute();
        $totalPaidData = $totalPaidStmt->fetch(PDO::FETCH_ASSOC);

        // If no payment exists, show alert instead of receipt
       
        // Calculate remaining balances
        $book_fees_remaining = $feesData['book_fees_amount'] - ($totalPaidData['total_book_fees_paid'] ?? 0);
        $tuition_fees_remaining = $feesData['tuition_fees_amount'] - ($totalPaidData['total_tuition_fees_paid'] ?? 0);
        $transport_fees_remaining = $feesData['transport_fees_amount'] - ($totalPaidData['total_transport_fees_paid'] ?? 0);
        $total_fees_remaining = $book_fees_remaining + $tuition_fees_remaining + $transport_fees_remaining;

        echo json_encode([
            'success' => true,
            'student_name' => trim($feesData['first_name'] . ' ' . $feesData['last_name']),
            'student_id' => $feesData['student_id'],
            'book_fees' => $book_fees_remaining, 
            'tuition_fees' => $tuition_fees_remaining, 
            'transport_fees' => $transport_fees_remaining,
            'total_fees' => $total_fees_remaining,
            'payment_type' => strtoupper($latestPaymentData['payment_type']) ?? 'N/A',
            'payment_method' => strtoupper($latestPaymentData['payment_method']) ?? 'N/A',
            'payment_date' => $latestPaymentData['payment_date'] ?? 'N/A',
            'amount_paid' => $latestPaymentData['amount_paid'] ?? 0
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No student identifier provided']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
