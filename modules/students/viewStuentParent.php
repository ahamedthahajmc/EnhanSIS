<?php
include('../../Data.php');
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//PopTable('header', _viewfees);
// echo "ID: " . htmlspecialchars(UserStudentID());

$userProfile = User('PROFILE');
$userName = User('USERNAME');

if ($userProfile === 'student' || $userProfile === 'parent') {
    PopTable('header', _viewfees);
    $student_Id = UserStudentID(); // Shortcut to get student ID

    // Fetch fee details for the student
    $sql = "
        SELECT fd.fees_id, fd.student_id, fd.student_name, fd.syear, 
               fd.book_fees_amount, fd.tuition_fees_amount, fd.transport_fees_amount, fd.total_fees
        FROM fees_details fd
        WHERE fd.student_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$student_Id]);
    $feesDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch payment summary for the student
    $sql2 = "
        SELECT 
            student_id,
            SUM(book_fees_paid) AS total_book_fees,
            SUM(tuition_fees_paid) AS total_tuition_fees,
            SUM(transport_fees_paid) AS total_transport_fees
        FROM fee_payment
        WHERE student_id = ?
        GROUP BY student_id
    ";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$student_Id]);
    $feesPaid = $stmt2->fetch(PDO::FETCH_ASSOC) ?? [
        'total_book_fees' => 0,
        'total_tuition_fees' => 0,
        'total_transport_fees' => 0,
    ];

    // Generate output table
    $output = '<div style="max-height: 400px; overflow-y: auto;">';
    $output .= '<table class="table table-bordered table-responsive" id="fees-table">';
    $output .= '<thead>
                    <tr>
                        <th class="bg-grey-200">Student ID</th>
                        <th class="bg-grey-200">Academic Year</th>
                        <th class="bg-grey-200">Student Name</th>
                        <th class="bg-grey-200">Book Fees</th>
                        <th class="bg-grey-200">Book Fees Balance</th>
                        <th class="bg-grey-200">Tuition Fees</th>
                        <th class="bg-grey-200">Tuition Fees Balance</th>
                        <th class="bg-grey-200">Transport Fees</th>
                        <th class="bg-grey-200">Transport Fees Balance</th>
                        <th class="bg-grey-200">Total Fees</th>
                        <th class="bg-grey-200">Total Balance Fees</th>
                    </tr>
                </thead>';
    $output .= '<tbody>';

    foreach ($feesDetails as $fee) {
        $balanceBookFees = $fee['book_fees_amount'] - $feesPaid['total_book_fees'];
        $balanceTuitionFees = $fee['tuition_fees_amount'] - $feesPaid['total_tuition_fees'];
        $balanceTransportFees = $fee['transport_fees_amount'] - $feesPaid['total_transport_fees'];
        $totalBalanceFees = $balanceBookFees + $balanceTuitionFees + $balanceTransportFees;

        $output .= '<tr id="row-' . htmlspecialchars($fee['fees_id']) . '">';
        $output .= '<td>' . htmlspecialchars($fee['student_id']) . '</td>';
        $output .= '<td>' . htmlspecialchars($fee['syear']) . '</td>';
        $output .= '<td>' . htmlspecialchars($fee['student_name']) . '</td>';
        $output .= '<td>' . htmlspecialchars($fee['book_fees_amount']) . '</td>';
        $output .= '<td>' . htmlspecialchars($balanceBookFees) . '</td>';
        $output .= '<td>' . htmlspecialchars($fee['tuition_fees_amount']) . '</td>';
        $output .= '<td>' . htmlspecialchars($balanceTuitionFees) . '</td>';
        $output .= '<td>' . htmlspecialchars($fee['transport_fees_amount']) . '</td>';
        $output .= '<td>' . htmlspecialchars($balanceTransportFees) . '</td>';
        $output .= '<td>' . htmlspecialchars($fee['total_fees']) . '</td>';
        $output .= '<td>' . htmlspecialchars($totalBalanceFees) . '</td>';
        $output .= '</tr>';
    }

    $output .= '</tbody></table>';
    $output .= '</div>';

    echo $output;
} else {
    echo '<div class="alert alert-warning" role="alert">Access restricted to students and parents only.</div>';
}
?>
