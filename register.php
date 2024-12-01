<?php
// Enable error reporting for debugging

// Include the database connection file
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];     // Password entered by the user
    $confirm_password = $_POST['confirm_password']; // Password confirmation (not stored in DB)
    $phone = $_POST['phone'];   

    // Hash the password for storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the password and confirm_password match
    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
    } else {
        // Prepare SQL to check if the email already exists
        $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email); // "s" means string
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            // If email already exists, show an error
            echo "Email is already registered. Please use a different email.";
            echo "<p><a href='register.html'>Go back to registration.</a></p>"; 
        } else {
            // Prepare SQL to insert a new user without confirm_password
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phone); // "sss" means three strings

            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                echo "Registration successful! <a href='login.html'>Login here</a>";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        }

        // Close the email check statement
        $checkEmail->close();
    }
}

// Close the database connection
$conn->close();
?>



