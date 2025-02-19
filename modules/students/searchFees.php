<?php
include('../../Data.php'); // Include your DB connection file
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = $_POST['last'] ?? null;
    $first_name = $_POST['first'] ?? null;
    $student_id = $_POST['stuid'] ?? null;
    $alt_id = $_POST['altid'] ?? null;

    // Construct the base SQL query
    $sql = "SELECT student_id, last_name, first_name FROM students WHERE 1=1";
    
    // Array to store query parameters
    $params = [];

    // Add conditions based on input values
    if ($last_name) {
        $sql .= " AND last_name = :last_name";
        $params['last_name'] = $last_name;
    }

    if ($first_name) {
        $sql .= " AND first_name = :first_name";
        $params['first_name'] = $first_name;
    }

    if ($student_id) {
        $sql .= " AND student_id = :student_id";
        $params['student_id'] = $student_id;
    }

    if ($alt_id) {
        $sql .= " AND alt_id = :alt_id";
        $params['alt_id'] = $alt_id;
    }

    try {
        $stmt = $conn->prepare($sql); // Prepare the SQL statement
        $stmt->execute($params); // Execute with parameters

        $student = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the student

        if ($student) {
            // Combine first and last name for full student name
            $student_name = $student['first_name'] . ' ' . $student['last_name'];

            // Return success response with student data
            echo json_encode([
                'success' => true, 
                'student_id' => $student['student_id'], 
                'student_name' => $student_name
            ]);
        } else {
            // No student found
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
    } catch (PDOException $e) {
        // Handle SQL execution errors
        echo json_encode(['success' => false, 'message' => 'Error searching student: ' . $e->getMessage()]);
    }
}
?>
