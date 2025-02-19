<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$db = 'haniims';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $certificateName = $_POST['certificate_name'] ?? null;

    if (empty($certificateName)) {
        echo json_encode(['status' => 'error', 'message' => 'Certificate name is required.']);
        exit;
    }

    // Insert the new certificate
    $stmt = $conn->prepare("INSERT INTO documents1 (certificate, upload_status) VALUES (?, ?)");
    $uploadStatus = "Not Uploaded";
    $stmt->bind_param("ss", $certificateName, $uploadStatus);

    if ($stmt->execute()) {
        // Fetch all documents after the insertion
        $result = $conn->query("SELECT certificate, upload_status FROM documents1");
        $documents = [];
        while ($row = $result->fetch_assoc()) {
            $documents[] = $row;
        }

        echo json_encode(['status' => 'success', 'documents' => $documents]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save certificate: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
