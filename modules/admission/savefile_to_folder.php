<?php
session_start();
include_once("../../includes/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && isset($_POST['certificate'])) {
        $file = $_FILES['file'];
        $certificate = $_POST['certificate'];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            die(json_encode(['status' => 'error', 'message' => 'File upload error: ' . $file['error']]));
        }

        // Prepare file path
        $upload_dir = "files/";
        $file_path = $upload_dir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            echo json_encode(['status' => 'success', 'message' => 'File saved temporarily']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
}
?>
