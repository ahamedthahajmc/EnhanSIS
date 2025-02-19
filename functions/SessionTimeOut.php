<?php
// session_start();

// // Set the timeout duration (in seconds)
// $timeout_duration = 1200; // 20 minutes

// // Check if the last activity timestamp is set in the session
// if (isset($_SESSION['LAST_ACTIVITY'])) {
//     // Calculate the time difference since the last activity
//     $time_elapsed = time() - $_SESSION['LAST_ACTIVITY'];

//     // If the elapsed time exceeds the timeout duration, destroy the session
//     if ($time_elapsed > $timeout_duration) {
//         session_unset();     // Unset session variables
//         session_destroy();   // Destroy the session
//         header("Location: login.php"); // Redirect to login page
//         exit();
//     }
// }

// // Update the last activity timestamp
// $_SESSION['LAST_ACTIVITY'] = time();
?> 
