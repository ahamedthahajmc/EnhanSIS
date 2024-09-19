<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "opensis";

$conn = new mysqli($servername, $username, $password, $dbname);



$alt_id = "";
$fee_table = "";
$selected_fee = "";
$Fee_type = "";
$student_id = "";
$last_name = "";
$first_name = "";
$fees_section = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alt_id = isset($_POST['alt_id']) ? $_POST['alt_id'] : "";
    $selected_fee = isset($_POST['fee_type']) ? $_POST['fee_type'] : "";
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : "";
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : "";
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : "";
    $fees_section = isset($_POST['fees_section']) ? $_POST['fees_section'] : "";

    switch ($selected_fee) {
        case "tuition":
            $Fee_type = "Tuition_Fees";
            break;
        case "book":
            $Fee_type = "Book_Fees";
            break;
        case "travel":
            $Fee_type = "Travel_Fees";
            break;
        default:
            $Fee_type = "";
            break;
    }

    switch ($alt_id) {
        case "101":
            $fee_table = "FeesResults";
            break;
        case "102":
            $fee_table = "fees1";
            break;
        case "103":
            $fee_table = "fees2";
            break;
        default:
            $fee_table = "";
            break;
    }

    if (!empty($fee_table) && !empty($Fee_type)) {
        $sql = "SELECT * FROM $fee_table WHERE Fees_Section = ?";
        $params = [$Fee_type];

        if (!empty($student_id)) {
            $sql .= " AND student_id = ?";
            $params[] = $student_id;
        }

        if (!empty($last_name)) {
            $sql .= " AND last_name = ?";
            $params[] = $last_name;
        }

        if (!empty($first_name)) {
            $sql .= " AND first_name = ?";
            $params[] = $first_name;
        }

        if (!empty($fees_section)) {
            $sql .= " AND fees_section = ?";
            $params[] = $fees_section;
        }

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        echo json_encode($rows);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();
