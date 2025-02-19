<?php
session_start();
// include './DB.php';
include('../../Data.php');

$instituteId = $_SESSION['UserInstitute'];
$section = $_GET['section'] ?? null;
$grade = $_GET['grade'] ?? null;
$first_name = $_GET['first_name'] ?? null;
$last_name=$_GET['last_name'] ?? null;


// Initialize the PDO connection
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ( $grade != "" ) {
    $grade = $grade ?? null;
    $section = $section ?? null;
    $year = $_SESSION['UserSyear'] ?? null;

    if ($grade && $year) {
        try {
            // Base query
            $sql = "
                SELECT 
                    se.student_id, 
                    CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
                    g.TITLE AS grade_title, 
                    COALESCE(sec.name, 'N/A') AS section_name
                FROM student_enrollment se
                JOIN institute_gradelevels g ON se.grade_id = g.id
                JOIN students s ON se.student_id = s.student_id
                LEFT JOIN institute_gradelevel_sections sec ON se.section_id = sec.id
                WHERE g.id = :grade 
                  AND se.syear = :year 
                  AND se.institute_id = :instituteId
            ";

            // Add section filter if provided
            if ($section) {
                $sql .= " AND sec.id = :section";
            }

            $stmt = $conn->prepare($sql);

            // Parameters
            $stmt = $conn->prepare($sql);
                $params = ['grade' => $grade,
                            'year'=> $year,
                            'instituteId' => $instituteId
                        ];

            if ($section) {
                $params['section'] = $section;
            }

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
                    'message' => 'No students found for the selected grade and section.'
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
            'message' => 'Grade and Year are required fields.'
        ]);
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
                AND se.institute_id = :instituteId
            ";

            $stmt = $conn->prepare($sql);

            // Parameters
            $params = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'year' => $year,
                'instituteId' => $instituteId
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
