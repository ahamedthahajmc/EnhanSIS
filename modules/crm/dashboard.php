<?php
PopTable('header', _dashboard);

// Dynamic data from the database (example values provided here)
$totalCalls = 1200;
$attendedCalls = 1000;
$missedCalls = 200;
$admissionInquiries = 300;
$feeInquiries = 400;
$courseInquiries = 500;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Management - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 200px;
        }

        .card-title {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .display-6 {
            font-size: 24px;
            font-weight: bold;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .card-header {
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #007bff;
            color: white;
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<!-- Summary Cards -->
<div class="row">
    <div class="card">
        <h5 class="card-title">Total Calls</h5>
        <p class="display-6"><?php echo $totalCalls; ?> Calls</p>
    </div>
    <div class="card">
        <h5 class="card-title">Attended Calls</h5>
        <p class="display-6 text-success"><?php echo $attendedCalls; ?> Calls</p>
    </div>
    <div class="card">
        <h5 class="card-title">Missed Calls</h5>
        <p class="display-6 text-danger"><?php echo $missedCalls; ?> Calls</p>
    </div>
    <div class="card">
        <h5 class="card-title">Enquiries</h5>
        <p class="card-text">
            Admission: <?php echo $admissionInquiries; ?><br>
            Fee: <?php echo $feeInquiries; ?><br>
            Course: <?php echo $courseInquiries; ?>
        </p>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <div class="card">
        <div class="card-header">Call Statistics</div>
        <div class="chart-container">
            <canvas id="callChart"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Enquiry Distribution</div>
        <div class="chart-container">
            <canvas id="inquiryChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Calls Table -->
<h4>Recent Calls</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Call ID</th>
            <th>Status</th>
            <th>Category</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>#101</td>
            <td>Attended</td>
            <td>Admission</td>
            <td>2025-01-03</td>
        </tr>
        <tr>
            <td>2</td>
            <td>#102</td>
            <td>Missed</td>
            <td>Fee</td>
            <td>2025-01-03</td>
        </tr>
        <tr>
            <td>3</td>
            <td>#103</td>
            <td>Attended</td>
            <td>Course</td>
            <td>2025-01-02</td>
        </tr>
    </tbody>
</table>

<script>
setTimeout(() => {
    chartLoad();    
}, 200);    
    function chartLoad(){
    const ctx = document.getElementById('callChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Calls', 'Attended Calls', 'Missed Calls'],
            datasets: [{
                label: 'Calls',
                data: [<?php echo $totalCalls; ?>, <?php echo $attendedCalls; ?>, <?php echo $missedCalls; ?>],
                backgroundColor: ['#4caf50', '#2196f3', '#f44336'],
                borderColor: ['#388e3c', '#1976d2', '#d32f2f'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Inquiry Distribution Chart
    const ctx2 = document.getElementById('inquiryChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Admission', 'Fee', 'Course'],
            datasets: [{
                label: 'Inquiries',
                data: [<?php echo $admissionInquiries; ?>, <?php echo $feeInquiries; ?>, <?php echo $courseInquiries; ?>],
                backgroundColor: ['#ffeb3b', '#ff9800', '#8bc34a'],
                borderColor: ['#fbc02d', '#f57c00', '#388e3c'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
    }
</script>

</body>
</html>