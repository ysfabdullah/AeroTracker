<?php
session_start();

//this is here to connect the php to our database on myphpadmin
include 'db_connect.php'; 


$user_id = $_SESSION['user_id'];
//to ensure that a user is logged in

if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo "<p style='color: green;'>Your request was processed successfully!</p>";
}

//this is the title for the page called ticket requsrs
echo "<h2>Your Ticket Requests</h2>";


//this selects the information and places it in the database
$query = "SELECT tr.request_id, tr.action, s.Seat_id, tr.price, tr.status
          FROM ticket_requests tr
          JOIN seats s ON tr.Seat_id = s.Seat_id
          WHERE tr.user_id = ? AND tr.status = 'Pending'";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// these are also the different columns in the table and shows them here
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Action</th>
                <th>Seat ID</th>
                <th>Price</th>
                <th>Status</th>
                <th>Options</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['action']}</td>
                <td>{$row['Seat_id']}</td>
                <td>" . ($row['price'] ? "$" . $row['price'] : "N/A") . "</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='process_action.php?action=sell&ticket_id={$row['request_id']}'>Sell Ticket</a> | 
                    <a href='process_action.php?action=swap&ticket_id={$row['request_id']}'>Request Swap</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    //this is for handling errors as well
    echo "<p>You have no pending ticket requests.</p>";
    //then there is a link that will take you back to the main page
    echo "<a href='index.html'>Go back to Main Page</a>";
}
?>


