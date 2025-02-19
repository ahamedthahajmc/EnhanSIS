<?php
include('../../Data.php');

try {
    $conn = new PDO("mysql:host=$DatabaseServer;dbname=$DatabaseName;port=$DatabasePort", $DatabaseUsername, $DatabasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM bus_transport_fees ORDER BY id DESC");
    $transport_fees_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($transport_fees_data as $row) {
        echo "<tr data-id='{$row['id']}'>
                <td>" . htmlspecialchars($row['place']) . "</td>
                <td>" . htmlspecialchars($row['bus_no']) . "</td>
                <td>" . htmlspecialchars($row['transport_fees']) . "</td>
                <td>
                    <button class='btn btn-warning btn-sm editBtn'>Edit</button>
                </td>
              </tr>";
    }
} catch (PDOException $e) {
    echo 'Error retrieving data: ' . $e->getMessage();
    exit;
}
?>
