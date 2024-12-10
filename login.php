<?php
session_start();
//this is here to connect the php to our database on myphpadmin
include 'db_connect.php';

$error = "";
//used this for debugging issues.


//gets the server
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    //for email purpose
    $password = $_POST['password'];
    //and password purpose

    //you have to enter both in order to log in
    if (empty($email) || empty($password)) {
        $error = "Both email and password fields are required.";
    } else {
        //this is to make sure the email format is correct
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        }else{    
            //this will create a sql  statement to find the user by email
            $stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
           
            //checks to see if the user exists in the database
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                //then here is for the password checks
                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true); 
                    //stores them in the session variables 
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_name'] = $user['name'];
                    

                    //it will then directed to the home page
                    header("Location: index.html");
                    exit();
                } else {
                    //handling errors if they are inputted wrong
                    $error = "Invalid email or password! <a href='login.html'>Try again</a>";

                }
            } else {
                //another error checking if the user is not found with the provided email
                $error = "No user found with that email! <a href='login.html'>Try again</a>";
            }

            $stmt->close();
        }
        
    }
}    

$conn->close();//closes the database after

if (!empty($error)) {
    echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); 
}
?>
