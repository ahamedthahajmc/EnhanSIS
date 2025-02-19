<?php
PopTable('header', _leaverequestlist);
include('../../Data.php');

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM leave_application WHERE status = 'pending' ORDER BY id DESC");
    $leave_application_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error retrieving data: ' . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Request Form</title>
    <style>
        .error {color: #FF0000;}

        /* Container for the form */
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }


        button[type="button"] {
            background-color:rgb(227, 231, 233);
            color: black;
            padding: 8px 10px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        select, textarea, input {
            width: 30%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1 style="color: blue;">Students Leave Request List:</h1>
    <form id="form_container" method="POST">
    <input type="hidden" id="processed_ids" value="">

    <div class="form-horizontal m-b-0">
    <div class="table-responsive">
    <table id="request">
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
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leave_application_data as $index => $leave): ?>
                <tr id="row_<?php echo $leave['id']; ?>" class="status-<?php echo strtolower($leave['status']); ?>">
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
                    <td>
                        <input id="remark_<?php echo $leave['id']; ?>" class="form-control" type="text" name="remark" placeholder="Remark" required>
                    </td>
                    <td>
                        <button type="button" class="approve-btn" data-id="<?php echo $leave['id']; ?>" data-action="approved">Approved</button>
                        <button type="button" class="reject-btn" data-id="<?php echo $leave['id']; ?>" data-action="rejected">Rejected</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    </div>
    <br><br>
</form>

<script>
    document.querySelectorAll('.approve-btn, .reject-btn').forEach(button => {
    button.addEventListener('click', function() {
        var leaveId = this.getAttribute('data-id');
        var action = this.getAttribute('data-action');
        var remarkField = document.getElementById('remark_' + leaveId);
        var remark = remarkField ? remarkField.value : '';

        if (!remark) {
            alert('Please enter a remark.');
            return;
        }

        var processedIds = document.getElementById('processed_ids').value.split(',');

        // Check if the ID has been processed
        if (processedIds.includes(leaveId)) {
            alert('This request has already been processed.');
            return;
        }

        var formData = new FormData();
        formData.append('id', leaveId);
        formData.append('action', action);
        formData.append('remark', remark);

        fetch('modules/onlineleave/Request.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            console.log('Server response:', text);
            try {
                var data = JSON.parse(text);
                if (data.status === 'success') {
                    var row = document.getElementById('row_' + leaveId);
                    row.style.display = 'none';

                    // Add the processed ID to the hidden field
                    processedIds.push(leaveId);
                    document.getElementById('processed_ids').value = processedIds.join(',');
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Parsing error:', error);
                alert('Error: Invalid JSON response');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error: ' + error.message);
        });
    });
});

</script>
</body>
</html>
