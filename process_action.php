<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// added these foer debugging purposes

session_start();
//including the database to the SQL database
include 'db_connect.php';

//this checks to see if the POST is there
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit;
}

//gets the user inputs and session from the ticketID and stuff
$user_id = $_SESSION['user_id'];
$ticket_id = isset($_GET['ticket_id']) ? $_GET['ticket_id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;


//this will validate the presence of required paramaets
if (!$ticket_id || !$action) {
    echo "Invalid request.";
    exit;
}


//this is a query to get the ticket details for the logged in user
$query = "SELECT * FROM ticket_requests WHERE request_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);

$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

//this checks to see if the ticket actually exists and is for the logged in user
if (!$ticket) {
    echo "Ticket not found or you do not have permission to perform this action.";
    exit;
}

//gets the seat Id and price from the ticket details
$seat_id = $ticket['Seat_id'];
$price = $ticket['price'];  

//then this is for the sell action handling
if ($action == 'sell') {

    //this bascially ensures that it is in pending state before proceeding
    if ($ticket['status'] != 'Pending') {
        echo "This ticket cannot be sold at the moment.";
        exit;
    }

    //updating query that will shows it as completed
    $update_ticket = "UPDATE ticket_requests SET status = 'Completed' WHERE request_id = ?";
    $stmt = $conn->prepare($update_ticket);
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();

    //marks it as available and listed for sale
    $update_seat = "UPDATE seats SET is_listed_for_sale = 1, seat_status = 'Available' WHERE Seat_id = ?";
    $stmt = $conn->prepare($update_seat);
    $stmt->bind_param("i", $seat_id);
    $stmt->execute();

    // it will redirect the user ot the ticket view page with success message
    header("Location: view_ticket.php?status=success");
    exit;

} elseif ($action == 'buy') { //this handles the buy option

    //marks it as completed
    $update_ticket = "UPDATE ticket_requests SET status = 'Completed' WHERE request_id = ?";
    $stmt = $conn->prepare($update_ticket);
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();

    // Update seat status to sold
    $update_seat = "UPDATE seats SET seat_status = 'Sold', user_id = ? WHERE Seat_id = ?";
    $stmt = $conn->prepare($update_seat);
    $stmt->bind_param("ii", $user_id, $seat_id);
    $stmt->execute();

    header("Location: view_ticket.php?status=success");
    exit;

} elseif ($action == 'swap') { // handles the swap method

    //updates the ticket to say completed
    $update_ticket = "UPDATE ticket_requests SET status = 'Completed' WHERE request_id = ?";
    $stmt = $conn->prepare($update_ticket);
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();

    //updates the seat status to swapped and removes it

    $update_seat = "UPDATE seats SET seat_status = 'Swapped', user_id = NULL WHERE Seat_id = ?";
    $stmt = $conn->prepare($update_seat);
    $stmt->bind_param("i", $seat_id);
    $stmt->execute();

    header("Location: view_ticket.php?status=success");
    exit;
} else {
    echo "Invalid action.";
}
?>