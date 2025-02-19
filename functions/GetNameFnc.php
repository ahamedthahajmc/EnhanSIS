<?php

function GetNameFromUserName($userName)
{

    $q = "Select * from login_authentication where username='" . $userName . "'";

    $userProfile =  DBGet(DBQuery($q));

    $userProfileId = $userProfile[1]['PROFILE_ID'];
    $UserId = $userProfile[1]['USER_ID'];

    if ($userProfileId != 3 || $userProfileId != 4) {
        $nameQuery = "Select CONCAT(first_name,' ', last_name) name from staff where profile_id='" . $userProfileId . "' and staff_id='" . $UserId . "'";
    }
    if ($userProfileId == 3) {
        $nameQuery = "Select CONCAT(first_name,' ', last_name) name from students where student_id='" . $UserId . "'";
    }
    if ($userProfileId == 4) {

        $nameQuery = "Select CONCAT(first_name,' ', last_name) name from people where profile_id='" . $userProfileId . "' and staff_id='" . $UserId . "'";
    }
    $name =  DBGet(DBQuery($nameQuery));
    $name = $name[1]['NAME'];
    return $name;
}

function GetStudentIdFromUserName($userName)
{
    // Query to get user profile based on username
    $q = "Select * from login_authentication where username='" . $userName . "'";

    $userProfile = DBGet(DBQuery($q));

    $userProfileId = $userProfile[1]['PROFILE_ID'];
    $UserId = $userProfile[1]['USER_ID'];

    // Initialize variable to store the student ID query
    $idQuery = "";

    if ($userProfileId == 3) // Check if the profile is for a student
    {
        // Fetch student ID from the students table
        $idQuery = "Select student_id from students where student_id='" . $UserId . "'";
    }

    if ($idQuery) {
        // Execute the query and retrieve the student ID
        $result = DBGet(DBQuery($idQuery));
        $studentId = $result[1]['STUDENT_ID'];

        return $studentId;
    }

    // Return null or an error message if not a student
    return null;
}


function GetStudentFeesFromUserName($userName)
{
    // Query to get user profile based on username
    $q = "Select * from login_authentication where username='" . $userName . "'";

    $userProfile = DBGet(DBQuery($q));

    $userProfileId = $userProfile[1]['PROFILE_ID'];
    $UserId = $userProfile[1]['USER_ID'];

    // Initialize variable to store the student fees query
    $feesQuery = "";

    if ($userProfileId == 3) // Check if the profile is for a student
    {
        // Fetch student fees from the fees_details table
        $feesQuery = "Select book_fees_amount, tuition_fees_amount, transport_fees_amount from fees_details where student_id='" . $UserId . "'";
    }

    if ($feesQuery) {
        // Execute the query and retrieve the fees details
        $result = DBGet(DBQuery($feesQuery));

        $bookFeesAmount = $result[1]['BOOK_FEES_AMOUNT'];
        $tuitionFeesAmount = $result[1]['TUITION_FEES_AMOUNT'];
        $transportFeesAmount = $result[1]['TRANSPORT_FEES_AMOUNT'];
        $feespaidQuary = "
    SELECT 
        SUM(book_fees_paid) AS total_book_fees,
        SUM(tuition_fees_paid) AS total_tuition_fees,
        SUM(transport_fees_paid) AS total_transport_fees
    FROM fee_payment 
    WHERE student_id ='" . $UserId . "'";

        $fees = $result = DBGet(DBQuery($feespaidQuary));

        $totalBookFees = $fees[1]['TOTAL_BOOK_FEES'] ?? 0;
        $totalTuitionFees = $fees[1]['TOTAL_TUITION_FEES'] ?? 0;
        $totalTransportFees = $fees[1]['TOTAL_TRANSPORT_FEES'] ?? 0;
        $bookFees = $bookFeesAmount - $totalBookFees;
        $tuitionFees = $tuitionFeesAmount - $totalTuitionFees;
        $transportFees = $transportFeesAmount - $totalTransportFees;
        // Return the fees as an associative array
        return array(
            'book_fees_balance' => $bookFees,
            'tuition_fees_balance' => $tuitionFees,
            'transport_fees_balance' => $transportFees
        );
    }

    // Return null or an error message if not a student
    return null;
}



