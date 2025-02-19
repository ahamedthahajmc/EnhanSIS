<?php

PopTable('header', _salary_slip);

$staffs = DBGet(DBQuery('SELECT * FROM staff'));
// print_r($staffs);
// die;
$staff_val = array();
// if (!empty($staffs)) {
foreach ($staffs as $row) {
    // if (!empty($row['FIRST_NAME'])) { // Check if FIRST_NAME is not empty
    array_push($staff_val, $row);
    //     }
    // }
}

?>
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

<div id="salaryslip-cont" style="display: none;">
    <div id="salaryslip-content">
        <h1>Loading...</h1>
    </div>
</div>

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
            $('#salaryslip-cont').show();

            // Send AJAX request
            $.ajax({
                url: 'modules/hr/FetchSalarySlip.php?idval=' + idVal, // The server-side script to handle the request
                type: 'POST',
                data: {
                    staff: staff,
                    year: year,
                    month: month
                },
                success: function(response) {
                    // Update the salary slip container with the response
                    $('#salaryslip-content').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    $('#salaryslip-content').html('<p>An error occurred while fetching the salary slip.</p>');
                    console.error(xhr.responseText);
                }
            });
        });
        $('#cancel-btn').on('click', function(event) {
            event.preventDefault();
            $('#salaryslip-content').html('');
            $('#staffSelect').val("");
            $('#yearSelect').val("");
            $('#monthSelect').val("");
        })
    });
</script>