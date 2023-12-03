

<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "attsystem";

// Create connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// You can now perform your database operations here

// Close connection
//$conn->close();

?>


