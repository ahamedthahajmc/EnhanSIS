<?php
include('../../Data.php'); // Include your DB connection file
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade_id = $_POST['grade_id'] ?? null;

    if ($grade_id) {
        try {
            // Fetch sections for the selected grade from student_enrollment table
            $sql = "
                SELECT DISTINCT s.id, s.name
                FROM student_enrollment se
                JOIN institute_gradelevel_sections s ON se.section_id = s.id
                WHERE se.grade_id = :grade_id
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['grade_id' => $grade_id]);
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add the "Select Section" option initially
            echo '<option value="">Select Section</option>';

            // Return the sections as options
            foreach ($sections as $section) {
                echo '<option value="' . $section['id'] . '">' . $section['name'] . '</option>';
            }
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            echo '';
        }
    }
}
?>