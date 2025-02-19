<?php
PopTable('header', _report);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .card {
            margin-top: 20px;
        }

        table {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Class Selection Section -->

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Grade</label>
                <div class="col-lg-8">
                    <select class="form-control" id="grade">
                        <option selected disabled>Choose Grade</option>
                        <option value="1">Grade 1</option>
                        <option value="2">Grade 2</option>
                        <option value="3">Grade 3</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label text-right col-lg-4">Section</label>
                <div class="col-lg-8">
                    <select class="form-control" id="section">
                        <option selected disabled>Choose Section</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                        <option value="C">Section C</option>
                    </select>
                </div>
            </div>
        </div>
    </div><br>
    <div class="text-right mt-3">
        <button type="button" class="btn btn-primary" id="viewReport">View Report</button>
    </div>
    </div><hr>

    <!-- No Due Report Table -->
    <div class="card p-4">
        <h5>No Due Report</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Grade</th>
                    <th>Section</th>
                    <th>Outstanding Fees</th>
                    <th>Total Due</th>
                    <th>No Due Status</th>
                </tr>
            </thead>
            <tbody id="reportBody">
                <!-- Rows will be dynamically added -->
                <tr>
                    <td>101</td>
                    <td>John Doe</td>
                    <td>Grade 1</td>
                    <td>A</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="text-success">Cleared</td>
                </tr>
                <tr>
                    <td>102</td>
                    <td>Jane Smith</td>
                    <td>Grade 1</td>
                    <td>A</td>
                    <td>500</td>
                    <td>500</td>
                    <td class="text-danger">Pending</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        // Placeholder script for "View Report" button
        document.getElementById('viewReport').addEventListener('click', function() {
            alert('Report will be generated based on the selected grade and section.');
            // Implement logic here to fetch and display data
        });
    </script>
</body>

</html>