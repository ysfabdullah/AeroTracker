<?php

//this is here to connect the php to our database on myphpadmin
include 'db_connect.php';

$error = "";
$success = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];    
    $confirm_password = $_POST['confirm_password']; //need to confirm password
    $phone = $_POST['phone']; 


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    

    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
        //error checking to see if the passwords should match or not
        echo "<p><a href='register.html'>Go back to registration.</a></p>"; 
        //takes you back to the register page
    } else {

        //then this is the data that gets entered into the 
        $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        //if the email is already in the database it will display an error
        if ($result->num_rows > 0) {
            
            //cannot have two of the same emails 
            echo "Email is already registered. Please use a different email.";
            echo "<p><a href='register.html'>Go back to registration.</a></p>"; 
        } else {
         
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phone); 

  
            if ($stmt->execute()) {
                //shows a successful message with a link that will take you back to the page
                echo "Registration successful! <a href='login.html'>Login here</a>";
            } else {
                //also shows an error here if there seems to be an error
                echo "An error occured during registraion. Please try again later. " . $stmt->error;
            }


            $stmt->close();
        }

        
        $checkEmail->close();
    }
}


$conn->close();
?>



