<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Search Form</title>
    <style>
        .form-container {
            max-width: 1200px;
            height: 400px;
            background-color: #fff;
            padding: 20px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #ec407a;
            margin-bottom: 20px;
            font-size: 18px;
            border-bottom: 2px solid #ec407a;
            display: inline-block;
            padding-bottom: 5px;
        }
        .form-group {
            display: flex;
            
            /* margin-bottom: 15px; */
        }
        /* .form-group {
            display: flex;
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            margin-right: 10px;
            align-self: center;
        }

        .form-group input,
        .form-group select {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        } */

            .LastName{
                margin-top: 12px;

            }

            .FirstName{
                margin-top: 12px;
            }

            .StudentId{
                margin-top: 12px;

            }

            .AltId{
                margin-top: 12px;
                margin-left: 15px;
            }

            .FeesType{
                margin-top: 12px;

            }

        .form-group input,
.form-group select {
    /* flex: 1; */
    width: 480px;
    padding: 12px ;
    border: none;
    border-bottom: 2px solid #ccc;
    background-color: transparent;
    font-size: 14px;
    outline: none;
    transition: border-color 0.3s ease;
}

/* Focus state for inputs */
.form-group input:focus,
.form-group select:focus {
    border-bottom-color: #2196f3;
}

/* Error state for input */
.form-group input.error,
.form-group select.error {
    border-bottom-color: red;
}

        .required-asterisk {
            color: red;
            margin-left: 3px;
        }


        .buttons {
            display: flex;
            justify-content: flex-end;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .buttons .submit-btn {
            background-color: #2196f3;
            color: white;
            margin-right: 10px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            display: none;
        }

        .container-1 {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        h2 {
            color: #ec407a;
            align-items: center;
            text-align: center;
        }

        /* .save-btn-container {
            text-align: left;
        } */

        .save-btn {
            background-color: #337ab7;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 30px;
            margin-top: 40px;
        }

        .save-btn:hover {
            background-color: #286090;
        }

        .hidden {
            display: none;
        }


        .printButton {
            background-color: #337ab7;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            ;
        }

        .printButton:hover {
            background-color: #286090;
        }
    </style>
</head>

<body>

    <div id="form-container" class="form-container">
        <h2>Find a Student</h2>

        <!-- Added New -->
        <div id="error-message" class="error-message" style="display: none;">
            Please fill out all required fields.
        </div>


        <form id="find-student-form">
            <div class="form-group">
                <label class="LastName" for="last-name" style="margin-top: 12px">Last Name <span class="required-asterisk">*</span></label>
                <input type="text" id="last-name" name="last_name" placeholder="Last Name">

                <label class="FirstName" for="first-name">First Name <span class="required-asterisk">*</span></label>
                <input type="text" id="first-name" name="first_name" placeholder="First Name">
            </div>
            <div class="form-group">
                <label class="StudentId" for="student-id">Student ID <span class="required-asterisk">*</span></label>
                <input type="text" id="student-id" name="student_id" placeholder="Student ID">

                <label class="AltId" for="alt_id">Alt ID <span class="required-asterisk">*</span></label>
                <input type="text" id="alt_id" name="alt_id" placeholder="Alt ID">
                <div id="altIdError" class="error"></div>
            </div>
            <div class="form-group">
                <label class="FeesType"for="fee_type">Fees Type: <span class="required-asterisk">*</span></label>
                <select class="fee_type"name="fee_type" id="fee_type">
                    <option value="">Select Fees Section</option>
                    <option value="tuition">Tuition Fees</option>
                    <option value="book">Book Fees</option>
                    <option value="travel">Travel Fees</option>
                </select>
                <div id="feeTypeError" class="error"></div>
            </div>

            <div class="buttons">
                <button type="submit" class="submit-btn">Submit</button>
            </div>
        </form>
    </div>

    <div id="table-container" class="container-1 hidden">
        <h2>Fees Reports</h2>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Alt ID</th>
                    <th>Fees Section</th>
                    <th>Paid</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody id="fee-report-body"></tbody>
        </table>
        <div class="save-btn-container">
            <button class="save-btn" id="GoBack">Back</button>
            <button class="printButton" id="printButton" onclick="handlePrint()">Print</button>

        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        // Newly Added
        // document.getElementById('find-student-form').addEventListener('submit', function(event) {
        //     event.preventDefault();

        //     let lastName = document.getElementById('last-name').value.trim();
        //     let firstName = document.getElementById('first-name').value.trim();
        //     let feeType = document.getElementById('fee_type').value.trim();

        //     if (!lastName || !firstName || !feeType || !altId || !student_id) {
        //         document.getElementById('error-message').style.display = 'block';
        //     } else {
        //         document.getElementById('error-message').style.display = 'none';
        //         // Proceed with form submission or any other logic
        //     }
        // });

        
    <script>
        document.getElementById('find-student-form').addEventListener('submit', function(event) {
            event.preventDefault();

            let lastName = document.getElementById('last-name').value.trim();
            let firstName = document.getElementById('first-name').value.trim();
            let studentId = document.getElementById('student-id').value.trim();
            let altId = document.getElementById('alt_id').value.trim();
            let feeType = document.getElementById('fee_type').value;

            let hasError = false;

]            if (!lastName) {
                document.getElementById('lastNameError').textContent = 'Enter Last Name.';
                hasError = true;
            } else {
                document.getElementById('lastNameError').textContent = '';
            }

            if (!firstName) {
                document.getElementById('firstNameError').textContent = 'Enter First Name.';
                hasError = true;
            } else {
                document.getElementById('firstNameError').textContent = '';
            }

            if (!studentId) {
                document.getElementById('studentIdError').textContent = 'Enter Student ID.';
                hasError = true;
            } else {
                document.getElementById('studentIdError').textContent = '';
            }

            if (!altId) {
                document.getElementById('altIdError').textContent = 'Enter Alt Id.';
                hasError = true;
            } else {
                document.getElementById('altIdError').textContent = '';
            }

            if (!feeType) {
                document.getElementById('feeTypeError').textContent = 'Select any Fees type.';
                hasError = true;
            } else {
                document.getElementById('feeTypeError').textContent = '';
            }

            if (hasError) {
                return;
            }

           
        });
    </script>

        <script>
        $(document).ready(function() {
            $("#find-student-form").submit(function(event) {
                event.preventDefault();

                $("#altIdError").text('');
                $("#feeTypeError").text('');

                var altId = $("#alt_id").val();
                var feeType = $("#fee_type").val();
                var hasError = false;

                if (altId === "") {
                    $("#altIdError").text("Input Alt ID to Show Student Details");
                    hasError = true;
                }

                if (feeType === "") {
                    $("#feeTypeError").text("Please select any Fees type.");
                    hasError = true;
                }

                if (hasError) {
                    return;
                }

                var formData = {
                    last_name: $("#last-name").val(),
                    first_name: $("#first-name").val(),
                    student_id: $("#student-id").val(),
                    alt_id: altId,
                    fee_type: feeType
                };

                $.ajax({
                    url: 'submit.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $("#form-container").addClass('hidden');

                        $("#table-container").removeClass('hidden');

                        $("#fee-report-body").empty();

                        if (response.length > 0) {
                            $.each(response, function(index, student) {
                                $("#fee-report-body").append(`
                            <tr>
                                <td>${student.student_id}</td>
                                <td>${student.last_name}</td>
                                <td>${student.first_name}</td>
                                <td>${student.alt_id}</td>
                                <td>${student.Fees_Section}</td>
                                <td>${student.Paid}</td>
                                <td>${student.Balance}</td>
                            </tr>
                        `);
                            });
                        } else {
                            $("#fee-report-body").append("<tr><td colspan='7'>No data found</td></tr>");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                    }
                });
            });

            $("#GoBack").click(function() {

                $("#table-container").addClass('hidden');
                $("#form-container").removeClass('hidden');
            });

            function handlePrint() {
                window.print();
            }
        });
    </script>
</body>

</html>
