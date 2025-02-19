<?php
// Pop the dashboard header
PopTable('header', _dashboard);


if (!isset($_SESSION['UserInstitute'])) {
}

$instituteId = $_SESSION['UserInstitute'];

?>

<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr class="bg-grey-200">
                    <th class="text-wrap">Grades</th>
                    <th class="text-wrap">No Of Students</th>
                    <th class="text-wrap">Left Students</th>
                    <th class="text-wrap">RTE Students</th>
                    <th class="text-wrap">Total Amount</th>
                    <th class="text-wrap">Collected Amount</th>
                    <th class="text-wrap">Balance Amount</th>
                    <th class="text-wrap">Collected Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Initialize total counters
                $total_grades = 0;
                $total_students = 0;
                $total_left_students = 0;
                $total_rte_students = 0;
                $total_collected = 0;
                $total_balance = 0;
                $total_amount = 0;
                $total_percentage = 0;

                // Fetch all grades for the current institute
                $grades = DBGet(DBQuery("
                    SELECT id, title as TOTAL_GRADES 
                    FROM institute_gradelevels 
                    WHERE institute_id = '$instituteId'
                "));

                foreach ($grades as $grade) {
                    $total_grades++;
                    $grade_id = $grade['ID'];

                    // Fetch the number of students for the current grade
                    $students = DBGet(DBQuery("
                        SELECT COUNT(se.grade_id) as STUDENTS 
                        FROM student_enrollment se
                        WHERE se.grade_id = '$grade_id' AND se.institute_id = '$instituteId'
                    "));
                    $num_students = $students[1]['STUDENTS'] ?? 0;


                    $RTE_Students = DBGet(DBQuery("
    SELECT COUNT(s.student_id) as RTE_STUDENTS
    FROM student_enrollment se
    JOIN students s ON se.student_id = s.student_id
    WHERE se.grade_id = '$grade_id' 
      AND se.institute_id = '$instituteId'
      AND s.RTE = 1
"));
                    $rte_students = $RTE_Students[1]['RTE_STUDENTS'] ?? 0;



                    // Fetch the number of left students for the current grade
                    $LeftStudents = DBGet(DBQuery("
                        SELECT COUNT(se.student_id) AS TOTAL_LEFT 
                        FROM student_enrollment AS se 
                        WHERE se.grade_id = '$grade_id' AND se.institute_id = '$instituteId' AND se.drop_code IS NOT NULL
                    "));
                    $left_students = $LeftStudents[1]['TOTAL_LEFT'] ?? 0;

                    // Fetch total fees for the current grade
                    $TotalFees = DBGet(DBQuery("
                        SELECT SUM(fd.total_fees) AS TOTAL_FEES 
                        FROM fees_details fd 
                        JOIN student_enrollment se ON fd.student_id = se.student_id 
                        WHERE se.grade_id = '$grade_id' AND se.institute_id = '$instituteId'
                    "));
                    $total_fees = $TotalFees[1]['TOTAL_FEES'] ?? 0;

                    // Fetch total paid amount for the current grade
                    $TotalPaid = DBGet(DBQuery("
                        SELECT SUM(fp.amount_paid) AS TOTAL_PAID 
                        FROM fee_payment fp 
                        JOIN student_enrollment se ON fp.student_id = se.student_id 
                        WHERE se.grade_id = '$grade_id' AND se.institute_id = '$instituteId'
                    "));
                    $total_paid = $TotalPaid[1]['TOTAL_PAID'] ?? 0;

                    // Calculate payment percentage
                    $payment_percentage = $total_fees > 0 ? round(($total_paid / $total_fees) * 100, 2) : 0;

                    // Fetch total balance for the current grade
                    $PaymentBalance = $total_fees - $total_paid;

                    // Update total counters
                    $total_students += $num_students;
                    $total_left_students += $left_students;
                    $total_rte_students+= $rte_students;

                    $total_collected += $total_paid;
                    $total_balance += $PaymentBalance;
                    $total_amount += $total_fees;
                    $total_percentage += $payment_percentage;
                ?>
                    <tr>
                        <td class="text-wrap"><?php echo htmlspecialchars(strtoupper($grade['TOTAL_GRADES'])); ?></td>
                        <td class="text-wrap"><?php echo htmlspecialchars($num_students); ?></td>
                        <td class="text-wrap"><?php echo htmlspecialchars($left_students); ?></td>
                        <td class="text-wrap"><?php echo htmlspecialchars($rte_students); ?></td> <!-- Ensures $rte_students is set -->
                        <td class="text-wrap"><?php echo htmlspecialchars($total_fees); ?></td>
                        <td class="text-wrap"><?php echo htmlspecialchars($total_paid); ?></td>
                        <td class="text-wrap"><?php echo htmlspecialchars($PaymentBalance); ?></td>
                        <td class="text-wrap"><?php echo htmlspecialchars($payment_percentage . '%'); ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="bg-light">
                    <th class="text-wrap">TOTAL: <?php echo htmlspecialchars($total_grades); ?></th>
                    <th class="text-wrap"><?php echo htmlspecialchars($total_students); ?></th>
                    <th class="text-wrap"><?php echo htmlspecialchars($total_left_students); ?></th>
                    <th class="text-wrap"><?php echo htmlspecialchars($total_rte_students); ?></th>
                    <th class="text-wrap"><?php echo htmlspecialchars($total_amount); ?></th>
                    <th class="text-wrap"><?php echo htmlspecialchars($total_collected); ?></th>
                    <th class="text-wrap"><?php echo htmlspecialchars($total_balance); ?></th>
                    <th class="text-wrap"><?php echo htmlspecialchars($total_percentage . '%'); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>