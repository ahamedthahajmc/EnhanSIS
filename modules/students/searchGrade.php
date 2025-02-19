<?php
include('../../Data.php'); // Include your DB connection file
session_start(); // Ensure session is started

if (!isset($_SESSION['UserInstitute'])) {
    echo json_encode(['success' => false, 'message' => 'Institute is not set in session']);
    exit;
}

// Initialize the PDO connection
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the institute ID from session
$institute_id = $_SESSION['UserInstitute'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'search_by_grade') {
        $grade = $_POST['grade'] ?? null;
        $section = $_POST['section'] ?? null;
        $year = $_POST['year'] ?? null; // Get the year from the request
        $stud_id = $_POST['student_id'] ?? null;
        // Debugging the inputs
        error_log("Grade: $grade, Year: $year, Section: $section");

        if ($grade && $year && !$stud_id) { // Check if both grade and year are provided
            try {
                // Check if the connection was established
                if (!isset($conn)) {
                    die("Connection failed: Connection variable is null.");
                }

                // Query to get student IDs for the current year and institute
                $sql = "
                    SELECT se.student_id, se.institute_id, g.title AS grade_title, s.name AS section_name
                    FROM student_enrollment se
                    JOIN institute_gradelevels g ON se.grade_id = g.id
                    LEFT JOIN institute_gradelevel_sections s ON se.section_id = s.id
                    WHERE g.id = :grade AND se.syear = :year AND se.institute_id = :institute_id
                ";

                $params = [
                    'grade' => $grade,
                    'year' => $year,
                    'institute_id' => $institute_id, // Use institute ID here
                ];

                // Add section condition if provided
                if ($section) {
                    $sql .= " AND se.section_id = :section";
                    $params['section'] = $section;
                }

                // Prepare and execute the query
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);

                // Fetch the results
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($students) {
                    // Extract student IDs and other details for further processing
                    $student_ids = array_column($students, 'student_id');
                    $grade_title = $students[0]['grade_title'] ?? 'N/A';
                    $section_name = $students[0]['section_name'] ?? 'N/A';
                    $student_count = count($students);

                    // Return success response with student details
                    echo json_encode([
                        'success' => true,
                        'student_ids' => $student_ids,
                        'grade_title' => $grade_title,
                        'section_name' => $section_name,
                        'student_count' => $student_count
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No students found for the selected grade, section, and year']);
                }
            } catch (PDOException $e) {
                error_log('Database error: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error processing the search result. Please try again later.']);
            }
        }
        else if ($stud_id) {
            try {
                // Check if the connection was established
                if (!isset($conn)) {
                    die("Connection failed: Connection variable is null.");
                }
        
                // Query to get student details for the specific student ID
                $sql = "
                    SELECT concat(st.first_name,' ',st.last_name) as stud_name, se.student_id, se.institute_id, g.title AS grade_title, s.name AS section_name
                    FROM student_enrollment se
                    JOIN institute_gradelevels g ON se.grade_id = g.id
                    LEFT JOIN institute_gradelevel_sections s ON se.section_id = s.id
                    LEFT JOIN students st ON st.student_id = se.student_id
                    WHERE se.student_id = :stud_id AND se.syear = :year AND se.institute_id = :institute_id
                ";
        
                $params = [
                    'stud_id' => $stud_id,
                    'year' => $year,
                    'institute_id' => $institute_id,
                ];
        
                // Prepare and execute the query
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
        
                // Fetch the results
                $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($student) {
                    // Return success response with student details
                    echo json_encode([
                        'success' => true,
                        'student_ids' => [$student['student_id']], // Return as an array
                        'grade_title' => $student['grade_title'],
                        'section_name' => $student['section_name'],
                        'student_name' => $student['stud_name'],
                        'student_count' => 1 // Since it's a single student
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No student found with the provided ID']);
                }
            } catch (PDOException $e) {
                error_log('Database error: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error processing the search result. Please try again later.']);
            }
        } 
         else {
            echo json_encode(['success' => false, 'message' => 'Grade and year are required']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}
?>
