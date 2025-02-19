<?php
include 'DB.php';

if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);

    $query = "
        SELECT 
            ct.certificate_name, ct.id as certificate_id,
            COALESCE(sc.upload_status, 'Pending') AS upload_status,
            sc.file_path
        FROM certificate_types ct
        LEFT JOIN certificate_details sc 
        ON ct.certificate_name = sc.certificate_name AND sc.student_id = $student_id
    ";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($conn));
    }

    $certificates = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Generate the public URL for the file path
        if ($row['file_path']) {
            // Convert local file path to a public URL
            $row['file_path'] = 'http://' . $_SERVER['HTTP_HOST'] . '/HaniIMS/modules/admission/uploads/certificates/' . $student_id . '/' . basename($row['file_path']);
        }

        $certificates[] = $row;
    }

    echo json_encode($certificates);
} else {
    echo json_encode(['success' => false, 'message' => 'student_id not provided.']);
}
?>
