<?php
session_start();
include 'db_connect.php'; // Include database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}


$user_id = $_SESSION['user_id']; // Get logged-in user's ID


// Query to fetch the user's purchased tickets
$query = "
    SELECT b.booking_id, f.flight_id, f.departure_city, f.arrival_city, f.departure_date, f.arrival_date, 
           bg.baggage_type, bg.weight AS baggage_weight, bg.cost AS baggage_price, s.seat_class, s.seat_status, s.seat_id
    FROM booking b
    JOIN flights f ON b.flight_id = f.flight_id
    LEFT JOIN baggage bg ON b.user_id = bg.user_id AND b.flight_id = bg.flight_id
    LEFT JOIN seats s ON b.flight_id = s.flight_id AND b.user_id = s.user_id
    WHERE b.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user has booked any tickets
if ($result->num_rows > 0) {
    echo "<h2>Your Booked Flights</h2>";
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
                    <a href='swap_sell.php?seat_id={$row['seat_id']}&action=swap'>Swap</a> 
                    <a href='swap_sell.php?seat_id={$row['seat_id']}&action=sell'>Sell</a>
                </td>
              </tr>";
    }

    echo "</tbody> </table>";
} else {
    echo "<p>You have not booked any tickets yet.</p>";
}

$stmt->close();
$conn->close();

?>
<style>
    /* General Table Styles */
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
