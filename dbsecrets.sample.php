<?php
// Database Configuration
$hostname = "your_database_host";
$username = "your_database_username";
$password = "your_database_password";
$database = "your_database_name";

// Create a database connection
$connection = mysqli_connect($hostname, $username, $password, $database);

// Check the connection
if (!$connection) {
	die("Connection failed: " . mysqli_connect_error());
}
