<?php
PopTable('header', _noDue);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Due Module</title>
</head>

<body>
    <!-- Student Search -->
    <div class="form-horizontal m-b-0">
    <form id="searchForm">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Student ID</label>
                    <div class="col-lg-8">
                        <input type="text" name="studentID" id="studentID" size="30" placeholder="Student ID" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Student Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="studentName" id="studentName" placeholder="Student Name" size="30" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="text-right">
            <button type="button" class="btn btn-primary" id="searchButton">Search</button>
        </div>
    </form>

    <!-- Display Student Details (Read-Only) -->
    <div class="mt-4" id="studentDetails" style="display: none;">
        <h5>Student Details</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="totalFees" class="form-label">Total Fees</label>
                <input type="text" class="form-control" id="totalFees" readonly>
            </div>
            <div class="col-md-4">
                <label for="amountPaid" class="form-label">Amount Paid</label>
                <input type="text" class="form-control" id="amountPaid" readonly>
            </div>
            <div class="col-md-4">
                <label for="balance" class="form-label">Balance</label>
                <input type="text" class="form-control" id="balance" readonly>
            </div>
        </div><br>


        <div class="row mb-3">
            <div class="col-md-6">
                <label for="depositAmount" class="form-label">Deposit Amount</label>
                <input type="text" class="form-control" id="depositAmount" readonly>
            </div>
            <div class="col-md-6">
                <label for="fineType" class="form-label">Type of Fine</label>
                <input type="text" class="form-control" id="fineType" readonly>
            </div>
        </div><br>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fineAmount" class="form-label">Fine Amount</label>
                <input type="text" class="form-control" id="fineAmount" readonly>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#searchButton').click(function() {
                // Simulate fetching data (replace with actual AJAX call)
                const studentID = $('#studentID').val();
                const studentName = $('#studentName').val();

                if (studentID || studentName) {
                    // Show student details (dummy data for now)
                    $('#totalFees').val('10000');
                    $('#amountPaid').val('7000');
                    $('#balance').val('3000');
                    $('#depositAmount').val('2000');
                    $('#fineType').val('Library');
                    $('#fineAmount').val('500');

                    // Display the details section
                    $('#studentDetails').show();
                } else {
                    alert('Please enter either Student ID or Student Name to search.');
                }
            });
        });
    </script>
    </div>
</body>

</html>