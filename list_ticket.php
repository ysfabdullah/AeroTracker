<?php
session_start();
//this is here to connect the php to our database on myphpadmin
include 'db_connect.php';

//gets the session for the logged in user
$user_id = $_SESSION['user_id'];
?>

<form action="process_ticket_request.php" method="POST">
    <h2>List Your Ticket for Sale or Swap</h2>
    <label for="Seat_id">Select Seat:</label>
    <select name="Seat_id" required>
        <?php
       
       //query that will fetch the seats that are available for the logged in user
        $query = "SELECT Seat_id, seat_class FROM seats WHERE user_id = ? AND seat_status = 'Available'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            //this displays each avaiable seat as a option
            while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['Seat_id']}'>{$row['seat_class']}</option>";
            }
        } else {
            //handling errors if no seat is found
            echo "<option value=''>No available seats</option>";
        }
        ?>
    </select>

    <br><br>

   
    <label for="action">Choose Action:</label>
    <select name="action" id="action" required>
        <option value="Sell">Sell</option>
        <option value="Swap">Swap</option>
    </select>

    <br><br>


    <div id="price_input" style="display: none;">
        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" min="0" placeholder="Enter price for the seat">
    </div>


    <div id="requested_seat_input" style="display: none;">
        <label for="requested_seat_id">Requested Seat (for Swap):</label>
        <select name="requested_seat_id" required>
            <?php
        
            // another query to fetch seats that are available for swapping other users seats
            $query = "SELECT Seat_id, seat_class FROM Seats WHERE user_id != ? AND seat_status = 'Available'";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['Seat_id']}'>{$row['seat_class']}</option>";
            }
            ?>
        </select>
    </div>

    <br><br>

    <button type="submit">Submit Request</button>
</form>

<script>
    //just some javascript for toggle purpose. 
    document.getElementById('action').addEventListener('change', function () {
        if (this.value == 'Sell') {
            document.getElementById('price_input').style.display = 'block';
            document.getElementById('requested_seat_input').style.display = 'none';
        } else {
            document.getElementById('price_input').style.display = 'none';
            document.getElementById('requested_seat_input').style.display = 'block';
        }
    });
</script>
