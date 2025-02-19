<?php
PopTable('header', _report);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Call Management - Reports</title>
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
            gap: 10px;
            padding: 20px;
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
        }

        .display-6 {
            font-size: 24px;
            font-weight: bold;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
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
            <p class="display-6" id="totalCalls">250</p>
        </div>
        <div class="card">
            <h5 class="card-title">Missed Calls</h5>
            <p class="display-6 text-danger" id="missedCalls">45</p>
        </div>
        <div class="card">
            <h5 class="card-title">Answered Calls</h5>
            <p class="display-6 text-success" id="answeredCalls">180</p>
        </div>
        <div class="card">
            <h5 class="card-title">Call Duration (hrs)</h5>
            <p class="display-6" id="callDuration">12</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="card">
            <div class="card-header">Call Status Distribution</div>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Daily Call Trends</div>
            <div class="chart-container">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Call Logs Table -->
    <div class="card">
        <div class="card-header">Call Logs</div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Caller Name</th>
                        <th>Phone Number</th>
                        <th>Call Type</th>
                        <th>Status</th>
                        <th>Duration</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>+1 234 567 890</td>
                        <td>Inbound</td>
                        <td>Answered</td>
                        <td>5 min</td>
                        <td>2025-01-04</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith</td>
                        <td>+1 987 654 321</td>
                        <td>Outbound</td>
                        <td>Missed</td>
                        <td>0 min</td>
                        <td>2025-01-03</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js -->
    <script>
        setTimeout(() => {
            chartLoad();            
        }, 200);
        function chartLoad(){
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Answered', 'Missed', 'Voicemail'],
                datasets: [{
                    data: [180, 45, 25],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                }]
            },
        });

        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                datasets: [{
                    data: [30, 40, 25, 50, 35, 20, 50],
                    borderColor: '#007bff',
                    fill: false,
                }]
            },
        });

        }
    </script>
</body>

</html>
