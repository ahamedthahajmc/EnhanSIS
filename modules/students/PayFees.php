<?php
include("feesdb.php");
PopTable('header',_payfees); 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
</head>

<body>
    <form action="PaymentSummary.php" method="POST">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="studentName">Student Name</label>
                <input type="text" class="form-control" id="studentName" name="studentName" placeholder="Enter student name" required>
            </div>
            <div class="form-group col-md-6">
                <label for="studentId">Student ID</label>
                <input type="text" class="form-control" id="studentId" name="studentId" placeholder="Enter student ID" required>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="feesType">Fees Type</label>
            <select class="form-control" id="feesType" name="feesType" required>
                <option value="">Select Fee Type</option>
                <option value="tuition">Tuition Fee</option>
                <option value="book">Book Fee</option>
                <option value="bus">Bus Fee</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
        </div>
        <div class="form-group col-md-6">
            <label for="paymentDate">Payment Date</label>
            <input type="date" class="form-control" id="paymentDate" name="paymentDate" required>
        </div>
       
        <div class="text-right">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Clear</button>
        </div>
    </form>
</body>

</html>
<?php

PopTable('footer')
?>