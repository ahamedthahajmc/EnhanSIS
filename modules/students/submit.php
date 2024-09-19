<!-- <?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "opensis";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Get form data
// $student_id = $_POST['student_id'];
// $last_name = $_POST['last_name'];
// $first_name = $_POST['first_name'];
// $alt_id = $_POST['alt_id'];
// $fees_section = $_POST['fees_section'];

// // Prepare the query with dynamic filters
// $sql = "SELECT * FROM fees WHERE $student_id=1";

// if (!empty($student_id)) {
//     $sql .= " AND student_id = '$student_id'";
// }

// if (!empty($last_name)) {
//     $sql .= " AND last_name = '$last_name'";
// }
// if (!empty($first_name)) {
//     $sql .= " AND first_name = '$first_name'";
// }

// if (!empty($alt_id)) {
//     $sql .= " AND alt_id = '$alt_id'";
// }
// if (!empty($fees_section)) {
//     $sql .= " AND fees_section = '$fees_section'";
// }

// // Execute the query
// $result = $conn->query($sql);
?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Fees Results</title>
    <style>
         table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
         th { background-color: #f5f5f5; }
     </style>
</head>
 <body>
     <h1>Fees Results</h1>

//     <table>
//         <thead>
//             <tr>
                    <th>Student ID</th>
//                 <th>Last Name</th>
//                 <th>First Name</th>
//                 
//                 <th>Alt ID</th>
//                 <th>Fees</th>
//                 
//             </tr>
//         </thead>
              <tbody>
            <?php
            // Check if any results were returned
            // if ($result->num_rows > 0) {
            //     // Output data for each row
            //     while ($row = $result->fetch_assoc()) {
            //         echo "<tr>

            //                  <td>{$row['student_id']}</td>
            //                 <td>{$row['last_name']}</td>
            //                 <td>{$row['first_name']}</td>
            //                 <td>{$row['alt_id']}</td>
            //                 <td>{$row['Fees']}</td>
                          
            //               </tr>";
            //     }
            // } else {
            //     echo "<tr><td colspan='7'>No results found</td></tr>";
            // }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

?>
 -->
