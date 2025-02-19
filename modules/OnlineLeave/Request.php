<?php
include('../../Data.php');
header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $action = $_POST['action'];
        $remark = $_POST['remark'];

        // Update the database based on action
        $stmt = $conn->prepare("UPDATE leave_application SET status = :status, remark = :remark WHERE id = :id");
        $stmt->bindParam(':status', $action);
        $stmt->bindParam(':remark', $remark);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => ucfirst($action) . ' updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating leave request.']);
        }
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error retrieving data: ' . $e->getMessage()]);
    error_log($e->getMessage());
    exit;
}
?>
