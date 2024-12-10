Create Table:

CREATE DATABASE AeroTracker;

USE AeroTracker;

User Table:

CREATE TABLE Users (
    	user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    	name VARCHAR(255) NOT NULL,
   	email VARCHAR(255) NOT NULL UNIQUE,
    	password VARCHAR(255) NOT NULL,
   	 phone VARCHAR(15) NOT NULL
);

 Flights Table:
CREATE TABLE Flights (
flight_id INT(11) AUTO_INCREMENT PRIMARY KEY,
departure_city VARCHAR(100) NOT NULL,
arrival_city VARCHAR(100) NOT NULL,
trip_type ENUM('one-way', 'round-trip') NOT NULL,
departure_date DATE NOT NULL,
arrival_date DATE NULL,
num_passengers INT(11) NOT NULL,
booking_id INT(11),
FOREIGN KEY (booking_id) REFERENCES Booking(booking_id)
);
Booking Table:
	
CREATE TABLE Booking (
booking_id INT AUTO_INCREMENT PRIMARY KEY,
	 User_id int(11) NOT NULL,
            Flight_id int(11) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES Users(user_id), 
FOREIGN KEY (flight_id) REFERENCES Flights(flight_id)
	
);

Baggage Table:
 
CREATE TABLE Baggage (
   baggage_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    flight_id INT(11) NOT NULL,
    baggage_type ENUM('Carry-On', 'Checked', 'Excess') NOT NULL,
    weight DECIMAL(5, 2) NULL,
    cost DECIMAL(10, 2) NULL,
    FOREIGN KEY (flight_id) REFERENCES Flights(flight_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

Seat Table: 
	
CREATE TABLE Seats (
   Seat_id INT(11) AUTO_INCREMENT PRIMARY KEY,
   Flight_id INT(11) NOT NULL,
   User_id INT(11) NULL,
   Seat_class ENUM('Economy', 'Business', 'First Class') NOT NULL,
   Seat_status ENUM('Available', 'Sold', 'Swapped') NOT NULL DEFAULT 'Available',
   is_listed_for_sale TINYINT(1) DEFAULT 0,
   FOREIGN KEY (Flight_id) REFERENCES Flights(flight_id),
   FOREIGN KEY (User_id) REFERENCES Users(user_id)
);























Swap Request Table:

CREATE TABLE Ticket_Request (
    request_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    seat_id INT(11) NOT NULL,
    flight_id INT(11) NOT NULL,
    action ENUM('Sell', 'Swap') NOT NULL,
    price DECIMAL(10, 2) NULL,
    status ENUM('Pending', 'Accepted', 'Rejected', 'Completed') NOT NULL DEFAULT 'Pending',
    requested_seat_id INT(11) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    listed_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seat_id) REFERENCES Seats(Seat_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (flight_id) REFERENCES Flights(flight_id),
    FOREIGN KEY (requested_seat_id) REFERENCES Seats(Seat_id)
);


Notification table: 

CREATE TABLE Notification (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT,
    timestamp DATETIME
);

