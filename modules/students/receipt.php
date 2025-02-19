<?php
PopTable('header', _receipt);

if (isset($_GET['institute_id'])) {

    $InstituteQuery = DBGet(DBQuery("SELECT title FROM institutes WHERE id = " . intval($_GET['institute_id'])));
    $Institute = $InstituteQuery[1]['TITLE'];
} else {
    $defaultInstituteQuery = DBGet(DBQuery("SELECT id, title FROM institutes LIMIT 1"));
    $Institute = $defaultInstituteQuery[1]['TITLE'];
    $InstituteID = $defaultInstituteQuery[1]['ID'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees Receipt</title>
    <style>
        @media print {

            #backBtn,
            header,
            footer,
            nav,
            .sidebar {
                display: none !important;
            }

            * {
                box-sizing: border-box;
            }

            body * {
                margin: 0;
                padding: 0;
                visibility: hidden;
                /* Hide everything by default */
            }

            #receiptCard,
            #receiptCard * {
                visibility: visible;
                /* Only display the receipt section */
            }

            #receiptCard {
                position: absolute;
                left: 0;
                top: 0;
                margin: 0;
                padding: 0;
                width: 100%;
                page-break-inside: avoid;
            }

            .d-print-none {
                display: none !important;
            }

            #institute_name {
                display: block !important;
                /* Show institute name during print */
            }

            #payment_date_dropdown {
                display: none;
                /* Show paymentDate name during print */
            }

            #logo {
                display: block !important;

            }


            #backBtn {
                display: none !important;
                /* Hide back button during print */
            }

            #footer-signatures {
                display: flex !important;
                /* Show the cashier and principal during print */
            }

            /* Ensure the current date is visible during print */
            #current_date {
                display: block !important;
                /* Show current date during print */
                visibility: visible;
                /* Make sure it's visible */
            }

            .table thead th {
                background-color: #f0f0f0 !important;
                /* Ensure this color shows up during print */
                -webkit-print-color-adjust: exact;
                /* Ensure color adjustments are respected during print */
                print-color-adjust: exact;
            }

            .table-responsive {
                margin-top: 10px;
                /* Adjust this value for more or less spacing */
            }


        }


        /* Default view for UI */
        #current_date {
            display: none;
            /* Ensure the current date is hidden by default in the UI */
        }

        #institute_name {
            display: none;
            /* Ensure the institute name is hidden by default in the UI */
        }


        #logo {
            display: none;
        }


        #footer-signatures {
            display: none;
            justify-content: right;
            /* Hide cashier section in the UI */
        }
    </style>
</head>

