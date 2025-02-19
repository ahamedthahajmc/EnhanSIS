<script>
    $(document).ready(function() {
        // Grade dropdown change event
        $('#grade').change(function() {
            var gradeId = $(this).val();
            $('#section-container').hide();
            $('#student-container').hide();

            if (gradeId) {
                $.ajax({
                    type: 'POST',
                    url: 'modules/students/get_sections.php',
                    data: {
                        grade_id: gradeId
                    },
                    success: function(response) {
                        if (response.trim()) {
                            $('#section').html(response);
                            $('#section-container').show();
                        } else {
                            $('#section-container').hide();
                        }
                    }
                });
            }
        });

        // Section dropdown change event
        $('#section').change(function() {
            let gradeId = $('#grade').val();
            let sectionId = $(this).val();

            $('#student-container').show();
            if (gradeId && sectionId) {
                $.ajax({
                    type: 'POST',
                    url: 'modules/students/get_students.php',
                    data: {
                        grade_id: gradeId,
                        section_id: sectionId
                    },
                    success: function(response) {
                        console.log("Response from get_students.php:", response);
                        if (response.trim()) {
                            $('#student_id1').html(response).show();
                        } else {
                            $('#student_id1').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error fetching students:", error);
                    }
                });
            }
        });

        $('#search-form').on('submit', function(e) {
            e.preventDefault();

            var grade = $('#grade').val();
            var section = $('#section').val();
            var year = $('#year').val();
            var studentId = $('#student_id1').val();

            $.ajax({
                url: 'modules/students/searchGrade.php',
                method: 'POST',
                data: {
                    action: 'search_by_grade',
                    grade: grade,
                    section: section,
                    year: year,
                    student_id: studentId
                },
                success: function(response) {
                    console.log("Raw Response:", response); // Log response to check if it's valid JSON
                    try {
                        var res = JSON.parse(response); // Parse JSON
                        console.log("Parsed JSON:", res); // Log parsed object
                        if (res.success) {
                            if (studentId) {
                                $('#student-name').text(res.student_name);
                                $('#student-count-row').hide();
                                $('#student-name-row').show();
                            } else {
                                $('#student-count').text(res.student_count);
                                $('#student-name-row').hide();
                                $('#student-count-row').show();
                            }
                            $('#student_id').val(res.student_ids.join(", "));
                            $('#grade-title').text(res.grade_title);
                            $('#section-name').text(res.section_name);
                            $('#search-form').hide();
                            $('#add-fees-form').show();
                        } else {
                            alert(res.message);
                        }
                    } catch (e) {
                        console.log("Error in parsing JSON:", e, response);
                        alert("Error processing the search result. Check console for details.");
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error:", error);
                    alert("Error searching student.");
                }
            });
        });
        $('#feesForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            var formData = $(this).serialize();
            var singleStuId = $('#student_id1').val();
            console.log(formData);
            $.ajax({
                url: 'modules/students/Add.php', // Ensure this points to the correct PHP file
                method: 'POST',
                data: singleStuId == "" ? formData: {singleStuId,formData}, // Send the serialized form data
                success: function(response) {
                    console.log("Raw Response:", response); // Log response to check if it's valid JSON
                    try {
                        var res = JSON.parse(response); // Parse JSON
                        console.log("Parsed JSON:", res); // Log parsed object
                        if (res.success) {
                            if (res.student_ids && res.student_ids.length > 0) {
                                $('#student-name').text(res.student_name);
                                $('#student-count-row').hide();
                                $('#student-name-row').show();
                            } else {
                                $('#student-count').text(res.student_count);
                                $('#student-name-row').hide();
                                $('#student-count-row').show();
                            }
                            $('#student_id').val(res.student_ids.join(", "));
                            $('#grade-title').text(res.grade_title);
                            $('#section-name').text(res.section_name);
                            $('#search-form').show();
                            $('#add-fees-form').hide();
                        } else {
                            alert(res[0].message);
                        }
                    } catch (e) {
                        console.log("Error in parsing JSON:", e, response);
                        alert("Error processing the search result. Check console for details.");
                    }
                },
                error: function(xhr, status, error) {
                    // Show error message
                    $('#response-message').html('<div class="alert alert-danger">Error adding fees: ' + error + '</div>');
                }
            });
        });
        $('#fees').on('click', function () {
        scrollToTop();
        $('#add-fees-form').hide();
        $('#search-form').show();
        $('#section-container').hide();
        $('#student-container').hide();
        $('#reset-btn').click();
        
    });
    });
</script>


<?php
include('../../Data.php');
PopTable('header', _addfees);
?>
<div class="form-horizontal m-b-0">
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
            <div class="col-md-6" id="student-container" style="display:none;">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label text-right col-lg-4">Student ID</label>
                        <div class="col-lg-8">
                            <select id="student_id1" name="student_id" class="form-control">
                                <option value="">Select Student</option>
                                <option value="">Select All Student</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div><br>

            <input type="hidden" id="year" name="year" value="<?php echo UserSyear(); ?>"> <!-- Current year -->

            <div class="text-right">
                <input id="searchStuBtn" type="submit" name="search" class="btn btn-primary m-r-10" value="Search">
                <input type="reset" id="reset-btn" class="btn btn-default" value="Reset">
            </div>
        </div>
    </form>
</div>


<!-- Div for Add Fees Form (Initially Hidden) -->
<div id="add-fees-form" style="display: none;">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Grade</td>
                <td id="grade-title" class="text-success"></td>
            </tr>
            <tr>
                <td>Section</td>
                <td id="section-name" class="text-success"></td>
            </tr>
            <tr id="student-count-row">
                <td>No. Of Students</td>
                <td id="student-count" class="text-success"></td>
            </tr>
            <tr id="student-name-row">
                <td>Name</td>
                <td id="student-name" class="text-success"></td>
            </tr>
        </tbody>
    </table>

    <form id="feesForm" method="post">
        <div class="mb-3">
            <input type="hidden" name="student_id" id="student_id" class="form-control">
        </div><br>

        <table class="table table-bordered" id="fee_type">
            <thead>
                <tr class='bg-grey-200'>
                    <th>Fee Type</th>
                    <th>Fees Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Book Fees</td>
                    <td><input class="form-control" name="book_fees" placeholder="Enter amount" required></td>
                </tr>
                <tr>
                    <td>Tuition Fees</td>
                    <td><input class="form-control" name="tuition_fees" placeholder="Enter amount" required></td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="text-right">
            <button id="fees" type="submit" class="btn btn-primary">Add Fees</button>
        </div>
    </form>

    <!-- Success/Error Messages -->
    <div id="response-message" class="mt-3"></div>
</div>