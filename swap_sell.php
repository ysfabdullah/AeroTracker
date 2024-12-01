<?php
include 'db_connection.php';

$action = $_GET['action'];
$seat_id = $_GET['seat_id'];

if ($action == 'swap') {
    echo "<h2>Request a Seat Swap</h2>";
    echo "<form action='process_swap_sell.php' method='POST'>
            <input type='hidden' name='action' value='swap'>
            <input type='hidden' name='seat_id' value='$seat_id'>
            <label for='requested_seat'>Choose Seat to Swap With:</label>
            <select name='requested_seat_id'>
                <!-- Populate with available seats -->
                <?php
                $query = 'SELECT seat_id, seat_class FROM seats WHERE seat_status = 'Available';
                $result = $conn->query($query);
                while ($seat = $result->fetch_assoc()) {
                    echo \"<option value='{$seat['seat_id']}'>{$seat['seat_class']}</option>\";
                }
                ?>
            </select>
            <button type='submit'>Submit Swap Request</button>
          </form>";
} elseif ($action == 'sell') {
    echo "<h2>Sell Your Seat</h2>";
    echo "<form action='process_swap_sell.php' method='POST'>
            <input type='hidden' name='action' value='sell'>
            <input type='hidden' name='seat_id' value='$seat_id'>
            <label for='price'>Set Price:</label>
            <input type='number' name='price' step='0.01' required>
            <button type='submit'>List for Sale</button>
          </form>";
}
?>
