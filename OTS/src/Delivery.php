<?php
// Create a connection to the database
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

// Retrieve data from the "orders" table
$sql = "SELECT OID, Location, CID, date , phone_number FROM orders";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delivery Dashboard</title>
    <link rel="stylesheet" href="styles/Delivery_styles.css">
</head>
<body>
    <div class="container">
        <div class="navigation">
            <div class="logo">
				<a href = "index.html">
                <img src="img/logo.jpeg" alt="Logo">
				</a>
            </div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="signout.php">
                        <img src="img/logout.svg" alt="Logout Icon">
                        Sign out
                    </a>
                </li>
            </ul>
        </div>
        <div class="dashboard">
            <h1>Dashboard</h1>
            <h2>Orders</h2>
            <div class="orders-table">
                <?php
                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>OID</th><th>Location</th><th>CID</th><th>Date</th><th>Phone Number</th></tr>";
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["OID"] . "</td>";
                        echo "<td>" . $row["Location"] . "</td>";
                        echo "<td>" . $row["CID"] . "</td>";
                        echo "<td>" . $row["date"] . "</td>";
                        echo "<td>" . $row["phone_number"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
