<?php
session_start();
//starts a new sessions or if the person is already logged in


//this is here to connect the php to our database on myphpadmin
include 'db_connect.php'; 

//if the user is not already logged in, it will redirect you to login.php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}


//this checks to see if the flight_id is provided in the POST request
if (isset($_POST['flight_id'])) {
    $flight_id = $_POST['flight_id']; //this gets the flight_id from the form submission
    $user_id = $_SESSION['user_id']; // this will get the users from the session


    //this is the checking query that will bascially check to see if the flight is actually in the database
    $checkQuery = "SELECT * FROM flights WHERE flight_id = ?";
    $stmt = $conn->prepare($checkQuery);

    //some error handling
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }
    
    $stmt->bind_param("i", $flight_id); //this binds the flight Id as an integer
    $stmt->execute();
    $flightExists = $stmt->get_result()->num_rows > 0;
    //checks to see if the flight exists
    $stmt->close();


    //if the flight does not exist, it will print the error message saying not found
    if (!$flightExists) {
        echo "Flight not found.";
        exit();
    }



//here it will check to see if a user already booked a flight.
$checkBookingQuery = "SELECT * FROM booking WHERE user_id = ? AND flight_id = ?";
$stmt = $conn->prepare($checkBookingQuery);
$stmt->bind_param("ii", $user_id, $flight_id); // right here the user Id and flight ID will bind as int
$stmt->execute(); // the query gets exectuted 
$existingBooking = $stmt->get_result()->num_rows > 0; // checks to see if there is an exisiting booking
$stmt->close();//and then right here it closes the statment 


//if there is a user that already booked a flight it will show this message
//since you cannot book the same flight twice 
if ($existingBooking) {
    echo "You have already booked this flight.";
    echo "<a href='search.html'> Go back to Search Flights</a>";
    exit();

}

//inserts a new booking into the booking table 
$query = "INSERT INTO booking (user_id, flight_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $flight_id);

//if the booking is found to be successfull
if ($stmt->execute()) {
    //this again checks to see if the baggage details are provided

    if (isset($_POST['baggage_type'])) {
        $baggage_type = $_POST['baggage_type'];
        $weight = $_POST['weight'];
        //here it will add price to them
        //default price will be 0
        //then additional prices for the bags
        $price = 0;
        switch ($baggage_type) {
            case 'Carry-On':
                $price = 0; 
                break;
            case 'Checked':
                $price = 20; 
                break;
            case 'Excess':
                $price = 50; 
                break;
            default:
                $price = 0;    
        }
       
        //insertion the baggage details into the baggage table 
        $baggage_query = "INSERT INTO baggage (user_id, flight_id, baggage_type, weight, cost) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($baggage_query);
        $stmt->bind_param("iisdi", $user_id, $flight_id, $baggage_type, $weight, $price);
        $stmt->execute();
    }

    //also checks for the seat class if it is provided or not 
    if (isset($_POST['seat_class'])) {
        $seat_class = $_POST['seat_class'];

        // this checks to see if the seat is available in that flight 
        $seatQuery = "SELECT * FROM seats WHERE flight_id = ? AND seat_status = 'Available' LIMIT 1";
        $stmt = $conn->prepare($seatQuery);
        $stmt->bind_param("i", $flight_id);
        $stmt->execute();
        $availableSeat = $stmt->get_result()->fetch_assoc(); // fetches the available seat
        $stmt->close();

        //if a available seat is found 
        if ($availableSeat) {
            
            $seat_status = 'Sold'; //changes it to say Sold
           
           //updates the seat details into our seat table
            $seatQuery = "UPDATE seats SET user_id = ?, seat_class = ?, seat_status = ? WHERE seat_id = ?";
            $stmt = $conn->prepare($seatQuery);
            $stmt->bind_param("issi", $user_id, $seat_class, $seat_status, $availableSeat['Seat_id']);
            $stmt->execute();
            $stmt->close();
        } else {
            //if there are no seats for that flight, it will display this message
            echo "No available seats for this flight.";
            echo "<p><a href='search.html'>Return to Search</a></p>"; 
            exit();
        }
    }

    //it redirects you to the success page if it is successful after booking
    header('Location:flexitickets.php');
    exit();
} else {
    //and if the booking is an error, this handles the case
    echo "Error booking flight: " . $stmt->error;
}
$stmt->close();
} else {
    //if there is no FlightId, then it shows a error messsage too
echo "No flight selected.";
}

$conn->close(); // simply closes the database. 
?>