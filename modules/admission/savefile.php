<?php
// Database connection
$host = 'localhost';
$db = 'haniims';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and handle undefined array keys
    $certificate = $_POST['certificate'];
    $status = isset($_POST['status']) ? $_POST['status'] : '';  // Default value if status is not provided
    $upload_date = isset($_POST['upload_date']) ? $_POST['upload_date'] : date('Y-m-d');  // Default to today's date if upload_date is not provided

    // Check if file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = basename($_FILES['file']['name']);
        $uploadFolder = 'files/';

        // Ensure uploads directory exists
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);  // Create the folder with proper permissions
        }

        $destPath = $uploadFolder . $fileName;

        // Move the uploaded file to the uploads folder
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Prepare and execute the database insert or update statement
            $stmt = $conn->prepare('INSERT INTO documents (certificate, file_name, file_path, status, upload_date) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE file_name=?, file_path=?, status=?, upload_date=?');
            $stmt->bind_param('sssssssss', $certificate, $fileName, $destPath, $status, $upload_date, $fileName, $destPath, $status, $upload_date);

            if ($stmt->execute()) {
                // Successful file upload and database insertion
                echo json_encode(['status' => 'success']);
            } else {
                // Database error
                echo json_encode(['status' => 'error', 'message' => 'Database error']);
            }
        } else {
            // File upload failure
            echo json_encode(['status' => 'error', 'message' => 'File upload failed']);
        }
    } else {
        // No file uploaded or upload error
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
    }
}
?>
