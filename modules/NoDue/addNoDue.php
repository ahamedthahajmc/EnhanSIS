<?php
PopTable('header', _addNoDue);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<div class="form-horizontal m-b-0">

    <!-- Student Details Section -->
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Student ID</label>
                <div class="col-lg-8">
                    <input type="text" name="studentID" id="studentID" size="30" placeholder="Student ID" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Student Name</label>
                <div class="col-lg-8">
                    <input type="text" name="studentName" id="studentName" placeholder="Student Name" size="30" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Grade</label>
                <div class="col-lg-8">
                    <select class="form-control" id="grade">
                        <option selected>Choose Grade</option>
                        <option value="1">Grade 1</option>
                        <option value="2">Grade 2</option>
                        <option value="3">Grade 3</option>
                    </select>
                </div>
            </div>
        </div>
    </div><br>

    <!-- Fee Details Section -->

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Outstanding Fees</label>
                <div class="col-lg-8">
                    <input type="text" name="outstandingFees" id="outstandingFees" size="30" class="form-control" value="0" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Fine Amount</label>
                <div class="col-lg-8">
                    <input type="text" name="fine" size="30" class="form-control" id="fine" placeholder="Enter Fine Amount">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Total Due</label>
                <div class="col-lg-8">
                    <input type="text" name="totalDue" size="30" class="form-control" id="totalDue" value="0" readonly>
                </div>
            </div>
        </div>
    </div><br>
    <!-- No Due Confirmation Section -->
    <div class="card p-4">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="confirmNoDue">
            <label class="form-check-label" for="confirmNoDue">
                I confirm that there are no outstanding dues or fines for this student.
            </label>
        </div><hr>
        <div class="text-right">
            <button type="button" class="btn btn-primary" id="generateCertificate" disabled>Add No Due</button>
        </div>
    </div>


    <script>
        const fineInput = document.getElementById('fine');
        const outstandingFees = document.getElementById('outstandingFees');
        const totalDue = document.getElementById('totalDue');

        // Calculate Total Due dynamically
        fineInput.addEventListener('input', function() {
            const fine = parseFloat(fineInput.value) || 0;
            const outstanding = parseFloat(outstandingFees.value) || 0;
            totalDue.value = outstanding + fine;
        });

        // Enable button when checkbox is checked
        document.getElementById('confirmNoDue').addEventListener('change', function() {
            document.getElementById('generateCertificate').disabled = !this.checked;
        });
    </script>
</div>
</body>

</html>