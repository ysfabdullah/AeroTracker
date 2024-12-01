<?php
include 'db_connection.php';
session_start(); // Ensure session is started

$action = $_POST['action'];
$seat_id = $_POST['seat_id'];
$user_id = $_SESSION['user_id']; // Assumes user is logged in

if ($action == 'swap') {
    $requested_seat_id = $_POST['requested_seat_id'];

    // Use a prepared statement for swap request
    $query = "INSERT INTO Swap_Request (seat_id, requested_seat_id, user_id) 
              VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $seat_id, $requested_seat_id, $user_id); // "iii" for integers
    if ($stmt->execute()) {
        echo "Swap request submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
} elseif ($action == 'sell') {
    $price = $_POST['price'];

    // Use a prepared statement for sell request
    $query = "INSERT INTO Sell_Request (seat_id, user_id, price) 
              VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iid", $seat_id, $user_id, $price); // "iid" for integer and decimal
    if ($stmt->execute()) {
        echo "Seat listed for sale successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

