<?php
PopTable('header', _attendance);
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
$now = date("Y-m-d");
$staff_list = DBGet(DBQuery('SELECT * FROM staff'));


$staff_val = array();
foreach ($staff_list as $row) {
    array_push($staff_val, $row);
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

        .resize_none {
            resize: none;
        }

        .ovr-x {
            overflow-x: scroll;
        }

        .present-clr {
            background-color: #78f578;
        }

        .absent-clr {
            background-color: #dc3545;
        }

        .tardy-clr {
            background-color: #ffc107;
        }

        .partial-clr {
            background-color: #007bff;
        }

        .early-leave-clr {
            background-color: #fd7e14;
        }

        .others-clr {
            background-color: #6f42c1;
        }

        /* Webkit-based browsers (Chrome, Edge, Safari) */
        ::-webkit-scrollbar {
            width: 8px;
            /* Adjust width */
            height: 8px;
            /* Adjust height for horizontal scrollbar */
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            /* Color of the scrollbar */
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }
        .sm-box{
            width: 12px;
            height: 12px;
        }
        .flex-align-center{
            display: flex;
            align-items: center;
        }
        
    </style>
</head>

<body>
    <br>
    <div class="form-horizontal m-b-0">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Select Staff</label>
                    <div class="col-lg-8">
                        <SELECT name='groups' id='staffSelect' class="form-control" onchange=changeFunc(this.value)>
                            <OPTION value=''> Select Staff</OPTION>
                            <?php
                            foreach ($staff_val as $name) {
                                echo '<OPTION value="' . htmlspecialchars($name['STAFF_ID']) . '">' . htmlspecialchars($name['FIRST_NAME'] . ' ' . $name['LAST_NAME']) . '</OPTION>';
                            }
                            ?>
                        </SELECT>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Select Year</label>
                    <div class="col-lg-8">
                        <SELECT name='year' id='yearSelect' class="form-control">
                            <OPTION value=''> Select Year</OPTION>
                            <OPTION value='2024'> 2024</OPTION>
                            <OPTION value='2023'> 2023</OPTION>
                            <OPTION value='2022'> 2022</OPTION>
                        </SELECT>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Select Month</label>
                    <div class="col-lg-8">
                        <SELECT name='date' id='monthSelect' class="form-control">

                            <OPTION value=''> Select Month</OPTION>
                            <OPTION value='jan'> January</OPTION>
                            <OPTION value='feb'> February</OPTION>
                            <OPTION value='mar'> March</OPTION>
                        </SELECT>
                    </div>
                </div>
            </div>
            <div class='text-right '><INPUT type=SUBMIT id='submit-btn' class='btn btn-primary m-t-20' value='SUBMIT'>&nbsp; <INPUT type=RESET id='cancel-btn' class='btn btn-default m-t-20' value='RESET'></div>
        </div>
    </div>
    <br>

    <div id="attend-details" style="display: none;">
        <div id="attend-details-content">
            <h1>Loading...</h1>
        </div>
    </div>
</body>
<script>
    var idVal = "";

    function changeFunc(val) {
        idVal = val;
    }

    $(document).ready(function() {
        $('#submit-btn').on('click', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get selected values
            const staff = $('#staffSelect').val();
            const year = $('#yearSelect').val();
            const month = $('#monthSelect').val();

            // Validate inputs
            if (!staff || !year || !month) {
                alert('Please select all fields!');
                return;
            }

            // Display the container
            $('#attend-details').show();

            // Send AJAX request
            $.ajax({
                url: 'modules/hr/AttendanceData.php?idval=' + idVal, // The server-side script to handle the request
                type: 'POST',
                data: {
                    staff: staff,
                    // year: year,
                    // month: month
                },
                success: function(response) {
                    // Update the salary slip container with the response
                    $('#attend-details-content').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    $('#attend-details-content').html('<p>An error occurred while fetching the salary slip.</p>');
                    console.error(xhr.responseText);
                }
            });
        });
        $('#cancel-btn').on('click', function(event) {
            event.preventDefault();
            $('#attend-details-content').html('');
            $('#staffSelect').val("");
            $('#yearSelect').val("");
            $('#monthSelect').val("");
        })
    });
</script>

</html>