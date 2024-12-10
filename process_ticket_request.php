<?php

session_start();

//this is here to connect the php to our database on myphpadmin
include 'db_connect.php';


if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit;
}

//gets the user id
$user_id = $_SESSION['user_id'];
$seat_id = $_POST['seat_id']; 
$action = $_POST['action'];
$price = isset($_POST['price']) ? $_POST['price'] : null;
$requested_seat_id = isset($_POST['requested_seat_id']) ? $_POST['requested_seat_id'] : null;


//this will also set up the query and inserts data
$query = "SELECT * FROM seats WHERE Seat_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $seat_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$seat = $result->fetch_assoc();


//if the seat is not found, then this will handle it
if (!$seat) {
    echo "Seat not found or you don't own this seat.";
    exit;
}


// this checks if the price is less than 0 and action is sell
if ($action == 'Sell' && $price <= 0) {
    echo "Please provide a valid price for selling the seat.";
    exit();
}


//this is the sql query that will insert data into the ticket_requests 

$query = "INSERT INTO ticket_requests (user_id, Seat_id, flight_id, action, price, status, requested_seat_id) 
          VALUES (?, ?, (SELECT flight_id FROM seats WHERE Seat_id = ? LIMIT 1), ?, ?, 'Pending', ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiisss", $user_id, $seat_id, $seat_id, $action, $price, $requested_seat_id);


if ($stmt->execute()) {

    // if the button is sell then it will updated into the seats. 
    if ($action == 'Sell') {
        $update_seat_status = "UPDATE seats SET is_listed_for_sale = 1, seat_status = 'Available' WHERE Seat_id = ?";
        $seat_stmt = $conn->prepare($update_seat_status);
        $seat_stmt->bind_param("i", $seat_id);
        $seat_stmt->execute();
    }

    header("Location: view_ticket.php?status=success");
} else {
    echo "Error processing ticket request.";
}
exit();
?>