function GetStudentPaymentFromUserName($userName)
{
    // Query the login_authentication table to get the user details
    $q = "SELECT * FROM login_authentication WHERE username = '" . $userName . "'";
    $userProfile = DBGet(DBQuery($q));

    // Check if user profile is found
    if (!empty($userProfile)) {
        $userProfileId = $userProfile[1]['PROFILE_ID'];
        $UserId = $userProfile[1]['USER_ID'];

        $feesDetailsQuery = "";

        if ($userProfileId == 3) {
            $feesDetailsQuery = "SELECT fees_id FROM fees_details WHERE student_id='" . $UserId . "'";
        }

        // Execute the query to retrieve the fees details
        if ($feesDetailsQuery) {
            $feesDetailsResult = DBGet(DBQuery($feesDetailsQuery));

            if (!empty($feesDetailsResult) && isset($feesDetailsResult[1])) {
                $feesId = $feesDetailsResult[1]['FEES_ID']; // Accessing the correct key (FEES_ID)

                $feesQuery = "SELECT payment_type, payment_method, payment_date, amount_paid FROM fee_payment
                              WHERE fees_id = '" . $feesId . "' 
                              ORDER BY payment_date DESC LIMIT 1"; // Fetch the latest payment

                // Execute the payment details query
                $result = DBGet(DBQuery($feesQuery));

                // Debugging output for payment result
                // print_r($result); // Print raw payment result for inspection

                if (!empty($result) && isset($result[1])) {
                    // Assign to keys you will use in the UI
                    return array(
                        'payment_method' => $result[1]['PAYMENT_METHOD'],
                        'payment_type' => $result[1]['PAYMENT_TYPE'],
                        'amount_paid' => $result[1]['AMOUNT_PAID'],
                        'payment_date' => $result[1]['PAYMENT_DATE'],

                    );
                }
            }
        }
    }
}


function GetInstituteTitle($instituteId)
{
    $q = "SELECT * FROM institutes WHERE id = '" . $instituteId . "'";

    $result =  DBGet(DBQuery($q));
    $institutes = $result[1]['TITLE'];
    return $institutes;
}


function GetInstituteAddressFromInstituteId($instituteId)
{

    $q = "Select * from institutes where id='" . $instituteId . "'";

    $instituteDetails =  DBGet(DBQuery($q));

    if (!empty($instituteDetails)) {
        $address = $instituteDetails[1]['ADDRESS'];
        $city = $instituteDetails[1]['CITY'];
        $state = $instituteDetails[1]['STATE'];
        $zipcode = $instituteDetails[1]['ZIPCODE'];
        $phone = $instituteDetails[1]['PHONE'];
        $principal = $instituteDetails[1]['PRINCIPAL'];

        // Return the fees as an associative array
        return array(
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zipcode' => $zipcode,
            'phone' => $phone,
            'principal' => $principal
        );
    }
}





function GetStudentFeesFromUser($userName)
{
    // Query to get user profile based on username
    $q = "Select * from login_authentication where username='" . $userName . "'";

    $userProfile = DBGet(DBQuery($q));

    $userProfileId = $userProfile[1]['PROFILE_ID'];
    $UserId = $userProfile[1]['USER_ID'];

    $feesQuery = "";

    if ($userProfileId == 3) {
        $feesQuery = "Select total_fees from fees_details where student_id='" . $UserId . "'";
        
    }

    if ($feesQuery) {
        $result = DBGet(DBQuery($feesQuery));

        
        $TotalFees = $result[1]['TOTAL_FEES'];
      $feesQuery = "SELECT SUM(amount_paid) AS total_paid FROM fee_payment 
                      WHERE student_id='" . $UserId . "'";

     $result = DBGet(DBQuery($feesQuery));

     $TotalFeesPaid = $result[1]['TOTAL_PAID'];

     $LastValue= $TotalFees - $TotalFeesPaid;

        // Return the fees as an associative array
        return array(
            'total_fees' => $LastValue,

        );
    }
}


