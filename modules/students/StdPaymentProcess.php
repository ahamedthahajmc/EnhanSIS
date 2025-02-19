<?php
include('../../Data.php'); // Include your database connection
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
$year = $_SESSION['UserSyear'];
$endYear = $year + 1;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $bookpay_amount = $_POST['bookpay_amount'] ?? 0;
    $tuitionpay_amount = $_POST['tuitionpay_amount'] ?? 0;
    $transportpay_amount = $_POST['transportpay_amount'] ?? 0;
    $payment_method = $_POST['payment_method'];
    $payment_type = $_POST['payment_type'];
    $instituteName = $_POST['instituteName'];
    $instituteAddress = $_POST['instituteAddress'];
    $instituteCity = $_POST['instituteCity'];
    $instituteState = $_POST['instituteState'];
    $instituteZipcode = $_POST['instituteZipcode'];
    $institutePhone = $_POST['institutePhone'];
    $username = $_POST['userName'];
    $CASHIER = $_POST['signature'];
    $totalBookFees = $fees['total_book_fees'] ?? 0; // Use null coalescing to handle no records

    $paidQuery = "
    SELECT 
        SUM(book_fees_paid) AS total_book_fees,
        SUM(tuition_fees_paid) AS total_tuition_fees,
        SUM(transport_fees_paid) AS total_transport_fees
    FROM fee_payment 
    WHERE student_id = :student_id
";
    $paidStmt = $conn->prepare($paidQuery);
    $paidStmt->execute(['student_id' => $student_id]);
    $feesPaid = $paidStmt->fetch(PDO::FETCH_ASSOC);

    $totalBookFees = $feesPaid['total_book_fees'];
    $totalTuitionFees = $feesPaid['total_tuition_fees'];
    $totalTransportFees = $feesPaid['total_transport_fees'];

    // Validate payment amounts
    if ((!is_numeric($bookpay_amount) || $bookpay_amount <= 0) && (!is_numeric($tuitionpay_amount) || $tuitionpay_amount <= 0) && (!is_numeric($transportpay_amount) || $transportpay_amount <= 0)) {
        echo "<div class='alert alert-danger'>Invalid payment amount!</div>";
        exit;
    }

    // Fetch fee details
    $stmt = $conn->prepare("SELECT fees_id,book_fees_amount, tuition_fees_amount, transport_fees_amount FROM fees_details WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $fees = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fees) {
        $fees_id = $fees['fees_id'];
        $book_fees = $fees['book_fees_amount'];
        $tuition_fees = $fees['tuition_fees_amount'];
        $transport_fees = $fees['transport_fees_amount'];

        // Calculate new balances
        $book_feesBalance = $book_fees - ($totalBookFees + $bookpay_amount);
        $tuition_feesBalance = $tuition_fees - ($totalTuitionFees + $tuitionpay_amount);
        $transport_feesBalance = $transport_fees - ($totalTransportFees + $transportpay_amount);
        $total_pay_amount = $bookpay_amount + $tuitionpay_amount + $transportpay_amount;
        $remaining_balance = $book_feesBalance + $tuition_feesBalance + $transport_feesBalance;

        if ($book_feesBalance >= 0 && $tuition_feesBalance >= 0 && $transport_feesBalance >= 0) {
           

            // Insert payment into fee_payment table
            $insert_payment_stmt = $conn->prepare("
                INSERT INTO fee_payment(fees_id, payment_method, student_id, student_name, Academic_year, payment_type,book_fees_paid,tuition_fees_paid,transport_fees_paid, payment_date, amount_paid)
                VALUES (:fees_id,  :payment_method, :student_id, :student_name, :Academic_year, :payment_type, :book_fees_paid, :tuition_fees_paid, :transport_fees_paid, :payment_date, :amount_paid)
            ");
            $insert_payment_stmt->execute([
                'fees_id' => $fees_id,
                'student_id' => $student_id,
                'student_name' => $student_name,
                'Academic_year' => $year,
                'payment_method' => $payment_method,
                'payment_type' => $payment_type,
                'book_fees_paid' => $bookpay_amount,
                'tuition_fees_paid' => $tuitionpay_amount,
                'transport_fees_paid' => $transportpay_amount,
                'payment_date' => date('Y-m-d'),
                'amount_paid' => $total_pay_amount
            ]);

            // Generate Receipt HTML with integrated CSS for print
            $receipt_html = "
<style>
    @media print {
    #backBtn, header, footer, nav, .sidebar {
        display: none !important;
    }
  * {
        box-sizing: border-box;
    }

          body * {
            margin: 0;
        padding: 0;
            visibility: hidden; /* Hide everything by default */
        }
        #receiptCard, #receiptCard * {
            visibility: visible; /* Only display the receipt section */
        }
        #receiptCard {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
        padding: 0;
        width: 100%; 
         page-break-inside: avoid;
        }
        
        #instituteDetails, #Sign {
            display: block !important;
        }
        #backBtn {
            display: none !important;
        }
        #footer-signatures {
            display: flex !important;
        }
        #current_date {
            display: block !important;
        }

        /* Ensure table rows have background color during print */
        .table thead th {
            background-color:  #e2e2e2 !important;
                        -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
            table, h1, h2, h3, h4 {
        page-break-inside: avoid;
        page-break-before: auto;
        page-break-after: auto;
    }
       
       
    }

    
    #current_date {
        display: none;
    }
    #instituteDetails, #Sign {
        display: none;
    }
    #footer-signatures {
        display: none;
    }
