<?php 

include("../../lang/lang_en.php");
include('../../Data.php');
$conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ensure idval is set, otherwise, handle it gracefully
if (!isset($_GET['idval']) || empty($_GET['idval'])) {
    die("Error: Missing staff ID (idval).");
}

$staffId = $_GET['idval'];

$staffs_qry = 'SELECT * FROM staff WHERE staff_id = :staff_id';
$stmt = $conn->prepare($staffs_qry);
$stmt->execute([':staff_id' => $staffId]); // Use parameterized query to prevent SQL injection
$staff = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the staff

$staffs_att_qry = 'SELECT st.first_name, st.last_name, sa.attendance_category, sa.comments, sa.date FROM `staff` st LEFT JOIN staff_attendance sa ON st.staff_id = sa.staff_id WHERE st.staff_id = :staff_id';
$stmt = $conn->prepare($staffs_att_qry);
$stmt->execute([':staff_id' => $staffId]); // Use parameterized query to prevent SQL injection
$staff_att_data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the staff




echo '
<form id="staffAttendanceForm" class="ovr-x">
        <table id="typeAddTable" class="table table-bordered m-b-15">
            <thead>
                <tr class="bg-grey-200">
                    <th>Staff Name \ Days</th>';

                    // Generate table headers dynamically from 1 to 31
                    for ($i = 1; $i <= 31; $i++) {
                        echo "<th>$i</th>";
                    }

echo '      </tr>
                <tr class="bg-grey-200">
                    <th>'.$staff['first_name'].' '.$staff['last_name'].'</th>';

                    // Initialize an empty array to store attendance data by day
                    $attendanceData = [];

                    // Populate the array with attendance records
                    foreach ($staff_att_data as $stval) {
                        // print_r($stval);
                        // die;
                        $day = date("j", strtotime($stval['date'])); // Extract the day (1-31)
                        $attendanceData[$day] = [
                            'category' => $stval['attendance_category'], 
                            'comments' => isset($stval['comments']) ? htmlspecialchars($stval['comments'], ENT_QUOTES) : ''
                        ];
                    }

                    // Loop through all 31 days
                    for ($day = 1; $day <= 31; $day++) {
                        $class = "";
                        $title = "";

                        if (isset($attendanceData[$day])) {
                            // Assign class based on attendance category
                            switch ($attendanceData[$day]['category']) {
                                case 1:
                                    $class = "present-clr";
                                    break;
                                case 2:
                                    $class = "absent-clr";
                                    break;
                                case 3:
                                    $class = "tardy-clr";
                                    break;
                                case 4:
                                    $class = "partial-clr";
                                    break;
                                case 5:
                                    $class = "early-leave-clr";
                                    break;
                                case 6:
                                    $class = "others-clr";
                                    break;
                            }

                            // Assign comment as tooltip
                            if (!empty($attendanceData[$day]['comments'])) {
                                $title = "title='{$attendanceData[$day]['comments']}'";
                            }
                        }

                        echo "<td class='$class' $title></td>"; // Output the cell with the class and tooltip
                    }

echo '      </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </form>
    
    
<div class="col-md-12">
<div class="col-md-6">
</div>
<div class="col-md-6">
<div class="col-md-6">

<div class="flex-align-center">
<div class="sm-box present-clr"></div><strong class="p-l-10">Present</strong></div>
<div class="flex-align-center">
<div class="sm-box absent-clr"></div><strong class="p-l-10">Absent</strong></div>
<div class="flex-align-center">
<div class="sm-box tardy-clr"></div><strong class="p-l-10">Tardy</strong></div>
</div>

<div class="col-md-6">
<div class="flex-align-center">
<div class="sm-box partial-clr"></div><strong class="p-l-10">Partially Present</strong></div>
<div class="flex-align-center">
<div class="sm-box early-leave-clr"></div><strong class="p-l-10">Early Leave</strong></div>
<div class="flex-align-center">
<div class="sm-box others-clr"></div><strong class="p-l-10">Others</strong></div>
</div>
</div></div>
    ';
?>

