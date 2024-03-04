<?php
// Check if the cart item ID is provided in the URL
if (isset($_GET['id'])) {
    // Get the cart item ID
    $cartItemId = $_GET['id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ots";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete the cart item from the database
    $sql = "DELETE FROM cart WHERE cart_id = '$cartItemId'";
    if ($conn->query($sql) === TRUE) {
        // Cart item deleted successfully
		header("Location:cart.php");
    } else {
        // Error deleting cart item
        header("Location:cart.php");
    }

    $conn->close();
} else {
    // Cart item ID not provided in the URL
    echo "Cart item ID not provided.";
}


?>
