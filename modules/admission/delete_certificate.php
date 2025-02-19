<?php
include './DB.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $certificateId = intval($_POST['id']); // Get certificate ID from POST
    $query = "DELETE FROM certificate_types WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $certificateId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting record']);
    }

    $stmt->close();
    $conn->close();
}
?>
