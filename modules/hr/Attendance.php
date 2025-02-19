<?php
PopTable('header', _attendance);
include('../../Data.php');
include('../../functions/ScrollToTop.php');

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
$staffs = DBGet(DBQuery("SELECT stf.staff_id, stf.first_name, stf.last_name, COALESCE(sa.attendance_category, 0) AS category, COALESCE(sa.comments, '') AS comments FROM staff stf LEFT JOIN staff_attendance sa ON stf.staff_id = sa.staff_id AND sa.date = '" . $now . "'"));


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
    </style>
</head>

<body>
    <br>

    <form id="staffAttendanceForm">
        <table id="typeAddTable" class="table table-bordered m-b-15">
            <thead>
                <tr class="bg-grey-200">
                    <th>Staff Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Tardy</th>
                    <th>Partially present</th>
                    <th>Early Leave</th>
                    <th>Others</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($staff_val as $name) {
                    $category = isset($name['CATEGORY']) ? $name['CATEGORY'] : null; // Get category

                    echo '<tr data-id="' . $name['STAFF_ID'] . '">
        <td>' . htmlspecialchars($name['FIRST_NAME'] . ' ' . $name['LAST_NAME']) . '</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="present_' . $name['STAFF_ID'] . '" id="present1" onclick="toggleRadio(this)" ' . ($category == 1 ? 'checked' : '') . '>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="present_' . $name['STAFF_ID'] . '" id="present2" onclick="toggleRadio(this)" ' . ($category == 2 ? 'checked' : '') . '>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="present_' . $name['STAFF_ID'] . '" id="present3" onclick="toggleRadio(this)" ' . ($category == 3 ? 'checked' : '') . '>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="present_' . $name['STAFF_ID'] . '" id="present4" onclick="toggleRadio(this)" ' . ($category == 4 ? 'checked' : '') . '>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="present_' . $name['STAFF_ID'] . '" id="present5" onclick="toggleRadio(this)" ' . ($category == 5 ? 'checked' : '') . '>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="present_' . $name['STAFF_ID'] . '" id="present6" onclick="toggleRadio(this)" ' . ($category == 6 ? 'checked' : '') . '>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <textarea class="form-control resize_none" rows="1" type="text" name="present_comment" id="present7">' . htmlspecialchars($name['COMMENTS']) . '</textarea>
            </div>
        </td>
    </tr>';
                }
                ?>



            </tbody>
        </table>
    </form>



    <script>
        var present_val = '';

        function toggleRadio(selectedId) {
            let row = selectedId.closest("tr");
            let staffId = row.getAttribute("data-id");

            document.querySelectorAll('input[name="present"]').forEach(radio => {
                radio.checked = (radio.id === selectedId);
            });
            switch (selectedId.id) {
                case "present1":
                    present_val = 1;
                    break;

                case "present2":
                    present_val = 2;
                    break;

                case "present3":
                    present_val = 3;
                    break;

                case "present4":
                    present_val = 4;
                    break;

                case "present5":
                    present_val = 5;
                    break;

                case "present6":
                    present_val = 6;
                    break;

            }
            console.log(present_val);

            $.ajax({
                url: 'modules/hr/AddAttendance.php',
                type: 'POST',
                data: {
                    staff_id: staffId,
                    present_val: present_val ? present_val : "",
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log(response.message);
                    } else {
                        console.error(response.message);
                    }
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });


        }
        $(document).ready(function() {
            let timeoutId;

            $('textarea#present7').on('change', function(event) {
                event.preventDefault(); // Prevent default action immediately
                clearTimeout(timeoutId); // Clear any existing timeout

                timeoutId = setTimeout(() => {
                    const row = $(this).closest('tr');
                    let staffId = row.data('id');

                    $.ajax({
                        url: 'modules/hr/AddAttendance.php',
                        type: 'POST',
                        data: {
                            present_val: "",
                            staff_id: staffId,
                            comments: event.target.value,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                console.log(response.message);
                            } else {
                                console.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error("AJAX Error:", xhr.responseText);
                        }
                    });

                }, 1000); // Delay AJAX call by 2 seconds
            });

            $('#submit').on('click', function() {
                location.reload();
            });
        });
        scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
    </script>

</body>

</html>