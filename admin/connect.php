

<?php

$conn = new mysqli("localhost", "root", "", "attsystem");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// You can now perform your database operations here

// Close connection
//$conn->close();

?>


