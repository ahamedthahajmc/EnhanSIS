<?php
PopTable('header', _payfees);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Fees</title>
</head>

<body>
    <?php
    $InstituteId = UserInstitute();     
    $instituteDetails = GetInstituteAddressFromInstituteId($InstituteId);
         

echo '
<input type="hidden" id="instituteName" value="' . GetInstituteTitle($InstituteId) . '">
<input type="hidden" id="instituteAddress" value="' . $instituteDetails['address'] . '">
<input type="hidden" id="instituteCity" value="' . $instituteDetails['city'] . '">
<input type="hidden" id="instituteState" value="' . $instituteDetails['state'] . '">
<input type="hidden" id="instituteZipcode" value="' . $instituteDetails['zipcode'] . '">
<input type="hidden" id="institutePhone" value="' . $instituteDetails['phone'] . '">
<input type="hidden" id="userName" value="' . user('NAME'). '">
<input type="hidden" id="signature" value="' . ucwords(User('PROFILE')) .' Signature">
';


    
    if (User('PROFILE') == 'admin') {
        // Admin's search section
        echo '<div id="searchSection">';
        echo '<div class="form-horizontal m-b-0">';
        Search('search_Fees');
        $year=UserSyear();
        echo'<input type="hidden" id="year" value="'.$year.'">';
        echo '<hr/>';
        echo "<form id='searchForm' style='display: flex; justify-content: flex-end;'>";
        echo "<button type='submit' class='btn btn-primary'>Search</button>";
        echo "</form>";
        echo '</div>';
        echo '</div>';
    


    ?>

        <form id="payFeesForm" class="mt-4" id="receiptCard">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="student_id">Student ID:</label>
                    <span class="form-control" id="student_id"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for="student_name">Student Name:</label>
                    <span class="form-control" id="student_name"></span>
                </div>

                <table class="table table-bordered" id="fee_type" required>
                    <?php
                    $transport_fees = "transport_fees";
                    $tuition_fees = "tuition_fees";
                    $book_fees = "book_fees";

                    ?>
                    <thead>
                        <tr class='bg-grey-200'>
                            <th>Fee Type</th>
                            <th>Balance Amount</th>
                            <th>Amount to Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td value="book_fees">Book Fees</td>
                            <td><span id="<?php echo $book_fees; ?>"></span></td>
                            <td><input class="form-control" id="bookpay_amount" placeholder="Enter amount" value="0" required></td>
                        </tr>
                        <tr>
                            <td value="tuition_fees">Tuition Fees</td>
                            <td><span id="<?php echo $tuition_fees; ?>"></span></td>
                            <td><input class="form-control" id="tuitionpay_amount" placeholder="Enter amount" required></td>
                        </tr>
                        <tr>
                            <td value="transport_fees">Transport Fees</td>
                            <td><span id="<?php echo $transport_fees; ?>"></span></td>
                            <td><input class="form-control" id="transportpay_amount" placeholder="Enter amount" required></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group col-md-6">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-control" id="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="ONLINE">Online</option>
                            <option value="OFFLINE">Offline</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" id="online_methods" style="display: none;">
                        <label for="online_type">Select Online Payment Type</label>
                        <select class="form-control" id="online_type">
                        <option value="GPAY">GPay</option>
                        <option value="PHONEPAY">PhonePe</option>
                        <option value="PAYTM">Paytm</option>
                        <option value="DEBIT_CARD">Debit Card</option>
                        <option value="CREDIT_CARD">Credit Card</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" id="offline_methods" style="display: none;">
                        <label for="offline_type">Offline Payment Type</label>
                        <select class="form-control" id="offline_type">
                            <option value="CASH">Cash</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr />
            <div class="text-right">
                <?php echo Buttons('Pay Now', 'reset'); ?>
            </div>
        </form>
        <div id="gradeResult" class="mt-4"></div>
        <div id="balanceResult" class="mt-4"></div>

        <script>
          $(document).ready(function () {
    $('#payFeesForm').hide();

    $('#searchForm').submit(function (e) {
        e.preventDefault();

        let student_id = $('#stuid').val();
        let first_name = $('#first').val();
        let last_name = $('#last').val();
        let grade = $('#grade').val();
        let section = $('#section').val();
        let year = $('#year').val();

        if (student_id !== '') {
            $.ajax({
                url: 'modules/students/searchForPayfees.php',
                type: 'POST',
                data: { student_id: student_id },
                success: function (response) {
                    let search = JSON.parse(response);
                    if (search.success) {
                        $('#searchSection').hide();
                        $('#gradeResult').hide();
                        $('#student_name').text(search.student_name);
                        $('#student_id').text(search.student_id);
                        $('#book_fees').text(search.book_fees);
                        $('#tuition_fees').text(search.tuition_fees);
                        $('#transport_fees').text(search.transport_fees);
                        $('#institute_name').text(search.institute_name);

                        setFeesReadOnly(search);
                        $('#payFeesForm').show();
                    } else {
                        alert('Error: ' + search.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                }
            });
        } else if (first_name !== '' || last_name !== '') {
            fetch(`modules\\students\\searchNameForPayfees.php?first_name=${first_name}&last_name=${last_name}`)
                .then(response => response.json())
                .then(data => {
                    $('#searchSection').hide();
                    $('#loadingMessage').hide();
                    if (data.success) {
                        let tableHtml = `<table class="table table-bordered" id="gradeTableSection">
                            <thead>
                                <tr class="bg-grey-200">
                                    <th>Student Name</th>
                                    <th>Student Id</th>
                                    <th>Grade</th>
                                    <th>Section</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        data.students.forEach(student => {
                            tableHtml += `<tr>
                                <td>
                                    <a href="#" class="student-link" data-id="${student.student_id}" data-name="${student.student_name}" onclick="selectStudentAndFetchFees('${student.student_id}', '${student.student_name}')">
                                        ${student.student_name}
                                    </a>
                                </td>
                                <td>${student.student_id}</td>
                                <td>${student.grade_title}</td>
                                <td>${student.section_name}</td>
                            </tr>`;
                        });

                        tableHtml += '</tbody></table>';
                        $('#gradeResult').html(tableHtml);
                        attachClickEventToStudentLinks();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Fetch error:', error));
        } else if (grade !== '' || section !== '') {
            $.ajax({
                url: 'modules/students/searchGradeForPayfees.php',
                type: 'POST',
                data: { action: 'search_by_grade', grade: grade, section: section, year: year },
                success: function (response) {
                    let search = JSON.parse(response);
                    if (search.success) {
                        $('#searchSection').hide();
                        $('#payFeesForm').hide();
                        $('#gradeResult').empty();

                        let tableHtml = `<table class="table table-bordered">
                            <thead>
                                <tr class="bg-grey-200">
                                    <th>Student Name</th>
                                    <th>Student Id</th>
                                    <th>Grade</th>
                                    <th>Section</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        search.student_ids.forEach((student_id, index) => {
                            let student_name = search.student_names[index];
                            tableHtml += `<tr>
                                <td>
                                    <a href="#" class="student-link" data-id="${student_id}" data-name="${student_name}">
                                        ${student_name}
                                    </a>
                                </td>
                                <td>${student_id}</td>
                                <td>${search.grade_title}</td>
                                <td>${search.section_name}</td>
                            </tr>`;
                        });

                        tableHtml += '</tbody></table>';
                        $('#gradeResult').html(tableHtml);
                        attachClickEventToStudentLinks();
                    } else {
                        alert('Error: ' + search.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                }
            });
        }
    });

    function attachClickEventToStudentLinks() {
        $('.student-link').on('click', function (e) {
            e.preventDefault();
            let student_id = $(this).data('id');
            let student_name = $(this).data('name');
            let nameParts = student_name.split(' ');
            let first_name = nameParts[0];
            let last_name = nameParts.slice(1).join(' ');

            $.ajax({
                url: 'modules/students/searchForPayfees.php',
                type: 'POST',
                data: { student_id: student_id, first_name: first_name, last_name: last_name },
                success: function (response) {
                    let search = JSON.parse(response);
                    if (search.success) {
                        $('#searchSection').hide();
                        $('#gradeResult').hide();
                        $('#student_name').text(search.student_name);
                        $('#student_id').text(search.student_id);
                        $('#book_fees').text(search.book_fees);
                        $('#tuition_fees').text(search.tuition_fees);
                        $('#transport_fees').text(search.transport_fees);

                        setFeesReadOnly(search);
                        $('#payFeesForm').show();
                    }
                }
            });
        });
    }

    function setFeesReadOnly(search) {
        $('#bookpay_amount').val(search.book_fees == 0 ? '0' : '0').prop('readonly', search.book_fees == 0);
        $('#tuitionpay_amount').val(search.tuition_fees == 0 ? '0' : '0').prop('readonly', search.tuition_fees == 0);
        $('#transportpay_amount').val(search.transport_fees == 0 ? '0' : '0').prop('readonly', search.transport_fees == 0);
    }

    $('#payment_method').change(function () {
        let paymentMethod = $(this).val();
        if (paymentMethod === 'ONLINE') {
            $('#online_methods').show();
            $('#offline_methods').hide();
        } else if (paymentMethod === 'OFFLINE') {
            $('#online_methods').hide();
            $('#offline_methods').show();
        } else {
            $('#online_methods').hide();
            $('#offline_methods').hide();
        }
    });

    $('#payFeesForm').on('submit', function(event) {
                    event.preventDefault();
                    let student_id = $('#student_id').text(); // Use .text() to get displayed ID
                    let student_name = $('#student_name').text(); // Use .text() to get displayed name
                    let book_fees = $('#book_fees').val();
                    let tuition_fees = $('#tuition_fees').val();
                    let transport_fees = $('#transport_fees').val();
                    let bookpay_amount = $('#bookpay_amount').val()
                    let tuitionpay_amount = $('#tuitionpay_amount').val()
                    let transportpay_amount = $('#transportpay_amount').val()
                    let payment_method = $('#payment_method').val();
                    let payment_type = (payment_method === 'ONLINE') ? $('#online_type').val() : $('#offline_type').val();
                    let instituteName=$('#instituteName').val();
                    let instituteAddress = $('#instituteAddress').val();
                    let instituteCity = $('#instituteCity').val();
                    let instituteState = $('#instituteState').val();
                    let instituteZipcode = $('#instituteZipcode').val();
                    let institutePhone = $('#institutePhone').val();
                    let userName = $('#userName').val();
                    let signature = $('#signature').val();

                    $.ajax({
                        url: 'modules\\students\\StdPaymentProcess.php',
                        type: 'POST',
                        data: {
                            student_id: student_id,
                            student_name: student_name,
                            book_fees: book_fees,
                            tuition_fees: tuition_fees,
                            transport_fees: transport_fees,
                            bookpay_amount: bookpay_amount,
                            tuitionpay_amount: tuitionpay_amount,
                            transportpay_amount: transportpay_amount,
                            payment_method: payment_method,
                            payment_type: payment_type,
                            instituteName:instituteName,
                            instituteAddress: instituteAddress,
                            instituteCity: instituteCity,
                            instituteState: instituteState,
                            instituteZipcode: instituteZipcode,
                            institutePhone: institutePhone,
                            userName : userName, 
                            signature: signature
                        },
                        success: function(response) {
                            $('#balanceResult').html(response);
                        }
                    });
                });

            });
        </script>
</body>

</html>

<?php
    }

    // student login //

    if (User('PROFILE') == 'student') {

        $userName = user('USERNAME');
        $stdname = GetNameFromUserName($userName);      // shortcut function of get student name.
        $stdid = GetStudentIdFromUserName($userName);  //shortcut function of get student id.
        $fees = GetStudentFeesFromUserName($userName); // shortcut function of get student fees.

?>
    <form id="payFeesForm" class="mt-4">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="student_id">Student ID:</label>
                <input class="form-control" id="student_id" value="<?php echo $stdid; ?>" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="student_name">Student Name:</label>
                <input class="form-control" id="student_name" value="<?php echo $stdname; ?>" readonly>
            </div>


            <table class="table table-bordered" id="fee_type" required>
                <thead>
                    <tr class='bg-grey-200'>
                        <th>Fee Type</th>
                        <th>Balance Amount</th>
                        <th>Amount to Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td value="book_fees">Book Fees</td>
                        <td><?php echo $fees['book_fees_balance']; ?></td>
                        <td><input class="form-control" id="bookpay_amount" placeholder="Enter amount" value="<?php echo ($fees['book_fees_balance'] == 0) ? '0' : '' ?>" <?php echo ($fees['book_fees_balance'] == 0) ? 'readonly' : 'required' ?>></td>
                    </tr>
                    <tr>
                        <td value="tuition_fees">Tuition Fees</td>
                        <td><?php echo $fees['tuition_fees_balance']; ?></td>
                        <td><input class="form-control" id="tuitionpay_amount" placeholder="Enter amount" value="<?php echo ($fees['tuition_fees_balance'] == 0) ? '0' : '' ?>" <?php echo ($fees['tuition_fees_balance'] == 0) ? 'readonly' : 'required' ?>></td>
                    </tr>
                    <tr>
                        <td value="transport_fees">Travel Fees</td>
                        <td><?php echo $fees['transport_fees_balance']; ?></td>
                        <td><input class="form-control" id="transportpay_amount" placeholder="Enter amount" value="<?php echo ($fees['transport_fees_balance'] == 0) ? '0' : '' ?>" <?php echo ($fees['transport_fees_balance'] == 0) ? 'readonly' : 'required' ?>></td>
                    </tr>

                </tbody>


            </table>

        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">

                <div class="form-group col-md-6">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="ONLINE">Online</option>
                        <option value="OFFLINE">Offline</option>
                    </select>
                </div>
                <div class="form-group col-md-6" id="online_methods" style="display: none;">
                    <label for="online_type">Select Online Payment Type</label>
                    <select class="form-control" id="online_type">
                        <option value="GPAY">GPay</option>
                        <option value="PHONEPAY">PhonePe</option>
                        <option value="PAYTM">Paytm</option>
                        <option value="DEBIT_CARD">Debit Card</option>
                        <option value="CREDIT_CARD">Credit Card</option>
                    </select>
                </div>
                <div class="form-group col-md-6" id="offline_methods" style="display: none;">
                    <label for="offline_type">Offline Payment Type</label>
                    <select class="form-control" id="offline_type">
                        <option value="cash">Cash</option>
                    </select>
                </div>
            </div>
        </div>

        <?php
        echo '<hr/>';
        echo '<div class="text-right">';
        echo Buttons('Pay Now', 'reset');
        echo '</div>';
        ?>

    </form>
    <div id="balanceResult" class="mt-4"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#payment_method').on('change', function() {
                var method = $(this).val();
                if (method == 'ONLINE') {
                    $('#online_methods').show();
                    $('#offline_methods').hide();
                } else if (method == 'OFFLINE') {
                    $('#offline_methods').show();
                    $('#online_methods').hide();
                } else {
                    $('#online_methods').hide();
                    $('#offline_methods').hide();

                }
            });

            $('#payFeesForm').on('submit', function(event) {
                event.preventDefault();
                let student_id = $('#student_id').val();
                let student_name = $('#student_name').val(); // Get student name
                let book_fees = $('#book_fees').val();
                let tuition_fees = $('#tuition_fees').val();
                let transport_fees = $('#transport_fees').val();
                let bookpay_amount = $('#bookpay_amount').val()
                let tuitionpay_amount = $('#tuitionpay_amount').val()
                let transportpay_amount = $('#transportpay_amount').val()
                let payment_method = $('#payment_method').val();
                let payment_type = (payment_method === 'ONLINE') ? $('#online_type').val() : $('#offline_type').val();
                let instituteName=$('#instituteName').val();
                    let instituteAddress = $('#instituteAddress').val();
                    let instituteCity = $('#instituteCity').val();
                    let instituteState = $('#instituteState').val();
                    let instituteZipcode = $('#instituteZipcode').val();
                    let institutePhone = $('#institutePhone').val();
                    let userName =null;
                    let signature = null;

                $.ajax({
                    url: 'modules\\students\\StdPaymentProcess.php',
                    type: 'POST',
                    data: {
                        student_id: student_id,
                        student_name: student_name,
                        book_fees: book_fees,
                        tuition_fees: tuition_fees,
                        transport_fees: transport_fees,
                        bookpay_amount: bookpay_amount,
                        tuitionpay_amount: tuitionpay_amount,
                        transportpay_amount: transportpay_amount,
                        payment_method: payment_method,
                        payment_type: payment_type,
                        instituteName:instituteName,
                            instituteAddress: instituteAddress,
                            instituteCity: instituteCity,
                            instituteState: instituteState,
                            instituteZipcode: instituteZipcode,
                            institutePhone: institutePhone,
                            userName :userName,
                            signature:signature 
                    },
                    success: function(response) {
                        $('#balanceResult').html(response);
                    }
                });
            });

        });
    </script>