function GetNameFromGrade($grade)
{
    // Query to get the grade level ID based on the grade title

    $q = "SELECT id FROM institute_gradelevels WHERE title = '" . $grade . "'";
    $grade_result = DBGet(DBQuery($q));


    // Check if the grade exists
    if (!empty($grade_result)) {

        $grade_id = $grade_result[1]["ID"];


        $student_query = "SELECT student_id FROM student_enrollment WHERE grade_id='" . $grade_id . "'";
        $student_ids = DBGet(DBQuery($student_query));

        // Check if any students are found
        if (!empty($student_ids)) {
            $student_id_list = array();
            foreach ($student_ids as $student) {
                $student_id_list[] = $student['STUDENT_ID'];
            }

            // Convert the list of student IDs into a comma-separated string
            $student_ids_str = implode(",", $student_id_list);
            return $student_ids_str;
        } else {
            // Return a message if no students found
            return "No students found for the grade: " . $grade;
        }
    } else {
        // Return a message if no student IDs are found
        return "No students found for the grade: " . $grade;
    }
}

// studentName From Student Name
function GetStudentNameFromName($last, $first)
{
    // Query to get user profile based on first and last name
    $q = "SELECT CONCAT(first_name, ' ', last_name) AS name FROM students WHERE last_name = '" . $last . "' AND first_name = '" . $first . "'";
    $studentName = DBGet(DBQuery($q));

    // Check if a name was retrieved
    if (!empty($studentName)) {
        return $studentName;
    }

    // Return null or an error message if not found
    return null;
}

function GetStudentFeesFromName($lastName, $firstName)
{
    // Query to get the student_id
    $q = "SELECT student_id FROM students WHERE first_name = '" . $firstName . "' AND last_name = '" . $lastName . "'";

    $studentId = DBGet(DBQuery($q));

    $feesQuery = "";

    if (!empty($studentId)) {
        // Assuming the correct column name is 'student_id'
        $student_id = $studentId[1]["student_id"];

        // Query to get the fees details
        $feesQuery = "SELECT book_fees_amount, tuition_fees_amount, transport_fees_amount FROM fees_details WHERE student_id = '" . $student_id . "'";
    }

    if (!empty($feesQuery)) {
        // Execute the query and retrieve the fees details
        $result = DBGet(DBQuery($feesQuery));

        // Check if result exists
        if (!empty($result)) {
            // Retrieve the different fees
            $bookFees = $result[1]['book_fees_amount'];
            $tuitionFees = $result[1]['tuition_fees_amount'];
            $transportFees = $result[1]['transport_fees_amount'];

            // Return the fees as an associative array
            return array(
                'book_fees_amount' => $bookFees,
                'tuition_fees_amount' => $tuitionFees,
                'transport_fees_amount' => $transportFees
            );
        }
    }

    // Return null if no data is found
    return null;
}

function GetStdUserNameFromUsername($parentUsername)
{

    $q = "Select * from login_authentication where username='" . $parentUsername . "'";

    $userProfile =  DBGet(DBQuery($q));

    $UserId = $userProfile[1]['USER_ID'];
    if ($UserId) {
        $idQuery = "Select student_id from students_join_people where id='" . $UserId . "'";
    }
    $id =  DBGet(DBQuery($idQuery));
    $stdId = $id[1]['STUDENT_ID'];
    if ($stdId) {

        $username = "Select username from login_authentication where user_id='" . $stdId . "'";
    }
    $name =  DBGet(DBQuery($username));
    $stdUsername = $name[1]['USERNAME'];
    return $stdUsername;
}
