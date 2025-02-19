<?php
PopTable('header', _leavemanagement);
include('../../Data.php');

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM leave_management ORDER BY id DESC");
    $leave_manage_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Bus Transport Fees</title>
    <style>
        .form-group {
            position: unset;
        }

        .wid-border {
            border-width: 1px 1px;
        }

        .border-ddd {
            border-color: #ddd;
        }

        .border-ddd:focus {
            border-color: #ddd;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(221, 221, 221, 0.6);
        }
    </style>
</head>

<body>


    <div class="text-right mb-3">
        <button id="addNew" class="btn btn-primary">Add New</button>
    </div>
    <br>

    <table id="typeAddTable" class="table table-bordered">
        <thead>
            <tr class="bg-grey-200">
                <th>Academic Year</th>
                <th>User Role</th>
                <th>Leave Type</th>
                <th>Total Credits</th>
                <th>Notes</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leave_manage_data as $row): ?>
                <tr data-id="<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_role']); ?></td>
                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_credits']); ?></td>
                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    <td class="text-center">
                        <button class="btn btn-info btn-sm editBtn">Edit</button>
                        <button class="btn btn-warning btn-sm dltBtn">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Form for Add/Edit -->
    <div id="formContainer" style="display: none;">
        <!-- <h3 id="formTitle">Add Transport Fees</h3> -->
        <form id="leaveMangeForm">
            <div class="form-horizontal m-b-0">
                <input type="hidden" id="recordId" name="id" value="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-right col-lg-4">Academic Year</label>
                            <div class="col-lg-8">
                                <SELECT name='acd_year' id='academic_yr' class="form-control">
                                    <OPTION value=''> Academic Year</OPTION>
                                    <OPTION value='2023'> 2022-2023</OPTION>
                                    <OPTION value='2024'> 2023-2024</OPTION>
                                    <OPTION value='2025'> 2024-2025</OPTION>
                                    <?php
                                    // foreach ($staff_val as $name) {
                                    //     echo '<OPTION value="' . htmlspecialchars($name['STAFF_ID']) . '">' . htmlspecialchars($name['FIRST_NAME'] . ' ' . $name['LAST_NAME']) . '</OPTION>';
                                    // }
                                    ?>
                                </SELECT>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-right col-lg-4">User Role</label>
                            <div class="col-lg-8">
                                <SELECT name='usr_role' id='roleSelect' class="form-control">
                                    <OPTION value=''> User Role</OPTION>
                                    <OPTION value='2024'> 2024</OPTION>
                                    <OPTION value='2023'> 2023</OPTION>
                                    <OPTION value='2022'> 2022</OPTION>
                                </SELECT>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-right col-lg-4">Leave Type</label>
                            <div class="col-lg-8">
                                <SELECT name='leave_typ' id='typeSelect' class="form-control">
                                    <OPTION value=''> Leave Type</OPTION>
                                    <OPTION value='sickLeave'> Sick Leave</OPTION>
                                    <OPTION value='earnedLeave'> Earned Leave</OPTION>
                                    <OPTION value='casualLeave'> Casual Leave</OPTION>
                                    <?php
                                    // foreach ($staff_val as $name) {
                                    //     echo '<OPTION value="' . htmlspecialchars($name['STAFF_ID']) . '">' . htmlspecialchars($name['FIRST_NAME'] . ' ' . $name['LAST_NAME']) . '</OPTION>';
                                    // }
                                    ?>
                                </SELECT>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-right col-lg-4">Total Credits:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="tot_credits" name="tot_credits" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label text-right col-lg-2">Notes:</label>
                            <div class="col-lg-10">
                                <textarea name="leave_notes" class="form-control wid-border border-ddd p-r-15 p-l-15" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                        </div>

                    </div>

                </div>
                <div class='text-right '>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Add</button>
                        <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#addNew').on('click', function() {
                $('#formContainer').show();
                $('#typeAddTable').hide();
                $('#addNew').hide();
                $('#formTitle').text('Add Leave Type');
                $('#submitBtn').text('Add');
                $('#recordId').val('');
                $('#academic_yr').val('');
                $('#roleSelect').val('');
                $('#typeSelect').val('');
                $('#tot_credits').val('');
                $('textarea#exampleFormControlTextarea1').val('');
                $('#errorMessages').hide();
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
            });

            $('#leaveMangeForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: 'modules/hr/AddLeaveType.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);

                            // Hide the form & show the table again
                            $('#formContainer').hide();
                            $('#typeAddTable').show();
                            $('#addNew').show();
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");

                            // Dynamically update the table without reloading the page
                            let newRow = `
                            <tr data-id="${response.new_id}">
                                <td>${$('#academic_yr').find(":selected").val()}</td>
                                <td>${$('#roleSelect').find(":selected").val()}</td>
                                <td>${$('#typeSelect').find(":selected").val()}</td>
                                <td>${$('#tot_credits').val()}</td>
                                <td>${$('textarea#exampleFormControlTextarea1').val()}</td>
                                <td class="text-center">                                    
                                <button class="btn btn-info btn-sm editBtn">Edit</button>
                                <button class="btn btn-warning btn-sm dltBtn">Delete</button>
                                </td>
                            </tr>
                `;

                            if ($('#recordId').val() === '') {
                                // If adding a new record, append it to the table
                                $('#typeAddTable tbody').prepend(newRow);
                            } else {
                                console.log("editing");
                                // If updating an existing record, update that row
                                let row = $('tr[data-id="' + $('#recordId').val() + '"]');
                                row.find('td').eq(0).text($('#academic_yr').val());
                                row.find('td').eq(1).text($('#roleSelect').val());
                                row.find('td').eq(2).text($('#typeSelect').val());
                                row.find('td').eq(3).text($('#tot_credits').val());
                                row.find('td').eq(4).text($('textarea#exampleFormControlTextarea1').val());
                            }

                            // Clear the form fields
                            $('#recordId').val('');
                            $('#academic_yr').val('');
                            $('#roleSelect').val('');
                            $('#typeSelect').val('');
                            $('#tot_credits').val('');
                            $('textarea#exampleFormControlTextarea1').val('');
                        } else {
                            $('#errorMessages').show().html(response.errors ? response.errors.join('<br>') : response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#errorMessages').show().text('Error: Unable to process request.');
                    }
                });
            });

            $('#typeAddTable').on('click', '.dltBtn', function(event) {
                event.preventDefault(); // Prevent default form submission

                const row = $(this).closest('tr');
                $.ajax({
                    url: 'modules/hr/AddLeaveType.php?id=' + row.data('id'),
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Remove the row from the table dynamically
                            row.remove();
                        } else {
                            $('#errorMessages').show().html(response.errors ? response.errors.join('<br>') : response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#errorMessages').show().text('Error: Unable to process request.');
                    }
                });
            });

            $('#typeAddTable').on('click', '.editBtn', function() {
                const row = $(this).closest('tr');
                $('#recordId').val(row.data('id'));
                $('#academic_yr').val(row.find('td').eq(0).text());
                $('#roleSelect').val(row.find('td').eq(1).text());
                $('#typeSelect').val(row.find('td').eq(2).text());
                $('#tot_credits').val(row.find('td').eq(3).text());
                $('textarea#exampleFormControlTextarea1').val(row.find('td').eq(4).text());
                $('#formTitle').text('Edit Bus Route & Transport Fees');
                $('#submitBtn').text('Update');
                $('#formContainer').show();
                $('#typeAddTable').hide();
                $('#addNew').hide();
                $('#errorMessages').hide();
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
            });

            $('#cancelBtn').on('click', function() {
                $('#formContainer').hide(); // Hide the form
                $('#academic_yr').val('');
                $('#roleSelect').val('');
                $('#typeSelect').val('');
                $('#tot_credits').val('');
                $('#exampleFormControlTextarea1').val('');
                $('#typeAddTable').show(); // Show the table
                $('#addNew').show(); // Show the "Add New" button               
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
            });

            $('#submit').on('click', function() {
                location.reload();
            });
        });
    </script>

</body>

</html>