<?php
PopTable('header', _callLogs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Logs - Call Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .content {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .search-bar input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="form-horizontal m-b-0">

    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search for Call ID or Category" onkeyup="searchCalls()">
    </div>

    <!-- Call Logs Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Call ID</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="callLogsTable">
                <tr>
                    <td>1</td>
                    <td>C1001</td>
                    <td>2025-01-02</td>
                    <td>Admission Enquiry</td>
                    <td>Received</td>
                    <td><button class="btn">View</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>C1002</td>
                    <td>2025-01-03</td>
                    <td>Fees Enquiry</td>
                    <td>Missed</td>
                    <td><button class="btn">View</button></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>C1003</td>
                    <td>2025-01-04</td>
                    <td>Course Enquiry</td>
                    <td>Attended</td>
                    <td><button class="btn">View</button></td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>


<script>
    function searchCalls() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.getElementById("callLogsTable").getElementsByTagName("tr");
        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let callId = cells[1].textContent.toLowerCase();
            let category = cells[3].textContent.toLowerCase();
            rows[i].style.display = (callId.includes(input) || category.includes(input)) ? "" : "none";
        }
    }

    // Attach click event to all "View" buttons
    document.addEventListener("DOMContentLoaded", function () {
        const viewButtons = document.querySelectorAll(".btn");
        
        viewButtons.forEach(button => {
            button.addEventListener("click", function () {
                // Get the Call ID from the corresponding row
                const callId = this.closest("tr").children[1].textContent;
                
                // Redirect to a detailed call log page with Call ID
                window.location.href = `ViewCallDetails.php?call_id=${callId}`;
            });
        });
    });

</script>
</div>
</body>
</html>
