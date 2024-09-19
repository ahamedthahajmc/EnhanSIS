
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentId = $_POST['studentId'];
    $studentName = $_POST['studentName'];
    $feesType = $_POST['feesType'];
    $amount = $_POST['amount'];
    $paymentDate = $_POST['paymentDate'];
    $payment_gateway = $_POST['payment_gateway'];

    // Connect to the database (replace with your own credentials)
    $conn = new mysqli('localhost', 'root', '', 'schooldb');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO pay_fee (student_id, student_name, fees_type, amount, payment_date, payment_gateway)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $studentId, $studentName, $feesType, $amount, $paymentDate, $payment_gateway);

    if ($stmt->execute()) {
        // Simulate payment process
        if ($payment_gateway === 'gpay') {
        
            header('Location: GpayPayment.php?studentId=' . $studentId);
            exit();
        }
        elseif ($payment_gateway === 'paytm') {
       
            header('Location:PaymentReceipt.php?studentId=' . $studentId);
            exit();
        }
        elseif ($payment_gateway === 'card') {
      
            header('Location: CardPayment.php?studentId=' . $studentId);
            exit();
        } 
       else {
            echo "Payment completed successfully.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>