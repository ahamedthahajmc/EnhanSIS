<?php
include('../../Data.php'); // Include your database connection
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

$instituteId = $_SESSION['UserInstitute'];
$q = "SELECT content FROM user_file_upload WHERE institute_id = :institute_id";
$stmt = $conn->prepare($q);
$stmt->bindParam(':institute_id', $instituteId, PDO::PARAM_INT);
$stmt->execute();
$content = $stmt->fetchColumn();
if (!empty($content)) {
    header("Content-Type: image/png"); // Set correct MIME type for the image
    echo $content; // Output the image data directly
} else {
    header("Content-Type: image/jpeg");
    readfile(""); // Provide a default image if none exists
}



?>