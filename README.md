# AeroTracker

Project Overview:

AeroTracker is a flight reservation system with features such as seat selection, ticket selling and swapping. The project uses HTML, CSS and PHP as well as MySQL. The project is built to run on a local server environment. 

Requirements:

1. XAMPP (or any other server environment with PHP and MySQL)
2. A web browser

Setup Instructions:

1. Clone the Repository

2. Install XAMPP
   -Download and install XAMPP from your browser
   -Start the Apache and MySQL modules in the XAMPP control panel

3. Set up the database
   -Open phpMyAdmin(usually available at HTTP://localhost/phpmyadmin)
   -Create a new database (e.g., aerotracker)
   -Import the provided SQL file:
      1. Go to the Import tab in phpMyAdmin
      2. Select the SQL file from your project repository(database/aerotracker.sql)
      3. Click GO to import the database schema and data

4. Configure the Project
   - Open the config.php(or similar configuration file) in the project directory.
   - Update the database credentials
  
   $servername = "localhost";
  $username = "root"; // Default XAMPP user
  $password = "";     // Default XAMPP password is empty
  $dbname = "aerotracker";


5. Place the Project in the XAMPP Directory
   -Copy the project folder to the htdocs directory in your XAMPP installation.

6. Run the project
   -Open a browser and navigate to:
   HTTP://localhost/<project-folder-name>

   replace <project-folder-name> with the name of your project folder.







