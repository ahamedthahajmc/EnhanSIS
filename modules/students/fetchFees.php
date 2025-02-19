<?php
include('../../Data.php');
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_ids = $_POST['student_ids'] ?? null;

    if ($student_ids) {
        try {
            $student_ids = explode(',', $student_ids);
            $placeholders = implode(',', array_fill(0, count($student_ids), '?'));

            $sql = "
                SELECT fd.fees_id, fd.student_id, fd.student_name, fd.syear, fd.book_fees_amount, fd.tuition_fees_amount, fd.transport_fees_amount, fd.total_fees
                FROM fees_details fd
                WHERE fd.student_id IN ($placeholders)
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute($student_ids);
            $feesDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($feesDetails) {
                // Fetch payments for each student
                $feesSummary = [];
                $sql2 = "
                    SELECT 
                        student_id,
                        SUM(book_fees_paid) AS total_book_fees,
                        SUM(tuition_fees_paid) AS total_tuition_fees,
                        SUM(transport_fees_paid) AS total_transport_fees
                    FROM fee_payment
                    WHERE student_id IN ($placeholders)
                    GROUP BY student_id
                ";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute($student_ids);
                $feesPaid = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                // Organize payments by student ID
                foreach ($feesPaid as $fee) {
                    $feesSummary[$fee['student_id']] = $fee;
                }

                $output = '<div style="max-height: 400px; overflow-y: auto;">';
                $output .= '<table class="table table-bordered table-responsive" id="fees-table">';
                $output .= '<thead >
                                <tr class="position-fixed">
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
                                    <th class="bg-grey-200">Action</th>
                                </tr>
                            </thead>';
                $output .= '<tbody>';

                foreach ($feesDetails as $fee) {
                    $studentId = $fee['student_id'];
                    $payments = $feesSummary[$studentId] ?? [
                        'total_book_fees' => 0,
                        'total_tuition_fees' => 0,
                        'total_transport_fees' => 0,
                    ];

                    $balanceBookFees = $fee['book_fees_amount'] - $payments['total_book_fees'];
                    $balanceTuitionFees = $fee['tuition_fees_amount'] - $payments['total_tuition_fees'];
                    $balanceTransportFees = $fee['transport_fees_amount'] - $payments['total_transport_fees'];
                    $total_balance_fees = $balanceBookFees + $balanceTuitionFees + $balanceTransportFees;

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
                    $output .= '<td>' . htmlspecialchars($total_balance_fees) . '</td>';
                    $output .= '<td class="text-nowrap">
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="' . htmlspecialchars($fee['student_id']) . '">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="' . htmlspecialchars($fee['fees_id']) . '">Delete</button>
                                </td>';
                    $output .= '</tr>';
                }

                $output .= '</tbody></table>';
                $output .= '</div>';

                echo $output;
            } else {
                echo '<div class="alert alert-warning" role="alert">No Students Found For The Selected Grade</div>';
            }
        } catch (PDOException $e) {
            echo 'Error fetching fees details: ' . $e->getMessage();
        }
    } else {
        echo 'No student IDs provided.';
    }
}
?>

<script>
$(document).ready(function() {
    // Handle Edit button click
    $(document).on('click', '.edit-btn', function() {
        var studentId = $(this).data('id');

        // Fetch the existing fee data for this student
        $.ajax({
            url: 'modules/students/EditFees.php', // Ensure this path is correct
            method: 'GET',
            data: { id: studentId },
            success: function(response) {
                var data = JSON.parse(response);

                // Populate the form with the existing data
                $('#student_id').val(data.student_id);
                $('#student_name').val(data.student_name);
                $('#academic_year').val(data.syear);  
                $('#book_fees_amount').val(data.book_fees_amount);
                $('#tuition_fees_amount').val(data.tuition_fees_amount);
                $('#transport_fees_amount').val(data.transport_fees_amount);

                // Show the edit form
                $('#edit-form').show();
                $('#fees-table').hide();
                $('#search-form').hide();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching data: ', error);
            }
        });
    });

    // Handle form submission (AJAX save)
    $('#edit-fees-form').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
        url: 'modules/students/EditFees.php', // Ensure this path is correct
        method: 'POST',
        data: formData,
        success: function(response) {
            alert('Fees updated successfully!');

            // Hide the edit form and show the updated table
            $('#edit-form').hide();

            // Refresh the fees table by reloading it
            $('#fees-table').load(location.href + " #fees-table");
            

            // Show the search form again
            $('#search-form').show();
            $('#searchStuBtn').click();
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
});


    // Cancel button click event
    $('#cancel-btn').on('click', function() {
        $('#edit-form').hide();
        $('#fees-table').show();
        $('#search-form').show();
    });

    // Handle Delete button click
    $(document).on('click', '.delete-btn', function() {
        var fees_id = $(this).data('id');

        if (confirm('Are you sure you want to delete this fee record?')) {
            $.ajax({
                url: 'modules/students/DeleteFees.php', // Ensure this path is correct
                method: 'POST',
                data: { id: fees_id },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert(result.message);
                        $('#row-' + fees_id).remove(); // Remove the row from the table
                    } else {
                        alert('Error: ' + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }
    });
});
</script>