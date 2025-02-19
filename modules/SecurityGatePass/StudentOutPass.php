<?php
PopTable('header', _studentOutPass);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Out Pass</title>
</head>

<body>
    <!-- Student Out Pass Form -->
    <div class="form-horizontal m-b-0">

    <form>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Student ID</label>
                    <div class="col-lg-8">
                        <input type="text" name="studentId" size="30" placeholder="Student Id" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Student Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="student_Name" placeholder="Student Name" size="30" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Reason for Leaving</label>
                    <div class="col-lg-8">
                        <input type="text" name="outReason" size="30" placeholder="Enter Reason" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Time Out</label>
                    <div class="col-lg-8">
                        <input type="time" name="timeOut" size="30" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Guardian Contact</label>
                    <div class="col-lg-8">
                        <input type="text" name="guardianContact" size="30" placeholder="Enter Guardian Contact" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Approved By</label>
                    <div class="col-lg-8">
                        <input type="text" name="approvedBy" size="30" class="form-control" placeholder="Enter Name of Approver" required>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>

    <!-- Out Pass Records -->
    <hr>
    <div class="card">
        <div class="card-header bg-secondary text-white">
            Out Pass Records
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Reason</th>
                        <th>Time Out</th>
                        <th>Guardian Contact</th>
                        <th>Approved By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>101</td>
                        <td>Alice Johnson</td>
                        <td>Medical</td>
                        <td>10:00 AM</td>
                        <td>123-456-7890</td>
                        <td>Mr. Smith</td>
                        <td>
                            <button class="btn btn-primary btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>102</td>
                        <td>Bob Williams</td>
                        <td>Family Emergency</td>
                        <td>11:30 AM</td>
                        <td>987-654-3210</td>
                        <td>Ms. Davis</td>
                        <td>
                            <button class="btn btn-primary btn-sm">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
