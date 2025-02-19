<?php
PopTable('header', _leaveapplication);
?> 

<!DOCTYPE HTML>  
<html lang="en">
<head>  
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Application Form</title>
<style>
input[type="submit"] {
            background-color:rgb(48, 30, 128); 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            font-size: 13px; 
            cursor: pointer; 
            transition: background-color 0.3s ease; 
        }

        input[type="submit"]:hover {
            background-color:rgb(50, 74, 211); /* Darker green on hover */
        }
        label {
            font-size: 14px;
        }
        
</style>
</head>
<body>  

<form id="form_container" method="POST"> 
<div class="form-horizontal m-b-0">
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">Last Name</label>
                <div class="col-lg-8">
                 <input id="last_name" class="form-control" type="text" name="last_name" placeholder="Last Name" size="30"  required>
                </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">First Name</label>
                <div class="col-lg-8">
                    <input id="first_name" class="form-control" type="text" name="first_name" placeholder="First Name" size="30" required>
                </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">Middle Name</label>
                <div class="col-lg-8">
                    <input id="middle_name" class="form-control" type="text" name="middle_name" placeholder="Middle Name" size="30">
                </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">Student ID</label>
                <div class="col-lg-8">
                    <input type="text" id="stuid" name="student_id" size="30" placeholder="Student ID" class="form-control">
                </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">Grade</label>
                <div class="col-lg-8">
                    <select name="grade" id="grade" class="form-control" required>
                        <option value="">Select Grade</option>
                        <?php
                        $gradeList = DBGet(DBQuery("SELECT DISTINCT TITLE, ID, SHORT_NAME FROM institute_gradelevels WHERE INSTITUTE_ID='" . UserInstitute() . "' ORDER BY SORT_ORDER"));
                        foreach ($gradeList as $grade) {
                            echo '<option value="' . $grade['ID'] . '">' . $grade['TITLE'] . '</option>';
                        }
                        ?>
                        </select>
                </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">Section</label>
                <div class="col-lg-8">
                    <select id="section" name="section" class="form-control">
                        <option value="">Section</option>
                        <?php
                        $sectionList = DBGet(DBQuery("SELECT DISTINCT ID, NAME FROM institute_gradelevel_sections WHERE INSTITUTE_ID='" . UserInstitute() . "' ORDER BY SORT_ORDER"));
                        foreach ($sectionList as $section) {
                            echo '<option value="' . $section['ID'] . '">' . $section['NAME'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-lg-12 text-right">
                <button id="search" type="button" class="btn btn-primary" onclick="searchFunction()">GO</button>
            </div>
        </div>
    </div>
</div>

<div class="row hidden-fields" style="display: none;">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4" >Leave Type</label>
                <div class="col-lg-8">
                    <select name="leave_type" class="form-control" required>
                        <option value="">Select Leave Type</option>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Casual Leave">Casual Leave</option>
                        <option value="Personal Leave">Personal Leave</option>
                    </select>
                </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">Reason for Leave</label>
                <div class="col-lg-8">
                    <input id="reason" class="form-control" type="text" name="reason" placeholder="Reason for Leave" size="30" required>
                </div>
        </div>
    </div>
</div>

<div class="row hidden-fields" style="display: none;">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">Start Date</label>
                <div class="col-lg-8">
                    <input id="start_date" class="form-control" type="date" name="start_date" required>
                </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label text-right col-lg-4">End Date</label>
                <div class="col-lg-8">
                    <input id="end_date" class="form-control" type="date" name="end_date" required>
                </div>
        </div>
    </div>
</div>

<div class="panel-footer text-right p-r-40 hidden-fields" style="display: none;">
    <input id="submit" type="submit" value="Submit" class="submit-btn" >
</div>
</div>
</form>
    <!-- <div id="successmessage" class="success-message" style="display: none;">
        <p style="font-size: 15px; color: green; font-weight: bold; text-align: center; margin: 80; width: fit-content;">Your form has been submitted successfully.</p>
    </div> -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        function searchFunction() {
            
            var student_id = $('#stuid').val();
        
            if(student_id === '') {
                alert('Please enter a valid student ID.');
                return;
            }
        
            $.ajax({
                url: 'modules/onlineleave/student_data.php',  
                type: 'GET',
                data: { student_id: student_id },  
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#first_name').val(response.first_name);
                        $('#last_name').val(response.last_name);
                        $('#grade').val(response.grade);  
                        $('#section').val(response.section); 
                        $(".hidden-fields").show();  
                        $('#search').hide();  
                    } else {
                        alert(response.message);  
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);  
                }
            });
        }
        
        $(document).ready(function(){
            $("#form_container").submit(function(event){
                event.preventDefault();  

                var formData = $(this).serialize();  

                $.ajax({
                    url: 'modules/onlineleave/Application.php',  
                    type: 'POST',
                    data: formData,
                    dataType: 'json', 
                    success: function(response){
                        console.log(response); 
                        if(response.status === 'success') {
                            alert('Your form has been submitted successfully.');
                            // $('#successmessage').show();
                            // $('#form_container').hide();
                            $('#submit').hide();
                        } else {
                                    alert('Error: ' + response.message);
                                }
                    },
                    error: function(xhr, status, error){
                        console.log(xhr.responseText);
                        alert('Error: ' + error);
                    }
                });
            });
        });
</script>
</body>
</html>
