<?php
session_start();

include('../../Data.php'); // Include your DB connection file
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$section = $_GET['section'] ?? null;
$first_name = $_GET['first_name'] ?? null;
$last_name=$_GET['last_name'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $instituteId = $_SESSION['UserInstitute'];

    if ($action === 'search_by_grade') {
        $grade = $_POST['grade'] ?? null;
        $section = $_POST['section'] ?? null;
        $year=$_POST['year'];

        if ($grade) {
            try {
                // Base query to get student IDs, names, grade title, and section name from institute_gradelevel_sections
                $sql = "
                    SELECT se.student_id, CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
                           g.TITLE AS grade_title, sec.name AS section_name
                    FROM student_enrollment se
                    JOIN institute_gradelevels g ON se.grade_id = g.id
                    JOIN students s ON se.student_id = s.student_id  -- Joining with students table to get names
                    JOIN fees_details f ON se.student_id = f.student_id -- Join to filter only students with fees details
                    LEFT JOIN institute_gradelevel_sections sec ON se.section_id = sec.id -- Join with sections table to get section name
                    WHERE g.id = :grade AND se.syear= :year AND se.institute_id = :instituteId
                ";

                // Add section filtering if provided
                if ($section) {
                    $sql .= " AND sec.id = :section";
                }

                // Prepare and execute the query
                $stmt = $conn->prepare($sql);
                $params = ['grade' => $grade,
                            'year'=> $year,
                            'instituteId' => $instituteId
                        ];

                if ($section) {
                    $params['section'] = $section;
                }

                $stmt->execute($params);

                // Fetch the results
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($students) {
                    // Prepare response data, including grade title and section name
                    $responseData = [
                        'success' => true,
                        'student_ids' => array_column($students, 'student_id'),
                        'student_names' => array_column($students, 'student_name'),
                        'grade_title' => $students[0]['grade_title'],
                        'section_name' => $students[0]['section_name'] ?? '' // Handle possible empty section name
                    ];
                    error_log(print_r($responseData, true)); // Log the response data for debugging

                    // Return the JSON response
                    echo json_encode($responseData);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No students found for the selected grade and section']);
                }

            } catch (PDOException $e) {
                error_log('Database error: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error fetching students: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Grade is required']);
        }
    }
    
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

else if ($first_name || $last_name) {
    $first_name = $first_name ?? null;
    $last_name = $last_name ?? null;
    $year = $_SESSION['UserSyear'] ?? null;

    if (($first_name || $last_name) && $year) {
        try {
            // Base query
            $sql = "
                SELECT s.student_id, CONCAT(s.first_name, ' ', s.last_name) AS student_name, se.institute_id, 
                COALESCE(sec.name, 'N/A') AS section_name,                
                g.TITLE AS grade_title
                FROM `students` as s 
                LEFT JOIN student_enrollment se ON se.student_id = s.student_id                
                LEFT JOIN institute_gradelevel_sections sec ON se.section_id = sec.id
                LEFT JOIN institute_gradelevels g ON se.grade_id = g.id
                WHERE (s.first_name = :first_name OR s.last_name = :last_name)
                AND se.syear = :year
            ";

            $stmt = $conn->prepare($sql);

            // Parameters
            $params = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'year' => $year,
                // 'instituteId' => $instituteId
            ];

            $stmt->execute($params);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($students) {
                echo json_encode([
                    'success' => true,
                    'students' => $students,
                    'grade_title' => $students[0]['grade_title'] ?? 'N/A',
                    'section_name' => $students[0]['section_name'] ?? 'N/A'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No students found for the given details.'
                ]);
            }
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'A database error occurred. Please try again later.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Year is a required field.'
        ]);
    }
}
?>
