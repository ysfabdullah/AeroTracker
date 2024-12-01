<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Retrieve flight_id from the form
if (isset($_POST['flight_id'])) {
    $flight_id = $_POST['flight_id'];
    $user_id = $_SESSION['user_id']; 


    $checkQuery = "SELECT * FROM flights WHERE flight_id = ?";
    $stmt = $conn->prepare($checkQuery);

    
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }
    
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $flightExists = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if (!$flightExists) {
        echo "Flight not found.";
        exit();
    }


// Check if the user has already booked this flight
$checkBookingQuery = "SELECT * FROM booking WHERE user_id = ? AND flight_id = ?";
$stmt = $conn->prepare($checkBookingQuery);
$stmt->bind_param("ii", $user_id, $flight_id);
$stmt->execute();
$existingBooking = $stmt->get_result()->num_rows > 0;
$stmt->close();

if ($existingBooking) {
    echo "You have already booked this flight.";
    echo "<a href='search.html'> Go back to Search Flights</a>";
    exit();

}

// Proceed with booking the flight if not already booked
$query = "INSERT INTO booking (user_id, flight_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $flight_id);


if ($stmt->execute()) {
    // Booking successful, now add baggage info
    // Get baggage details from form
    if (isset($_POST['baggage_type'])) {
        $baggage_type = $_POST['baggage_type'];
        $weight = $_POST['weight'];

        $price = 0;
        switch ($baggage_type) {
            case 'Carry-On':
                $price = 0; // No cost for Carry-On
                break;
            case 'Checked':
                $price = 20; // $20 for Checked
                break;
            case 'Excess':
                $price = 50; // $50 for Excess
                break;
            default:
                $price = 0;    
        }
       

        // Insert baggage details into baggage table
        $baggage_query = "INSERT INTO baggage (user_id, flight_id, baggage_type, weight, cost) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($baggage_query);
        $stmt->bind_param("iisdi", $user_id, $flight_id, $baggage_type, $weight, $price);
        $stmt->execute();
    }

    if (isset($_POST['seat_class'])) {
        $seat_class = $_POST['seat_class'];

        // Check for available seats for the flight
        $seatQuery = "SELECT * FROM seats WHERE flight_id = ? AND seat_status = 'Available' LIMIT 1";
        $stmt = $conn->prepare($seatQuery);
        $stmt->bind_param("i", $flight_id);
        $stmt->execute();
        $availableSeat = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($availableSeat) {
            // Insert seat details into the Seats table (update seat status to 'Sold')
            $seat_status = 'Sold'; // Once booked, the seat is sold
            $seatQuery = "UPDATE seats SET user_id = ?, seat_class = ?, seat_status = ? WHERE seat_id = ?";
            $stmt = $conn->prepare($seatQuery);
            $stmt->bind_param("issi", $user_id, $seat_class, $seat_status, $availableSeat['Seat_id']);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "No available seats for this flight.";
            echo "<p><a href='search.html'>Return to Search</a></p>"; 
            exit();
        }
    }


    header('Location:flexitickets.php');
    exit();
} else {
    echo "Error booking flight: " . $stmt->error;
}
$stmt->close();
} else {
echo "No flight selected.";
}

$conn->close();
?>