<?php
// Database connection
include 'db_connection.php';

// Get Call ID from URL
$call_id = $_GET['call_id'];

// Fetch call details from the database
$query = $pdo->prepare("SELECT * FROM call_logs WHERE call_id = :call_id");
$query->execute(['call_id' => $call_id]);
$call = $query->fetch(PDO::FETCH_ASSOC);

// Check if call details exist
if ($call):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Details - <?= htmlspecialchars($call['call_id']); ?></title>
    <style>
        .details {
            padding: 20px;
            background: #fff;
            margin: 20px auto;
            width: 90%;
            max-width: 600px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .details h2 {
            margin-bottom: 20px;
        }
        .details p {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="details">
        <h2>Call Details</h2>
        <p><strong>Call ID:</strong> <?= htmlspecialchars($call['call_id']); ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($call['date']); ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($call['category']); ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($call['status']); ?></p>
        <p><strong>Notes:</strong> <?= htmlspecialchars($call['notes']); ?></p>
        <a href="index.php">Back to Call Logs</a>
    </div>
</body>
</html>
<?php
else:
    echo "<p>Call details not found!</p>";
endif;
?>
