<?php
session_start();

//this is here to connect the php to our database on myphpadmin
include 'db_connect.php'; 

//to ensure that a user is logged in
if (!isset($_SESSION['user_id'])) {
    //it will take you to the login page if u are not logged in
    header("Location: login.html");
    exit();
}

//gets the users login information
$user_id = $_SESSION['user_id']; 


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['flight_id'])) {
    $flight_id = $_POST['flight_id']; // flightid
    $num_passengers = $_POST['num_passengers']; //number of passengers

   
    //creats the query and gets ready to insert into the booking table 
    $booking_query = "INSERT INTO booking (user_id, flight_id, num_passengers, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($booking_query);
    $status = 'pending'; 
    $stmt->bind_param("iiis", $user_id, $flight_id, $num_passengers, $status);

    if ($stmt->execute()) {
        
        header("Location: flexitickets.php");
        exit;
    } else {
        //another error message checking

        echo "<p>Sorry, there was an error processing your booking. Please try again later.</p>";
    }
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $departure_city = $_POST['departure_city'];
    $arrival_city = $_POST['arrival_city'];
    $trip_type = $_POST['trip_type'];
    $departure_date = $_POST['departure_date'];
    $return_date = ($_POST['return_date']) ?? null;
    $num_passengers = $_POST['num_passengers'];


    $query = "SELECT * FROM flights 
              WHERE departure_city = ? 
                AND arrival_city = ? 
                AND trip_type = ? 
                AND departure_date = ?";

    //for the trip type
    if ($trip_type == 'round-trip') {
        $query .= " AND arrival_date = ?";
    }

    $stmt = $conn->prepare($query);

    //if they select either round trip and a date for it 
    if ($trip_type == 'round-trip' && $return_date) {
        $stmt->bind_param("sssss", $departure_city, $arrival_city, $trip_type, $departure_date, $return_date);
    } else {
        $stmt->bind_param("ssss", $departure_city, $arrival_city, $trip_type, $departure_date);
    }

    $stmt->execute();
    $result = $stmt->get_result();

 
    if ($result->num_rows > 0) {
        echo "<h2>Available Flights</h2>";
        // the title called availabe flights
        echo "<table class='flight-table'>
                <thead>
                <tr>
                    <th>Flight ID</th>
                    <th>Departure City</th>
                    <th>Arrival City</th>
                    <th>Trip Type</th>
                    <th>Departure Date</th>
                    <th>Arrival Date</th>
                    <th>Number of Passengers</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>"; 

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['flight_id']}</td>
                    <td>{$row['departure_city']}</td>
                    <td>{$row['arrival_city']}</td>
                    <td>{$row['trip_type']}</td>
                    <td>{$row['departure_date']}</td>
                    <td>{$row['arrival_date']}</td>
                    <td>{$row['num_passengers']}</td>
                    <td><form action='book_flight.php' method='POST'>
                        <input type='hidden' name='flight_id' value='{$row['flight_id']}'>
                        <input type='hidden' name='num_passengers' value='{$row['num_passengers']}'>
                
                        <label for='seat_class'>Select Seat Class:</label>
                        <select name='seat_class' id='seat_class' required>
                            <option value='Economy'>Economy</option>
                            <option value='Business'>Business</option>
                            <option value='First Class'>First Class</option>
                        </select>    

                        <label for='baggage_type'>Baggage Type:</label>
                        <select name='baggage_type' required>
                                <option value='Carry-On'>Carry-On</option>
                                <option value='Checked'>Checked</option>
                                <option value='Excess'>Excess</option>
                        </select>
                        <label for='weight'>Baggage Weight (kg):</label>
                        <input type='number' name='weight' min='0' step='0.1' placeholder='e.g., 5kg' required>
                
                        <button type='submit'>Book Now</button>
                    </form>
                   </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No flights match your criteria. Please try again.</p>";
    }

    $stmt->close();
}


// just some css to make it look appealing to the user
$conn->close();
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


    .book-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        text-align: center;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .book-btn:hover {
        background-color: #45a049;
    }


    @media (max-width: 768px) {
        table.flight-table {
            font-size: 14px;
            width: 100%;
        }

        table.flight-table th, table.flight-table td {
            padding: 10px;
        }

        .book-btn {
            padding: 8px 16px;
            font-size: 14px;
        }
    }
</style>


