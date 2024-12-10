<?php
session_start();
//this is here to connect the php to our database on myphpadmin
include 'db_connect.php'; 

//this redirects the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

//gets the user_id from the logged in session
$user_id = $_SESSION['user_id']; 


//here is the sQL query that is for bookings
$query = "
    SELECT b.booking_id, f.flight_id, f.departure_city, f.arrival_city, f.departure_date, f.arrival_date, 
           bg.baggage_type, bg.weight AS baggage_weight, bg.cost AS baggage_price, s.seat_class, s.seat_status, s.seat_id
    FROM booking b
    JOIN flights f ON b.flight_id = f.flight_id
    LEFT JOIN baggage bg ON b.user_id = bg.user_id AND b.flight_id = bg.flight_id
    LEFT JOIN seats s ON b.flight_id = s.flight_id AND b.user_id = s.user_id
    WHERE b.user_id = ?
";

$stmt = $conn->prepare($query);// this is to prepare the SQL statement
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


//checks to see if there are any bookings for the user
if ($result->num_rows > 0) {
    echo "<h2>Your Booked Flights</h2>"; // this is the header for that page
    echo "<table class='flight-table'>
            <thead>
            <tr>
                <th>Booking ID</th>
                <th>Departure City</th>
                <th>Arrival City</th>
                <th>Trip Type</th>
                <th>Departure Date</th>
                <th>Arrival Date</th>
                <th>Number of Passengers</th>
                <th>Baggage Type</th>
                <th>Baggage Weight (kg)</th>
                <th>Baggage Price ($)</th>
                <th>Seat Class</th>
                <th>Seat Status</th>

            </tr>
        </thead>
        <tbody>";
     

    echo "<h2>Booking successful! Your flight has been booked and baggage details saved.</h2>";   
    echo "<p><a href='index.html'>Return to Homepage</a></p>";    

    //this will look through each of the bookings and then display the details for such flight
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['booking_id']}</td>
                <td>{$row['departure_city']}</td>
                <td>{$row['arrival_city']}</td>
                <td>{$row['trip_type']}</td>
                <td>{$row['departure_date']}</td>
                <td>{$row['arrival_date']}</td>
                <td>{$row['num_passengers']}</td>
                <td>{$row['baggage_type']}</td>
                <td>{$row['baggage_weight']}</td>
                <td>{$row['baggage_price']}</td>
                <td>{$row['seat_class']}</td>
                <td>{$row['seat_status']}</td>
                <td>
                    <form action='process_ticket_request.php' method='POST'>
                        <input type='hidde' name='seat_id' value='{$row['seat_id']}'>
                        <input type='hidden' name='action' value='swap'>
                        <button type='submit'>Swap</button>
                    </form>
                    <form action='process_ticket_request.php' method='POST'>
                        <input type='hidden' name='seat_id' value='{$row['seat_id']}'>
                        <input type='hidden' name='action' value='sell'>
                        <button type='submit'>Sell</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</tbody> </table>"; // table will close
} else {
    //if no flight is found then it will display a message
    echo "<p>You have not booked any tickets yet.</p>";
}

$stmt->close(); // closes the statement and the database 
$conn->close();



//here is some css to make the output look appealing to the user. 
?>

<style>

    table.flight-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: Arial, sans-serif;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    table.flight-table th, table.flight-table td {
        padding: 12px 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    table.flight-table th {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
    }

    table.flight-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table.flight-table tr:hover {
        background-color: #f1f1f1;
    }

    table.flight-table tbody tr td {
        font-size: 14px;
    }
</style>