<body>

    <body>
        <?php

        $userName = user('USERNAME');
        $userProfile = User('PROFILE');
        $instituteId = UserInstitute();
        $institute_name = GetInstituteTitle($instituteId);
        $institute_details = GetInstituteAddressFromInstituteId($instituteId);


        if (User('PROFILE') == 'admin') {
            // Admin's search section
            echo '<div id="searchSection">';
            // echo '<h4 style="text-align:center">Search Student Receipt</h4>';
            echo '<div class="form-horizontal m-b-0">';
            Search('search_fees');
            $year = UserSyear();
            echo '<input type="hidden" id="year" value="' . $year . '">';
            echo '<hr/>';
            echo "<form id='searchForm' style='display: flex; justify-content: flex-end;'>";
            echo "<button type='submit' class='btn btn-primary'>Search</button>";
            echo "</form>";
            echo '</div>';
            echo '</div>';


        ?>


            <!-- Fee Receipt Section -->

            <div class="card mt-4" id="receiptCard" style='display:none; ' ;>


                <div class="card-header text-center">

                    <img id="logo" src="modules\students\fetchLogo.php" style="width:100px; height:100px; margin-left: 40%">
                    <h1 id="institute_name"><?php echo htmlspecialchars($institute_name); ?></h1>


                    <p id="institute_name" style="margin-bottom: 10px;">
                        <?php echo htmlspecialchars($institute_details['address']); ?>, <?php echo htmlspecialchars($institute_details['city']) . '-' . htmlspecialchars($institute_details['zipcode']); ?>.<br>
                        <?php echo htmlspecialchars($institute_details['state']); ?>.<br>
                        <?php echo htmlspecialchars($institute_details['phone']); ?>
                    </p>


                    <h3>PAYMENT RECEIPT</h3>
                    <span id="current_date" style="position:absolute; right: 20px; margin-top:25px "></span>
                </div>

                <div class="form-group text-right">
                    <!-- <label for="payment_date_dropdown">Select Payment Date:</label> -->
                    <select class="btn btn-primary select-right" id="payment_date_dropdown" onchange="dateChange()">
                    <option value="">-- Select Date --</option>
                    </select>
                </div>

                <div class="card-body">
                    <br>
                    <h4 style="margin-top: 10px;">STUDENT DETAILS:</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="bg-grey-200">
                                        <th class="text-wrap">Student ID</th>
                                        <th class="text-wrap">Student Name</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-wrap"><span id="student_id"></span></td>
                                        <td class="text-wrap"><span id="student_name"></span></td>


                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <h4 style="margin-top: 10px;">PAYMENT DETAILS:</h5>

                            <div class="table-responsive">

                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr class="bg-grey-200">
                                            <th class="text-wrap">Payment Method</th>
                                            <th class="text-wrap">Payment Type</th>
                                            <th class="text-wrap">Amount Paid</th>
                                            <th class="text-wrap">Total Balance</th>

                                            <th class="text-wrap">Payment Date</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row-1">
                                            <td class="text-wrap"><span id="payment_method"></span></td>
                                            <td class="text-wrap"><span id="payment_type"></span></td>
                                            <td class="text-wrap"><span id="amount_paid"></span></td>
                                            <td class="text-wrap"><span id="total_fees"></span></td>
                                            <td class="text-wrap"><span id="payment_date"></span></td>


                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <!-- <h5>FEE BALANCE:</h5> -->
                            <h4 style="margin-top: 10px;">FEE BALANCE:</h5>


                                <div class="table-responsive">

                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr class="bg-grey-200">
                                                <th class="text-wrap">Book Fees</th>
                                                <th class="text-wrap">Tuition Fees</th>
                                                <th class="text-wrap">Transport Fees</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-wrap">₹<span id="book_fees"></span></td>
                                                <td class="text-wrap">₹<span id="tuition_fees"></span></td>
                                                <td class="text-wrap">₹<span id="transport_fees"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-footer text-end" style="display: flex; justify-content: flex-end; margin-top: 25px">
                                    <button id="printBtn" class="btn btn-primary" style="margin-right: 15px" onclick="printReceipt()">PRINT</button>
                                    <button id="backBtn" class="btn btn-secondary" onclick="showSearch()">BACK</button>
                                </div>

                                <div id="footer-signatures">
                                    <div class="footer-signature">
                                        <h5 style="margin-left: 50px" ;><?php echo ($userProfile) . 'signature' ?></h5>


                                        <h5 style="margin-left: 50%" ;><?php echo ($userName) ?></h5>


                                    </div>

                                </div>

                </div>
            </div>
            </div>

            <div id="gradeResult" class="mt-4"></div>
            <script>
                function fetch_dates(student_id) {
                    $.ajax({
                        url: 'modules/students/fetch_payment_dates.php',
                        type: 'GET',
                        data: {
                            student_id: student_id
                        },
                        success: function(response) {
                            // console.log(student_id)
                            let data = JSON.parse(response);
                            if (data.success) {
                                let dropdown = $('#payment_date_dropdown');
                                dropdown.empty(); // Clear previous data
                                dropdown.append(new Option("Payment Date", "")); // Default option
                                data.dates.forEach(function(date) {
                                    dropdown.append(new Option(date.payment_date + " Receipt No: " + date.payment_id, date.payment_id));
                                });
                            } else {
                                alert('Error fetching payment dates.');
                            }
                        },
                        error: function() {
                            alert('Error loading payment dates. Please try again.');
                        }
                    });
                }


                $(document).ready(function() {
                    // Set the current date
                    let today = new Date().toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    $('#current_date').text(today);

                    $('#searchForm').submit(function(e) {
                        e.preventDefault();

                        let first_name = $('#first_name_input').val().trim();
                        let last_name = $('#last_name_input').val().trim();
                        let student_id = $('#student_id_input').val().trim();
                        let grade = $('#grade').val();
                        let section = $('#section').val();
                        let year = $('#year').val();

                        if ((student_id !== '')) {
                            // Fetch receipt details
                            // console.log("call1")
                            $.ajax({
                                url: 'modules/students/fetch_receipt.php',
                                type: 'GET',
                                data: {
                                    student_id: student_id,
                                    // first_name: first_name,
                                    // last_name: last_name
                                },
                                success: function(response) {
                                    let receipt = JSON.parse(response);
                                    if (receipt.success) {
                                        $('#searchSection').hide();

                                        // Populate receipt details
                                        $('#student_name').text(receipt.student_name);
                                        $('#student_id').text(receipt.student_id);
                                        $('#book_fees').text(receipt.book_fees);
                                        $('#tuition_fees').text(receipt.tuition_fees);
                                        $('#transport_fees').text(receipt.transport_fees);
                                        $('#total_fees').text(receipt.total_fees);
                                        $('#payment_type').text(receipt.payment_type);
                                        $('#payment_method').text(receipt.payment_method);
                                        $('#amount_paid').text(receipt.amount_paid);
                                        $('#payment_date').text(receipt.payment_date);

                                        $('#receiptCard').show();
                                    } else {
                                        alert('Error: ' + receipt.message);
                                    }

                                },
                                error: function() {
                                    alert('Error fetching receipt. Please check your input.');
                                }
                            });

                            // Fetch all payment dates for the selected student
                            fetch_dates(student_id);
                            // fetch_dates(studentID);


                        } else if (first_name != "" || last_name != "") {

                            // Fetch students by Grade and Section
                            fetch(`modules/students/SearchGradeForReceipt.php?first_name=${first_name}&last_name=${last_name}`)
                                .then((response) => response.json())
                                .then((data) => {
                                    if (data.success) {

                                        let tableHtml = `
                                            <table class="table table-bordered " id= "gradeTableSection ">
                                            <thead>
                                                <tr class="bg-grey-200">
                                                <th>Student Name</th>
                                                <th>Student Id</th>
                                                <th>Grade</th>
                                                <th>Section</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                        data.students.forEach(function(student) {
                                            tableHtml += `
                                            <tr>
                                                <td>
                                                <a href="#" class="student-link" data-id="${student.student_id}" data-name="${student.student_name}" onclick="selectStudentAndFetchCertificates('${student.student_id}', '${student.student_name}')">${student.student_name}</a>
                                                </td>
                                                <td>${student.student_id}</td>
                                                <td>${student.grade_title}</td>
                                                <td>${student.section_name}</td>
                                            </tr>`;
                                        });
                                        tableHtml += '</tbody></table>';

                                        // Hide the student search form, show the grade section table, and hide the grade search UI
                                        $('#searchSection').hide();
                                        $('#gradeResult').show();

                                        $('#gradeResult').html(tableHtml);
                                    } else {
                                        alert(data.message || 'No students found for the selected grade and section.');
                                        return; // **Stops execution after alert**
                                    }
                                })

                            // $.ajax({
                            // url: 'modules/students/fetch_payment_dates.php',
                            // type: 'GET',
                            // data: {
                            //     student_id: student_id,
                            //     // first_name: first_name,
                            //     // last_name: last_name
                            // },
                            // success: function(response) {
                            //     let data = JSON.parse(response);
                            //     if (data.success) {
                            //         let dropdown = $('#payment_date_dropdown');
                            //         dropdown.empty(); // Clear previous data
                            //         dropdown.append(new Option("Payment Date", "")); // Default option
                            //         data.dates.forEach(function(date) {
                            //             dropdown.append(new Option(date.payment_date + " Receipt No: " + date.payment_id, date.payment_id));
                            //         });
                            //     } else {
                            //         alert('Error fetching payment dates.');
                            //     }
                            // },
                            // error: function() {
                            //     alert('Error loading payment dates. Please try again.');
                            // }
                            // });


                        } else if ((grade != '') || (section != '')) {
                            $.ajax({
                                url: 'modules/students/SearchGradeforReceipt.php',
                                type: 'POST',
                                data: {
                                    action: 'search_by_grade',
                                    grade: grade,
                                    section: section,
                                    year: year
                                },
                                success: function(response) {
                                    let search = JSON.parse(response);
                                    if (search.success) {
                                        $('#searchSection').hide();

                                        let tableHtml = '<table class="table table-bordered"><thead><tr class="bg-grey-200"><th>Student Name</th><th>Student Id</th><th>Grade</th><th>Section</th></tr></thead><tbody>';
                                        search.student_ids.forEach(function(student_id, index) {
                                            let student_name = search.student_names[index];

                                            tableHtml += `
                        <tr>
                            <td>
                                <a href="#" class="student-link" data-id="${student_id}" data-name="${student_name}">${student_name}</a>
                            </td>
                            <td>${student_id}</td>
                            <td>${search.grade_title}</td>
                            <td>${search.section_name}</td>
                        </tr>`;
                                        });
                                        tableHtml += '</tbody></table>';

                                        $('#gradeResult').html(tableHtml).show(); // Ensure the table is shown

                                    } else {
                                        alert('No students found for the selected criteria.');
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.error('AJAX error:', textStatus, errorThrown);
                                    alert('Error fetching data. Please try again.');
                                }
                            });
                        }
                    });

                    $(document).off('click', '.student-link').on('click', '.student-link', function(e) {
                        e.preventDefault();

                        let student_id = $(this).data('id');
                        let $this = $(this);

                        $this.addClass('disabled');
                        console.log("call2")
                        $.ajax({
                            url: 'modules/students/fetch_receipt.php',
                            type: 'GET',
                            data: {
                                student_id: student_id
                            },
                            success: function(response) {
                                let receipt = JSON.parse(response);
                                if (receipt.success) {
                                    $('#gradeResult').hide(); // Hide the student list only when fetching receipt

                                    $('#student_name').text(receipt.student_name);
                                    $('#student_id').text(receipt.student_id);
                                    $('#book_fees').text(receipt.book_fees);
                                    $('#tuition_fees').text(receipt.tuition_fees);
                                    $('#transport_fees').text(receipt.transport_fees);
                                    $('#total_fees').text(receipt.total_fees);
                                    $('#payment_type').text(receipt.payment_type);
                                    $('#payment_method').text(receipt.payment_method);
                                    $('#amount_paid').text(receipt.amount_paid);
                                    $('#payment_date').text(receipt.payment_date);

                                    $('#receiptCard').show();
                                } else {
                                    alert('Error: ' + receipt.message);
                                }
                            },
                            complete: function() {
                                $this.removeClass('disabled');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('AJAX error:', textStatus, errorThrown);
                                alert('Error fetching receipt. Please check your input.');
                            }
                        });
                        fetch_dates(student_id);
                    });




                    $('#payment_date_dropdown').change(function() {
                        let selectedId = $(this).val();
                        $(this).find('option[value=""]').remove();

                        if (selectedId) {

                            $.ajax({
                                url: 'modules/students/fetch_receipt.php',
                                type: 'GET',
                                data: {
                                    payment_id: selectedId

                                },
                                success: function(response) {
                                    let receipt = JSON.parse(response);
                                    if (receipt.success) {
                                        $('#receiptCard').show();
                                        $('#student_name').text(receipt.student_name);
                                        $('#student_id').text(receipt.student_id);
                                        $('#book_fees').text(receipt.book_fees);
                                        $('#tuition_fees').text(receipt.tuition_fees);
                                        $('#transport_fees').text(receipt.transport_fees);
                                        $('#total_fees').text(receipt.total_fees);
                                        $('#payment_type').text(receipt.payment_type);
                                        $('#payment_method').text(receipt.payment_method);
                                        $('#amount_paid').text(receipt.amount_paid);
                                        $('#payment_date').text(receipt.payment_date);
                                    } else {
                                        alert('Error: ' + receipt.message);
                                    }
                                },
                                error: function() {
                                    alert('Error fetching receipt details.');
                                }
                            });
                        } else {
                            $('#receiptCard').hide();
                        }
                    });
                });




                function selectStudentAndFetchCertificates(student_id, student_name) {
                    let alertShown = false; // Flag to track if alert is shown

                    fetch(`modules/students/fetchPaymentDetails.php?student_id=${student_id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                displayPaymentReceipt(data.paymentDetails);
                            } else {
                                if (!alertShown) { // Ensure alert only shows once
                                    alert('No payment record found for ' + student_name);
                                    alertShown = true; // Set flag to true after showing alert
                                }
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }


                function printReceipt() {
                    document.getElementById("printBtn").classList.add("d-print-none");
                    window.print();
                    document.getElementById("printBtn").classList.remove("d-print-none");
                    document.getElementById('receiptCard').style.display = 'block';
                }

                // Show search function
                function showSearch() {
                    $('#searchSection').show();
                    $('#receiptCard').hide();
                    $('#student_name_input').val('');
                    $('#first_name_input').val('');
                    $('#last_name_input').val('');
                    $('#student_id_input').val('');
                    $('#payment_id_input').val('');
                }
            </script>





            <?php
        }



        if (User('PROFILE') == 'student') {

            include('../../Data.php');
            $userName = user('USERNAME');
            $studentID = GetStudentIdFromUserName($userName);
            $Payments = GetStudentPaymentFromUserName($userName);
            // printf($studentID);
            if (!empty($Payments)) {
                $Fees = GetStudentFeesFromUserName($userName);
                $TotalFeesPaid = GetStudentFeesFromUser($userName);

            ?>

                <script>

                </script>

                <div class="card mt-4" id="receiptCard">
                    <div class="card-header text-center">
                    <img id="logo" src="modules\students\fetchLogo.php" style="width:100px; height:100px; margin-left: 40%">

                        <h1 id="institute_name" style="margin-bottom: 30px;"><?php echo htmlspecialchars($institute_name); ?></h1>


                        <h5 id="institute_name" style="margin-bottom: 10px;">
                            <?php echo htmlspecialchars($institute_details['address']); ?>, <?php echo htmlspecialchars($institute_details['city']) . '-' . htmlspecialchars($institute_details['zipcode']); ?>.<br>
                            <?php echo htmlspecialchars($institute_details['state']); ?>.<br>
                            <?php echo htmlspecialchars($institute_details['phone']); ?>
                        </h5>

                        <h2>PAYMENT RECEIPT</h2>
                        <span id="current_date" style="position:absolute; right: 20px;"></span>

                    </div>

                    <div class="form-group text-right">
                        <!-- <label for="payment_date_dropdown">Select Payment Date:</label> -->
                        <select class="btn btn-primary select-right" id="payment_date_dropdown" onchange="dateChange()">
                            <option value="">-- Select Date --</option>
                        </select>
                    </div>

                    <div class="card-body">
                        <h4 style="margin-top: 10px;">STUDENT DETAILS:</h5>



                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr class="bg-grey-200">
                                            <th class="text-wrap">Student ID</th>
                                            <th class="text-wrap">Student Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-wrap" id="student_id"><?php echo htmlspecialchars($studentID); ?></td>
                                            <td class="text-wrap" id="student_name"><?php echo htmlspecialchars($userName); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                    </div>


                    <h4 style="margin-top: 10px;">PAYMENT DETAILS:</h5>
                        <div class="table-responsive">

                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="bg-grey-200">
                                        <th class="text-wrap">Payment Method</th>
                                        <th class="text-wrap">Payment Type</th>
                                        <th class="text-wrap">Amount Paid</th>

                                        <th class="text-wrap">Total Balance</th>
                                        <th class="text-wrap">Payment Date</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td class="text-wrap" id="payment_method"><?php echo htmlspecialchars(strtoupper($Payments['payment_method'])); ?></td>
                                        <td class="text-wrap" id="payment_type"><?php echo htmlspecialchars(strtoupper($Payments['payment_type'])); ?></td>

                                        <td class="text-wrap" id="amount_paid"><?php echo htmlspecialchars($Payments['amount_paid']); ?></td>

                                        <td class="text-wrap" id="total_fees"><?php echo htmlspecialchars($TotalFeesPaid['total_fees']); ?></td>
                                        <td class="text-wrap" id="payment_date"><?php echo htmlspecialchars($Payments['payment_date']); ?></td>



                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <h4 style="margin-top: 10px;">FEE BALANCE:</h5>

                            <div class="table-responsive">

                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr class="bg-grey-200">
                                            <th class="text-wrap">Book Fees</th>
                                            <th class="text-wrap">Tuition Fees</th>
                                            <th class="text-wrap">Transport Fees</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-wrap" id="book_fees">₹<?php echo ($Fees['book_fees_balance']) ?></td>
                                            <td class="text-wrap" id="tuition_fees">₹<?php echo ($Fees['tuition_fees_balance']) ?></td>
                                            <td class="text-wrap" id="transport_fees">₹<?php echo ($Fees['transport_fees_balance']) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer text-end" style="display: flex; justify-content: flex-end; margin-top: 25px">
                                <button id="printBtn" class="btn btn-primary" style="margin-right: 15px" onclick="printReceipt()">PRINT</button>
                                <!-- <button id="backBtn" class="btn btn-secondary" onclick="showSearch()">BACK</button> -->
                            </div>

                            <div id="footer-signatures">
                                <div class="footer-signature">
                                    <h5 style="margin-left: 50px" ;><?php echo ($userProfile) . ' ' . 'Signature' ?></h5>
                                    <!-- <h5 style="margin-left: 50%" ;><?php echo ($userName) ?></h5> -->
                                </div>

                            </div>
                </div>
                </div>

                <script>
                    $(document).ready(function() {
                        // Set the current date for both admin and student receipts
                        let today = new Date().toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        $('#current_date').text(today);

                        $('#searchForm').submit(function(e) {
                            // Existing AJAX code for search functionality
                        });
                    });


                    function fetch_dates(studentID) {
                        $.ajax({
                            url: 'modules/students/fetch_payment_dates.php',
                            type: 'GET',
                            data: {
                                student_id: studentID
                            },
                            success: function(response) {
                                // console.log(studentId)
                                let data = JSON.parse(response);
                                if (data.success) {
                                    let dropdown = $('#payment_date_dropdown');
                                    dropdown.empty(); // Clear previous data
                                    dropdown.append(new Option("Payment Date", "")); // Default option
                                    data.dates.forEach(function(date) {
                                        dropdown.append(new Option(date.payment_date + " Receipt No: " + date.payment_id, date.payment_id));
                                    });
                                } else {
                                    alert('Error fetching payment dates.');
                                }
                            },
                            error: function() {
                                alert('Error loading payment dates. Please try again.');
                            }
                        });
                    }

                    fetch_dates(<?php echo json_encode($studentID); ?>);

                    $('#payment_date_dropdown').change(function() {
                        let receiptID = $(this).val();
                        $(this).find('option[value=""]').remove();


                        if (receiptID) {
                            $.ajax({
                                url: 'modules/students/fetch_receipt.php',
                                type: 'GET',
                                data: {
                                    payment_id: receiptID

                                },
                                success: function(response) {
                                    let receipt = JSON.parse(response);
                                    if (receipt.success) {
                                        // dropdown.append(new Option("Payment Date", "", true, true)).prop("disabled", true);// Default option
                                        $('#receiptCard').show();
                                        $('#student_name').text(receipt.student_name);
                                        $('#student_id').text(receipt.student_id);
                                        $('#book_fees').text(receipt.book_fees);
                                        $('#tuition_fees').text(receipt.tuition_fees);
                                        $('#transport_fees').text(receipt.transport_fees);
                                        $('#total_fees').text(receipt.total_fees);
                                        $('#payment_type').text(receipt.payment_type);
                                        $('#payment_method').text(receipt.payment_method);
                                        $('#amount_paid').text(receipt.amount_paid);
                                        $('#payment_date').text(receipt.payment_date);
                                    } else {
                                        alert('Error: ' + receipt.message);
                                    }
                                },
                                error: function() {
                                    alert('Error fetching receipt details.');
                                }
                            });
                        } else {
                            $('#receiptCard').hide();
                        }
                    });



                    function printReceipt() {
                        // Hide the print button during printing

                        document.getElementById("printBtn").classList.add("d-print-none");
                        window.print();
                        // Show the print button again after printing
                        document.getElementById("printBtn").classList.remove("d-print-none");
                    }
                </script>




            <?php
            } else {
                echo "<p>No payments found for this student.</p>";
            }
        }




        if (User('PROFILE') == 'parent') {

            include('../../Data.php');
            $parentUsername = user('USERNAME');
            $userName = GetStdUserNameFromUsername($parentUsername);
            $studentID = GetStudentIdFromUserName($userName);
            $Fees = GetStudentFeesFromUserName($userName);
            $Payments = GetStudentPaymentFromUserName($userName);
            $TotalFees = GetStudentFeesFromUser($userName);


            ?>

            <div class="card mt-4" id="receiptCard">
                <div class="card-header text-center">
                <img id="logo" src="modules\students\fetchLogo.php" style="width:100px; height:100px; margin-left: 40%">

                    <h1 id="institute_name" style="margin-bottom: 30px;"><?php echo htmlspecialchars($institute_name); ?></h1>


                    <h5 id="institute_name" style="margin-bottom: 10px;">
                        <?php echo htmlspecialchars($institute_details['address']); ?>, <?php echo htmlspecialchars($institute_details['city']) . '-' . htmlspecialchars($institute_details['zipcode']); ?>.<br>
                        <?php echo htmlspecialchars($institute_details['state']); ?>.<br>
                        <?php echo htmlspecialchars($institute_details['phone']); ?>
                    </h5>

                    <h2>PAYMENT RECEIPT</h2>
                    <span id="current_date" style="position:absolute; right: 20px;"></span>

                </div>

                <div class="form-group text-right">
                        <!-- <label for="payment_date_dropdown">Select Payment Date:</label> -->
                        <select class="btn btn-primary select-right" id="payment_date_dropdown" onchange="dateChange()">
                            <option value="">-- Select Date --</option>
                        </select>
                    </div>

                <div class="card-body">
                    <h4 style="margin-top: 10px;">STUDENT DETAILS:</h5>



                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="bg-grey-200">
                                        <th class="text-wrap">Student ID</th>
                                        <th class="text-wrap">Student Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-wrap" id="student_id"><?php echo htmlspecialchars($studentID); ?></td>
                                        <td class="text-wrap" id="student_name"><?php echo htmlspecialchars($userName); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                </div>


                <h4 style="margin-top: 10px;">PAYMENT DETAILS:</h5>
                    <div class="table-responsive">

                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="bg-grey-200">
                                    <th class="text-wrap">Payment Method</th>
                                    <th class="text-wrap">Payment Type</th>
                                    <th class="text-wrap">Amount Paid</th>
                                    <th class="text-wrap">Total Balance</th>

                                    <th class="text-wrap" >Payment Date</th>


                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td class="text-wrap"  id="payment_method"><?php echo htmlspecialchars(strtoupper($Payments['payment_method'])); ?></td>
                                    <td class="text-wrap"  id="payment_type"><?php echo htmlspecialchars(strtoupper($Payments['payment_type'])); ?></td>

                                    <td class="text-wrap"  id="amount_paid"><?php echo htmlspecialchars($Payments['amount_paid']); ?></td>
                                    <td class="text-wrap"  id="total_fees"><?php echo htmlspecialchars($TotalFees['total_fees']); ?></td>

                                    <td class="text-wrap" id="payment_date"><?php echo htmlspecialchars($Payments['payment_date']); ?></td>



                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top: 10px;">FEE BALANCE:</h5>

                        <div class="table-responsive">

                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="bg-grey-200">
                                        <th class="text-wrap">Book Fees</th>
                                        <th class="text-wrap">Tuition Fees</th>
                                        <th class="text-wrap">Transport Fees</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-wrap" id="book_fees">₹<?php echo ($Fees['book_fees_balance']) ?></td>
                                        <td class="text-wrap" id="tuition_fees">₹<?php echo ($Fees['tuition_fees_balance']) ?></td>
                                        <td class="text-wrap" id="transport_fees">₹<?php echo ($Fees['transport_fees_balance']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-end" style="display: flex; justify-content: flex-end; margin-top: 25px">
                            <button id="printBtn" class="btn btn-primary" style="margin-right: 15px" onclick="printReceipt()">PRINT</button>
                            <!-- <button id="backBtn" class="btn btn-secondary" onclick="showSearch()">BACK</button> -->
                        </div>

                        <div id="footer-signatures">
                            <div class="footer-signature">
                                <h5 style="margin-left: 50px" ;><?php echo ($userProfile) . ' ' . 'Signature' ?></h5>
                                <!-- <h5 style="margin-left: 50%" ;><?php echo ($userName) ?></h5> -->
                            </div>

                        </div>
            </div>
            </div>

            <script>
                $(document).ready(function() {
                    // Set the current date for both admin and student receipts
                    let today = new Date().toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    $('#current_date').text(today);

                    $('#searchForm').submit(function(e) {
                        // Existing AJAX code for search functionality
                    });
                });

                function fetch_dates(studentID) {
                        $.ajax({
                            url: 'modules/students/fetch_payment_dates.php',
                            type: 'GET',
                            data: {
                                student_id: studentID
                            },
                            success: function(response) {
                                // console.log(studentId)
                                let data = JSON.parse(response);
                                if (data.success) {
                                    let dropdown = $('#payment_date_dropdown');
                                    dropdown.empty(); // Clear previous data
                                    dropdown.append(new Option("Payment Date", "")); // Default option
                                    data.dates.forEach(function(date) {
                                        dropdown.append(new Option(date.payment_date + " Receipt No: " + date.payment_id, date.payment_id));
                                    });
                                } else {
                                    alert('Error fetching payment dates.');
                                }
                            },
                            error: function() {
                                alert('Error loading payment dates. Please try again.');
                            }
                        });
                    }

                    fetch_dates(<?php echo json_encode($studentID); ?>);

                    $('#payment_date_dropdown').change(function() {
                        let receiptID = $(this).val();
                        $(this).find('option[value=""]').remove();


                        if (receiptID) {
                            $.ajax({
                                url: 'modules/students/fetch_receipt.php',
                                type: 'GET',
                                data: {
                                    payment_id: receiptID

                                },
                                success: function(response) {
                                    let receipt = JSON.parse(response);
                                    if (receipt.success) {
                                        // dropdown.append(new Option("Payment Date", "", true, true)).prop("disabled", true);// Default option
                                        $('#receiptCard').show();
                                        $('#student_name').text(receipt.student_name);
                                        $('#student_id').text(receipt.student_id);
                                        $('#book_fees').text(receipt.book_fees);
                                        $('#tuition_fees').text(receipt.tuition_fees);
                                        $('#transport_fees').text(receipt.transport_fees);
                                        $('#total_fees').text(receipt.total_fees);
                                        $('#payment_type').text(receipt.payment_type);
                                        $('#payment_method').text(receipt.payment_method);
                                        $('#amount_paid').text(receipt.amount_paid);
                                        $('#payment_date').text(receipt.payment_date);
                                    } else {
                                        alert('Error: ' + receipt.message);
                                    }
                                },
                                error: function() {
                                    alert('Error fetching receipt details.');
                                }
                            });
                        } else {
                            $('#receiptCard').hide();
                        }
                    });

                function printReceipt() {
                    // Hide the print button during printing

                    document.getElementById("printBtn").classList.add("d-print-none");
                    window.print();
                    // Show the print button again after printing
                    document.getElementById("printBtn").classList.remove("d-print-none");
                }
            </script>




        <?php
        }

        ?>

    </body>

</html>