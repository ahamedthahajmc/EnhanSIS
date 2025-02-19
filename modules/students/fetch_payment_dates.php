<?php
include('DB.php');
try {
    $student_id = trim($_GET['student_id'] ?? '');
    $first_name = trim($_GET['first_name'] ?? '');
    $last_name = trim($_GET['last_name'] ?? '');
 
    $pdo = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Base Query with JOIN
    $query = "SELECT fp.payment_date, fp.payment_id, fp.student_name
              FROM fee_payment fp 
              LEFT JOIN students s ON fp.student_id = s.student_id";


    $params = [];
    $conditions = [];

    if (!empty($student_id)) {
        $conditions[] = "fp.student_id = :student_id";
        $params[':student_id'] = $student_id;
    }

    // if (!empty($first_name)) {
    //     $conditions[] = "s.first_name = :first_name";
    //     $params[':first_name'] = $first_name;
    // }

    // if (!empty($last_name)) {
    //     $conditions[] = "s.last_name = :last_name";
    //     $params[':last_name'] = $last_name;
    // }

    // Apply WHERE conditions dynamically
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Corrected ORDER BY
//     $query .= " ORDER BY fp.payment_id ASC";
//   print_r($query);
//   die;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'dates' => $dates
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