<?php
    }

    // student login //

    if (User('PROFILE') == 'parent') {

        $parentUsername = user('USERNAME');
        $userName=GetStdUserNameFromUsername($parentUsername);
        $stdname = GetNameFromUserName($userName);      // shortcut function of get student name.
        $stdid = GetStudentIdFromUserName($userName);  //shortcut function of get student id.
        $fees = GetStudentFeesFromUserName($userName); // shortcut function of get student fees.

?>
    <form id="payFeesForm" class="mt-4">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="student_id">Student ID:</label>
                <input class="form-control" id="student_id" value="<?php echo $stdid; ?>" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="student_name">Student Name:</label>
                <input class="form-control" id="student_name" value="<?php echo $stdname; ?>" readonly>
            </div>


            <table class="table table-bordered" id="fee_type" required>
                <thead>
                    <tr class='bg-grey-200'>
                        <th>Fee Type</th>
                        <th>Balance Amount</th>
                        <th>Amount to Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td value="book_fees">Book Fees</td>
                        <td><?php echo $fees['book_fees_balance']; ?></td>
                        <td><input class="form-control" id="bookpay_amount" placeholder="Enter amount" value="<?php echo ($fees['book_fees_balance'] == 0) ? '0' : '' ?>" <?php echo ($fees['book_fees_balance'] == 0) ? 'readonly' : 'required' ?>></td>
                    </tr>
                    <tr>
                        <td value="tuition_fees">Tuition Fees</td>
                        <td><?php echo $fees['tuition_fees_balance']; ?></td>
                        <td><input class="form-control" id="tuitionpay_amount" placeholder="Enter amount" value="<?php echo ($fees['tuition_fees_balance'] == 0) ? '0' : '' ?>" <?php echo ($fees['tuition_fees_balance'] == 0) ? 'readonly' : 'required' ?>></td>
                    </tr>
                    <tr>
                        <td value="transport_fees">Travel Fees</td>
                        <td><?php echo $fees['transport_fees_balance']; ?></td>
                        <td><input class="form-control" id="transportpay_amount" placeholder="Enter amount" value="<?php echo ($fees['transport_fees_balance'] == 0) ? '0' : '' ?>" <?php echo ($fees['transport_fees_balance'] == 0) ? 'readonly' : 'required' ?>></td>
                    </tr>

                </tbody>


            </table>

        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">

                <div class="form-group col-md-6">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="ONLINE">Online</option>
                        <option value="OFFLINE">Offline</option>
                    </select>
                </div>
                <div class="form-group col-md-6" id="online_methods" style="display: none;">
                    <label for="online_type">Select Online Payment Type</label>
                    <select class="form-control" id="online_type">
                        <option value="GPAY">GPay</option>
                        <option value="PHONEPAY">PhonePe</option>
                        <option value="PAYTM">Paytm</option>
                        <option value="DEBIT_CARD">Debit Card</option>
                        <option value="CREDIT_CARD">Credit Card</option>
                    </select>
                </div>
                <div class="form-group col-md-6" id="offline_methods" style="display: none;">
                    <label for="offline_type">Offline Payment Type</label>
                    <select class="form-control" id="offline_type">
                        <option value="cash">Cash</option>
                    </select>
                </div>
            </div>
        </div>

        <?php
        echo '<hr/>';
        echo '<div class="text-right">';
        echo Buttons('Pay Now', 'reset');
        echo '</div>';
        ?>

    </form>
    <div id="balanceResult" class="mt-4"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#payment_method').on('change', function() {
                var method = $(this).val();
                if (method == 'ONLINE') {
                    $('#online_methods').show();
                    $('#offline_methods').hide();
                } else if (method == 'OFFLINE') {
                    $('#offline_methods').show();
                    $('#online_methods').hide();
                } else {
                    $('#online_methods').hide();
                    $('#offline_methods').hide();

                }
            });

            $('#payFeesForm').on('submit', function(event) {
                event.preventDefault();
                let student_id = $('#student_id').val();
                let student_name = $('#student_name').val(); // Get student name
                let book_fees = $('#book_fees').val();
                let tuition_fees = $('#tuition_fees').val();
                let transport_fees = $('#transport_fees').val();
                let bookpay_amount = $('#bookpay_amount').val()
                let tuitionpay_amount = $('#tuitionpay_amount').val()
                let transportpay_amount = $('#transportpay_amount').val()
                let payment_method = $('#payment_method').val();
                let payment_type = (payment_method === 'ONLINE') ? $('#online_type').val() : $('#offline_type').val();
                let instituteName=$('#instituteName').val();
                    let instituteAddress = $('#instituteAddress').val();
                    let instituteCity = $('#instituteCity').val();
                    let instituteState = $('#instituteState').val();
                    let instituteZipcode = $('#instituteZipcode').val();
                    let institutePhone = $('#institutePhone').val();
                    let userName =null;
                    let signature = null;

                $.ajax({
                    url: 'modules\\students\\StdPaymentProcess.php',
                    type: 'POST',
                    data: {
                        student_id: student_id,
                        student_name: student_name,
                        book_fees: book_fees,
                        tuition_fees: tuition_fees,
                        transport_fees: transport_fees,
                        bookpay_amount: bookpay_amount,
                        tuitionpay_amount: tuitionpay_amount,
                        transportpay_amount: transportpay_amount,
                        payment_method: payment_method,
                        payment_type: payment_type,
                        instituteName:instituteName,
                            instituteAddress: instituteAddress,
                            instituteCity: instituteCity,
                            instituteState: instituteState,
                            instituteZipcode: instituteZipcode,
                            institutePhone: institutePhone,
                            userName :userName,
                            signature:signature 
                    },
                    success: function(response) {
                        $('#balanceResult').html(response);
                    }
                });
            });

        });
    </script>


<?php
    }  // if(user) close
?>
</body>

</html>