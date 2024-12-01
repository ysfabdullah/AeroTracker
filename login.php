<?php
session_start();
include 'db_connect.php';

$error ="";


$_SESSION['user_id'] = $user_id;  // Store user ID in session
$_SESSION['username'] = $username; // Store username (optional for greeting)
header("Location: index.html"); // Redirect to tickets page



$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the entered password with the hashed password
        if (password_verify($password, $user['password'])) {
            // Start the session and store user data
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to the main page or dashboard
            header("Location: index.html"); // Replace with your actual page
            exit();
        } else {
            // Password incorrect
            echo "Invalid password!";
        }
    } else {
        // Email not found
        echo "No user found with that email!";
    }

    $stmt->close();
}

$conn->close();
?>