</style>

<div id='receiptCard'>
    <div class='text-center mb-2' id='instituteDetails'>
        <h1 id='institute_name' class='mb-1' >$instituteName</h1>
        <h3 id='instituteAddress' class='m-0'>$instituteAddress</h3>
        <h3 id='instituteCity' class='m-0'>$instituteCity, $instituteState-$instituteZipcode</h3>
        <h3 id='institutePhone' class='mt-1'>Ph:$institutePhone</h3>
        <br>
        <h3 id='institutePhone' class='mt-1'>$year-$endYear</h3>
    </div>
<br>
    <h2 class='text-center mt-2'>PAYMENT RECEIPT</h2>

    <h4 id='current_date' class='text-right mb-2'>" . date('d F Y') . "</h4>
    
    <h3 class='mb-2'>STUDENT DETAILS:</h3>
    <table class='table table-bordered mb-2'>
        <thead>
            <tr class='tableRow bg-grey-200'>
                <th class='text-wrap'>Student ID</th>
                <th class='text-wrap'>Student Name</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class='text-wrap'>" . htmlspecialchars($student_id) . "</td>
                <td class='text-wrap'>" . htmlspecialchars($student_name) . "</td>
            </tr>
        </tbody>
    </table>    

    <h3 class='mb-2'>PAYMENT DETAILS:</h3>
    <table class='table table-bordered mb-2'>
        <thead>
            <tr class='tableRow bg-grey-200'>
                <th class='text-wrap'>Payment Method</th>
                <th class='text-wrap'>Payment Type</th>
                <th class='text-wrap'>Amount Paid</th>
                <th class='text-wrap'>Total Balance</th>
                <th class='text-wrap'>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class='text-wrap'>" . htmlspecialchars($payment_method) . "</td>
                <td class='text-wrap'>" . htmlspecialchars($payment_type) . "</td>
                <td class='text-wrap'>" . htmlspecialchars($total_pay_amount) . "</td>
                <td class='text-wrap'>" . htmlspecialchars($remaining_balance) . "</td>
                <td class='text-wrap'>" . date('Y-m-d') . "</td>
            </tr>
        </tbody>
    </table>

    <h3 class='mb-2'>FEE BALANCE:</h3>
    <table class='table table-bordered mb-2'>
        <thead>
            <tr class='tableRow bg-grey-200'>
                <th class='text-wrap'>Book Fees</th>
                <th class='text-wrap'>Tuition Fees</th>
                <th class='text-wrap'>Transport Fees</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class='text-wrap'>" . htmlspecialchars($book_feesBalance) . "</td>
                <td class='text-wrap'>" . htmlspecialchars($tuition_feesBalance) . "</td>
                <td class='text-wrap'>" . htmlspecialchars($transport_feesBalance) . "</td>
            </tr>
        </tbody>
    </table>

    <div class='text-right mt-2' id='Sign'>
        <h5>" . htmlspecialchars($CASHIER) . "</h5>
        <h6>" . htmlspecialchars($username) . "</h6>
    </div>
</div>
    ";

            echo $receipt_html;
            echo '<br><hr/>';
            echo "
<div class='text-right'>
    <button id='printBtn' onclick='printReceipt()' class='btn btn-primary'>Print</button>
</div>
<script>
$('#payFeesForm').hide();
    function printReceipt() {
         document.getElementById('receiptCard').style.display = 'block'; // Ensure receipt is visible
         window.print();
    }
</script>
";
        } else {
            echo "<div class='alert alert-danger'>Payment exceeds remaining balance!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Student ID not found!</div>";
    }
}
?>


<!-- -------------------------------- -->