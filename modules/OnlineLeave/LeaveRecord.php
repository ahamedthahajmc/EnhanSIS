<?php
PopTable('header', _leaverecord);
include('../../Data.php');

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM leave_application WHERE status IN ('Approved', 'Rejected') ORDER BY student_id DESC");
    $leave_application_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error retrieving data: ' . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        updateLeaveStatus($_POST['approve'], 'Approved');
    }
    if (isset($_POST['reject'])) {
        updateLeaveStatus($_POST['reject'], 'Rejected');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Record</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }

        .count-button {
            border: 1px solid #ccc;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .count-approved {
            background-color: rgb(41, 253, 87);
            color: black;
            border: none;
        }

        .count-rejected {
            background-color: #ff0000;
            color: white;
            border: none;
        }

        .count-all {
            background-color: #007BFF;
            color: white;
            border: none;
        }
        
        .download-btn {
            background-color: rgb(55, 7, 167);  
            color: white;                
            font-size: 16px;             
            padding: 8px 20px;          
            border: none;               
            border-radius: 5px;         
            cursor: pointer;            
            transition: background-color 0.3s ease; 
        }

        .download-options {
            display: none;
            top: 50px;
            right: 20px;
            background-color: white;
            padding: 20px;
            text-align: right;
        }

        .download-options button {
            background-color: #007BFF;
            color: white;
            font-size: 14px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .download-options button:hover {
            background-color: #0056b3;
        }

    </style>

<script>
    function filterByStatus(status) {
        var rows = document.querySelectorAll('table tbody tr');

        rows.forEach(function(row) {
            var rowStatus = row.classList.contains('status-' + status.toLowerCase());
            if (status === 'All') {
                row.style.display = '';  // Show all rows
            } else if (rowStatus) {
                row.style.display = '';  // Show row if it matches the status
            } else {
                row.style.display = 'none';  // Hide row if it doesn't match the status
            }
        });

    }
    
</script>
</head>
<body>

<h2 style="text-align:left; color: blue;">Leave History</h2>
<div class="status-counts">
    <button class="count-button count-approved" onclick="filterByStatus('Approved')">
        <i class="fa fa-check"></i> &nbsp; Approved 
    </button>
    <button class="count-button count-rejected" onclick="filterByStatus('Rejected')">
        <i class="fa fa-close"></i> &nbsp; Rejected 
    </button>
    <button class="count-button count-all" onclick="filterByStatus('All')">
        <i class="fa fa-list"></i> &nbsp; Show All 
    </button>
</div>
<br><br>
<div class="table-responsive">
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Student id</th>
            <th>Student Name</th>
            <th>Grade</th>
            <th>Section</th>
            <th>Leave Type</th>
            <th>Apply Date</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Approved/Rejected By</th>
            <th>Remark</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($leave_application_data as $index => $leave): ?>
        <tr class="status-<?php echo strtolower($leave['status']); ?>">
            <td><?php echo $index + 1; ?></td>
            <td><?php echo htmlspecialchars($leave['student_id']); ?></td>
            <td><?php echo htmlspecialchars($leave['last_name']); ?> . <?php echo htmlspecialchars($leave['first_name']); ?></td>
            <td><?php echo htmlspecialchars($leave['grade']); ?></td>
            <td><?php echo htmlspecialchars($leave['section']); ?></td>
            <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
            <td><?php echo htmlspecialchars($leave['apply_date']); ?></td>
            <td><?php echo htmlspecialchars($leave['start_date']); ?></td>
            <td><?php echo htmlspecialchars($leave['end_date']); ?></td>
            <td><?php echo htmlspecialchars($leave['reason']); ?></td>
            <td><?php echo htmlspecialchars($leave['status']); ?></td>
            <td><?php echo htmlspecialchars($leave['Approved/Rejected By']); ?></td>
            <td><?php echo htmlspecialchars($leave['Remark']); ?></td>

        </tr>
    <?php endforeach; ?>
</tbody>
</table>
    </div>
    <br><br>

<div class="text-right">
    <button class="download-btn">Download</button>
</div>
<div class="download-options">
    <button onclick="downloadCSV()"> CSV</button>
    <button onclick="downloadPDF()"> PDF</button>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

<script>
    document.querySelector('.download-btn').addEventListener('click', function() {
        var downloadOptions = document.querySelector('.download-options');
        downloadOptions.style.display = downloadOptions.style.display === 'block' ? 'none' : 'block';
    });

    function downloadCSV() {
    var table = document.querySelector('table');
    var rows = table.querySelectorAll('tr');
    var csv = [];

    rows.forEach(function(row) {
        if (row.style.display !== 'none') {  // Include only visible rows
            var cells = row.querySelectorAll('td, th');
            var rowData = [];
            cells.forEach(function(cell) {
                rowData.push('"' + cell.innerText.replace(/"/g, '""') + '"');
            });
            csv.push(rowData.join(','));
        }
    });

    var csvData = csv.join('\n');
    var blob = new Blob([csvData], { type: 'text/csv' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'visible_leave_applications.csv';
    link.click();
}

function downloadPDF() {
    var doc = new jsPDF();
    var table = document.querySelector('table');
    var rows = table.querySelectorAll('tr');

    var content = [];
    rows.forEach(function(row) {
        if (row.style.display !== 'none') {  // Include only visible rows
            var cells = row.querySelectorAll('td, th');
            var rowData = [];
            cells.forEach(function(cell) {
                rowData.push(cell.innerText);
            });
            content.push(rowData);
        }
    });

    doc.autoTable({
        head: content.slice(0, 1),
        body: content.slice(1),
    });

    doc.save('visible_leave_applications.pdf');
}
</script>
</body>
</html>
