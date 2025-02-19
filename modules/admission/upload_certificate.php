<?php
// Database connection details
$host = 'localhost';
$dbname = 'haniims';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
    die();
}

if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['certificate_file'];
    $certificateName = isset($_POST['certificate_name']) ? $_POST['certificate_name'] : null;
    $studentId = isset($_POST['student_id']) ? (int)$_POST['student_id'] : null; // Ensure student_id is retrieved correctly

    // Validate student ID and certificate name
    if (empty($studentId) || empty($certificateName)) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing student ID or certificate name.']);
        exit;
    }

    // Define the upload directory specific to the student
    $uploadDir = 'uploads/certificates/' . $studentId . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . '_' . basename($file['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        try {
            // Check if the student ID exists in the students table
            $stmt = $pdo->prepare("SELECT student_id FROM students WHERE student_id = ?");
            $stmt->execute([$studentId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                echo json_encode(['success' => false, 'message' => 'Invalid student ID.']);
                exit;
            }

            // Delete any existing record for the same certificate for this student
            $stmt = $pdo->prepare("DELETE FROM certificate_details WHERE student_id = ? AND certificate_name = ?");
            $stmt->execute([$studentId, $certificateName]);

            // Insert the new certificate record
            $stmt = $pdo->prepare("INSERT INTO certificate_details (student_id, certificate_name, upload_status, file_path) 
                                   VALUES (?, ?, 'Uploaded', ?)");
            $stmt->execute([$studentId, $certificateName, $filePath]);

            // Generate the public URL for the file
            $publicFilePath = 'http://' . $_SERVER['HTTP_HOST'] . '/HaniIMS/modules/admission/uploads/certificates/' . $studentId . '/' . $fileName;

            echo json_encode(['success' => true, 'message' => 'Certificate uploaded successfully.', 'file_path' => $publicFilePath]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move the uploaded file.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or file error.']);
}
?>
