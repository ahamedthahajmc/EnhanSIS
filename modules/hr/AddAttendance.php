<?php
include('../../Data.php');

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    $now = date("Y-m-d");
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['present_val'] != "") {
        $staffId = $_POST['staff_id'];
        $presentVal = $_POST['present_val'];

        $occured_present = "SELECT id FROM staff_attendance WHERE staff_id = :staff_id AND date = :now";
        $stmt = $conn->prepare($occured_present);
        $stmt->execute([':staff_id' => $staffId, ':now' => $now]); // Use parameterized query to prevent SQL injection
        $present_occur = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the staff
        if ($present_occur['id']) {
            $stmt = $conn->prepare("UPDATE staff_attendance SET attendance_category = :presentVal WHERE id = :id");
            $stmt->execute([':presentVal' => $presentVal, ':id'=>$present_occur['id']]);
        } else {
            $stmt = $conn->prepare("INSERT INTO staff_attendance (staff_id, attendance_category, date) VALUES (:staffId, :presentVal, :now)");
            $stmt->execute([':staffId' => $staffId, ':presentVal' => $presentVal, ':now' => $now]);
        }

        echo json_encode(['success' => true, 'message' => 'Attendance recorded successfully!']);
    } else {
        $staffId = $_POST['staff_id'];
        $comments = trim(htmlspecialchars($_POST['comments'] ?? ''));

        $occured_already = "SELECT id FROM staff_attendance WHERE staff_id = :staff_id AND date = :now";
        $stmt = $conn->prepare($occured_already);
        $stmt->execute([':staff_id' => $staffId, ':now' => $now]); // Use parameterized query to prevent SQL injection
        $staff_occur = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the staff

if ($staff_occur && isset($staff_occur['id'])) {
    
    $stmt = $conn->prepare("UPDATE staff_attendance SET comments = :comments WHERE id = :id ");
    $stmt->execute([':comments' => $comments, ':id' => $staff_occur['id']]);

    if ($stmt->rowCount() === 0) {
        echo "Update failed. No rows affected.";
        die;
    }
} else {
    $stmt = $conn->prepare("INSERT INTO staff_attendance (staff_id, comments, date) VALUES (:staff_id, :comments, :now)");
    $stmt->execute([':staff_id' => $staffId, ':comments' => $comments, ':now' => $now]);
}


        echo json_encode(['success' => true, 'message' => 'Attendance recorded successfully!']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
