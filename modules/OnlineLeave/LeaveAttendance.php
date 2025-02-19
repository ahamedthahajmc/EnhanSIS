<?php
PopTable('header', _leaveattendance);
include('../../Data.php');

try {
    // Connecting to database
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve approved leave applications only
    $stmt = $conn->query("SELECT * FROM leave_application WHERE status = 'approved' ORDER BY student_id DESC");
    $leave_application_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Array to store student leave data by month
    $students_leave_data = [];

    foreach ($leave_application_data as $leave) {
        $studentID = $leave['student_id'];
        $studentName = $leave['last_name'] . '.' . $leave['first_name'];
        $grade = $leave['grade'];
        $section = $leave['section'];
        $applyDate = strtotime($leave['apply_date']);
        $month = date('M', $applyDate); // Get the month of the leave application

        if (!isset($students_leave_data[$studentID])) {
            $students_leave_data[$studentID] = array_fill_keys(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 0);
            $students_leave_data[$studentID]['Name'] = $studentName;
            $students_leave_data[$studentID]['Grade'] = $grade;
            $students_leave_data[$studentID]['Section'] = $section;
        }

        // Increment the leave count for the given month
        $students_leave_data[$studentID][$month]++;
    }
} catch (PDOException $e) {
    echo 'Error retrieving data: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Leave Attendance</title>
    <style>
        /* Styling for the table and page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 9px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        td:first-child {
            background-color: #e0f7fa;
            font-weight: bold; 
        }

        .total {
            font-weight: bold;
            background-color: #ffeb3b; 
            color: #000; 
        }

    </style>
</head>
<body>

<h2 style="color: blue;">Student Leave Attendance</h2>
<div class="table-responsive">
<table>
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Grade</th>
        <th>Section</th>
        <th>Jan</th>
        <th>Feb</th>
        <th>Mar</th>
        <th>Apr</th>
        <th>May</th>
        <th>Jun</th>
        <th>Jul</th>
        <th>Aug</th>
        <th>Sep</th>
        <th>Oct</th>
        <th>Nov</th>
        <th>Dec</th>
        <th>Total</th>
    </tr>

    <?php foreach ($students_leave_data as $studentID => $months): ?>
        <tr>
        <td><?php echo htmlspecialchars($studentID); ?></td>
            <td><?php echo htmlspecialchars($months['Name']); ?></td>
            <td><?php echo htmlspecialchars($months['Grade']); ?></td>
            <td><?php echo htmlspecialchars($months['Section']); ?></td>
            <?php $total = 0; ?>
            <?php foreach ($months as $month => $count): ?>
                <?php if ($month != 'Name' && $month != 'Grade' && $month != 'Section'): ?>
                    <td><?php echo $count; ?></td>
                    <?php $total += $count; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <td class="total"><?php echo $total; ?></td>
        </tr>
    <?php endforeach; ?>

</table>
</div>
</body>
</html>
