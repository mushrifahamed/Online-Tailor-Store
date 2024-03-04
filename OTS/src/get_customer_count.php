<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "learn";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer count from the customers table
$sql = "SELECT COUNT(*) AS customerCount FROM customers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['customerCount'];
} else {
    echo "0";
}

$conn->close();
?>
