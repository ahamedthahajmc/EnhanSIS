<?php
//include 'ConnectionClass.php';
//require_once "functions/PragRepFnc.php";
//require_once('functions/PopTableFnc.php');
//include('lang/language.php');
//include('../../RedirectModulesInc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentId = $_POST['studentId'];
    $studentName = $_POST['studentName'];
    $feesType = $_POST['feesType'];
    $amount = $_POST['amount'];
    $paymentDate = $_POST['paymentDate'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Payment Summary</title>
</head>
<body>

<div class="container">
    <h2 class="mt-4">Payment Summary</h2>
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
                <td><?php echo htmlspecialchars($studentId); ?></td>
                <td><?php echo htmlspecialchars($studentName); ?></td>
                <td><?php echo htmlspecialchars($feesType); ?></td>
                <td><?php echo htmlspecialchars($amount); ?></td>
                <td><?php echo htmlspecialchars($paymentDate); ?></td>
                <td>
                    <form action="PaymentProcess.php" id="paymentprocess" method="POST">
                        <select class="form-control" name="payment_gateway" required>
                            <option value="">Select Payment Method</option>
                            <option value="gpay">GPay</option>
                            <option value="paytm">Paytm</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="offline">Offline Payment</option>
                        </select>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Hidden fields to forward the payment data -->
    <input type="hidden" name="studentId" value="<?php echo htmlspecialchars($studentId); ?>">
    <input type="hidden" name="studentName" value="<?php echo htmlspecialchars($studentName); ?>">
    <input type="hidden" name="feesType" value="<?php echo htmlspecialchars($feesType); ?>">
    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
    <input type="hidden" name="paymentDate" value="<?php echo htmlspecialchars($paymentDate); ?>">

    <div class="text-right">
        <button type="submit" class="btn btn-primary">Submit Payment</button>
        <a href="PayFees.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

</body>
</html>
