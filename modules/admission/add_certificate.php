<?php
$host = 'localhost';
$dbname = 'haniims';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$certificateName = $_POST['certificate_name'] ?? '';

if (empty($certificateName)) {
    echo json_encode(['success' => false, 'message' => 'Certificate name is required.']);
    exit;
}

// Prepare the SQL query to insert the certificate name into the certificate_types table
$stmt = $pdo->prepare("INSERT INTO certificate_types (certificate_name) VALUES (?)");
$stmt->execute([$certificateName]);

echo json_encode(['success' => true, 'message' => 'Certificate added successfully.']);
?>
