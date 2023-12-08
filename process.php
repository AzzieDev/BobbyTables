<?php
require_once('dbsecrets.php');

//retrieve from the form for further processing without sanitization
$student_name = $_POST['student_name'];

//allow us to regenerate the table if we wiped it :)
if ($student_name == "reset") {
    $query = "
        DROP TABLE IF EXISTS Students;
        
        CREATE TABLE Students (
            first VARCHAR(50)
        );
        
        INSERT INTO Students VALUES
        ('Alice'),
        ('Bob'),
        ('Charlie'),
        ('David'),
        ('Eve');
    ";

    $result = mysqli_multi_query($connection, $query);

    while (mysqli_next_result($connection)) {
        if ($result = mysqli_store_result($connection)) {
            mysqli_free_result($result);
        }
    }
} else {

    // Unsafe query prone to SQL injection
    //$unsafe_query = "INSERT INTO `Students` VALUES ('Robert'); -- DROP TABLE Students;";

    $unsafe_query = "INSERT INTO `Students` VALUES ('$student_name');";
    //$result = $connection->multi_query($unsafe_query);

    echo $unsafe_query . "<br><br>";
    $result = mysqli_multi_query($connection, $unsafe_query);
    // Discard the results without fetching or processing them
    while (mysqli_next_result($connection)) {
        if ($result = mysqli_store_result($connection)) {
            mysqli_free_result($result);
        }
    }
}
$query = "SELECT * FROM Students";
$result = mysqli_query($connection, $query);

// Fetch data
while ($row = mysqli_fetch_assoc($result)) {
    // Display student data (for demonstration)
    echo "Name: " . $row['first'] . "<br>";
}

// Close statement and connection
mysqli_close($connection);
