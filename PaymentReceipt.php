<?php
$studentId = $_GET['studentId'];

// Fetch data from the database
$conn = new mysqli('localhost', 'root', '', 'schooldb');
$sql = "SELECT * FROM pay_fee WHERE student_id = '$studentId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No record found.";
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Payment Receipt</title>
</head>
<body>

<div class="container">
    <h2 class="mt-4">Payment Receipt</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Fees Type</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['student_name']; ?></td>
                <td><?php echo $row['fees_type']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['payment_date']; ?></td>
                <td><?php echo $row['payment_gateway']; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-right">
        <button onclick="window.print();" class="btn btn-success">Print</button>
        <a href="generate_pdf.php?studentId=<?php echo $studentId; ?>" class="btn btn-danger">Download PDF</a>
        <a href="index.php" class="btn btn-secondary">Finish</a>
    </div>
</div>

</body>
</html>
