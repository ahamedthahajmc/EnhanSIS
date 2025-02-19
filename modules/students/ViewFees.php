<?php
PopTable('header', _viewfees);
?>

<form id="search-form" method="POST">
    <div class="form-horizontal m-b-0">
        <!-- Grade Dropdown -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Grade</label>
                    <div class="col-lg-8">
                        <select name="grade" id="grade" class="form-control">
                            <option value="">Select Grade</option>
                            <?php
                            $gradeList = DBGet(DBQuery("SELECT DISTINCT TITLE, ID, SORT_ORDER FROM institute_gradelevels WHERE INSTITUTE_ID='" . UserInstitute() . "' ORDER BY SORT_ORDER"));
                            foreach ($gradeList as $grade) {
                                echo '<option value="' . $grade['ID'] . '">' . $grade['TITLE'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section Dropdown (Hidden Initially) -->
            <div class="col-md-6" id="section-container" style="display:none;">
                <div class="form-group">
                    <label class="control-label text-right col-lg-4">Section</label>
                    <div class="col-lg-8">
                        <select id="section" name="section" class="form-control">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" id="year" name="year" value="<?php echo UserSyear(); ?>"> <!-- Current year -->

        <hr/>
        <div class="text-right">
            <input id="searchStuBtn" type="submit" name="search" class="btn btn-primary m-r-10" value="Search">
            <input type="reset" id="reset-btn" class="btn btn-default" value="Reset">
        </div>
    </div>
</form>

<!-- Edit Fees Form (Initially Hidden) -->
<div id="edit-form" style="display:none;">
    <h6 style="color: red;">EDIT FEES</h6>
    <hr>
    <form id="edit-fees-form">
        <table class="table table-bordered table-responsive">
            <tbody>
                <!-- Fields for editing fees -->
                <tr><td>Student Id</td><td>Student Name</td><td>Academic Year</td></tr>
                <tr><td><input type="text" id="student_id" name="student_id" class="form-control" readonly></td>
                <td><input type="text" id="student_name" name="student_name" class="form-control" readonly></td>
                <td><input type="text" id="academic_year" name="academic_year" class="form-control" readonly></td></tr>
            </tbody>
        </table><br>
        <table class="table table-bordered table-responsive">
            <tbody>
                <tr>
                    <th>Fee Type</th>
                    <th>Actual Amount</th>
                    <th>Discount</th>
                    <th>Final Amount</th>
                </tr>
                <tr><td>Book Fees</td><td><input type="text" id="book_fees_amount" name="book_fees_amount" class="form-control" readonly></td><td><input type="text" id="discounted_book_fees" name="discounted_book_fees" class="form-control"></td><td><input type="text" id="final_book_fees" name="final_book_fees" class="form-control" readonly></td></tr>
                <tr><td>Tuition Fees</td><td><input type="text" id="tuition_fees_amount" name="tuition_fees_amount" class="form-control" readonly></td><td><input type="text" id="discounted_tuition_fees" name="discounted_tuition_fees" class="form-control"></td><td><input type="text" id="final_tuition_fees" name="final_tuition_fees" class="form-control" readonly></td></tr>
                <tr><td>Transport Fees</td><td><input type="text" id="transport_fees_amount" name="transport_fees_amount" class="form-control" readonly></td><td><input type="text" id="discounted_transport_fees" name="discounted_transport_fees" class="form-control"></td><td><input type="text" id="final_transport_fees" name="final_transport_fees" class="form-control" readonly></td></tr>
            </tbody>
        </table><br>
        <div class="text-right">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" id="cancel-btn" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
</div>

<div id="fees-table" style="display:none;"></div> <!-- Table for fees (Initially hidden) -->
<div id="alert-container" style="display:none;"></div> <!-- Alerts for feedback -->

<script>
$(document).ready(function () {
    // Prevent multiple event bindings
    $(document).off('change', '#grade').on('change', '#grade', function () {
        var gradeId = $(this).val();
        $('#section-container').hide();
        if (gradeId) {
            $.ajax({
                type: 'POST',
                url: 'modules/students/get_sections.php',
                data: { grade_id: gradeId },
                success: function (response) {
                    if (response.trim()) {
                        $('#section').html(response);
                        $('#section-container').show();
                    } else {
                        $('#section-container').hide();
                    }
                },
            });
        } else {
            $('#section-container').hide();
        }
    });

    // Search form submission
    $('#search-form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var selectedGrade = $('#grade').val();
        var selectedSection = $('#section').val();
        var year = $('#year').val();
        $.ajax({
            url: 'modules/students/searchGrade.php',
            method: 'POST',
            data: { action: 'search_by_grade', grade: selectedGrade, section: selectedSection, year: year },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    fetchFeesDetails(data.student_ids);
                } else {
                    showMessage(data.message, 'error');
                }
            },
        });
    });

    // Fetch fees details
    function fetchFeesDetails(studentIds) {
        $.ajax({
            url: 'modules/students/fetchFees.php',
            method: 'POST',
            data: { student_ids: studentIds.join(',') },
            success: function (response) {
                $('#fees-table').html(response).show();
            },
        });
    }

    // Calculate final fees
    $('input').off('input').on('input', function () {
        calculateFinalFees();
    });

    function calculateFinalFees() {
        const bookFees = parseFloat($('#book_fees_amount').val()) || 0;
        const bookDiscount = parseFloat($('#discounted_book_fees').val()) || 0;
        $('#final_book_fees').val(bookFees - bookDiscount);

        const tuitionFees = parseFloat($('#tuition_fees_amount').val()) || 0;
        const tuitionDiscount = parseFloat($('#discounted_tuition_fees').val()) || 0;
        $('#final_tuition_fees').val(tuitionFees - tuitionDiscount);

        const transportFees = parseFloat($('#transport_fees_amount').val()) || 0;
        const transportDiscount = parseFloat($('#discounted_transport_fees').val()) || 0;
        $('#final_transport_fees').val(transportFees - transportDiscount);
    }

    // Show message
    function showMessage(message, type) {
        const alertType = type === 'success' ? 'alert-success' : 'alert-danger';
        $('#alert-container')
            .html(`<div class="alert ${alertType}">${message}</div>`)
            .fadeIn()
            .delay(3000)
            .fadeOut();
    }

    // Reset button
    $('#reset-btn').off('click').on('click', function () {
        $('#edit-form').hide();
        $('#fees-table').hide();
        $('#search-form').show();
    });

    // Cancel button
    $('#cancel-btn').off('click').on('click', function () {
        $('#edit-form').hide();
        $('#fees-table').show();
    });

    // Edit button
    $(document).off('click', '.edit-btn').on('click', '.edit-btn', function () {
        var studentId = $(this).data('id');
        $.ajax({
            url: 'modules/students/EditFees.php',
            method: 'GET',
            data: { id: studentId },
            success: function (response) {
                var data = JSON.parse(response);
                $('#student_id').val(data.student_id);
                $('#student_name').val(data.student_name);
                $('#academic_year').val(data.syear);
                $('#book_fees_amount').val(data.book_fees_amount);
                $('#tuition_fees_amount').val(data.tuition_fees_amount);
                $('#transport_fees_amount').val(data.transport_fees_amount);
                $('#edit-form').show();
                $('#fees-table').hide();
                $('#search-form').hide();
            },
        });
    });

    // Save edited fees
    $('#edit-fees-form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');
        $submitButton.prop('disabled', true);

        $.ajax({
            url: 'modules/students/EditFees.php',
            method: 'POST',
            data: $form.serialize(),
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    showMessage(data.message, 'success');
                    $('#edit-form').hide();
                    $('#fees-table').show();
                    $('#search-form').show();
                    $('#fees-table').load(location.href + " #fees-table");
                } else {
                    showMessage(data.message, 'error');
                }
                $submitButton.prop('disabled', false);
            },
            error: function (xhr, status, error) {
                showMessage('Error: ' + error, 'error');
                $submitButton.prop('disabled', false);
            }
        });
    });

    // Delete button
    $(document).off('click', '.delete-btn').on('click', '.delete-btn', function () {
        var feesId = $(this).data('id');
        if (confirm('Are you sure you want to delete this fee record?')) {
            $.ajax({
                url: 'modules/students/DeleteFees.php',
                method: 'POST',
                data: { id: feesId },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        showMessage(data.message, 'success');
                        $('#row-' + feesId).remove();
                    } else {
                        showMessage(data.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    showMessage('Error: ' + error, 'error');
                }
            });
        }
    });
});
</script>