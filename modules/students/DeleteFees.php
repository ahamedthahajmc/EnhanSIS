<?php
// Include the database connection
include('../../Data.php');
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if 'id' is provided
    if (isset($_POST['id'])) {
        $fees_id = $_POST['id'];

        try {
            // Prepare the SQL statement to delete the record
            $sql = "DELETE FROM fees_details WHERE fees_id = :fees_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fees_id', $fees_id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Check how many rows were affected
                if ($stmt->rowCount() > 0) {
                    // Return success message
                    echo json_encode(['status' => 'success', 'message' => 'Fee record deleted successfully.']);
                } else {
                    // No row was deleted, probably the `fees_id` is not found
                    echo json_encode(['status' => 'error', 'message' => 'No fee record found with this ID.']);
                }
            } else {
                // If deletion fails
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete fee record.']);
            }
        } catch (PDOException $e) {
            // Return error message in case of exception
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // No fees_id provided
        echo json_encode(['status' => 'error', 'message' => 'No fees ID provided.']);
    }
} else {
    // If not a POST request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
