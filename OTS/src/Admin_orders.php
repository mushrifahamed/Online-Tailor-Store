<!--session code-->
<?php
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

session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page or display an error message
    header("Location: login.html");
    exit(); // Stop executing the rest of the code
}

// Get the email of the logged-in user
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/Admin_orders.css">
</head>
<body>
    <div class="container">
        <div class="navigation">
            <div class="logo">
                <img src="img/logo.jpeg" alt="Logo">
            </div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="Admin.php">
                        <img src="img/dashboard.svg" alt="Dashboard Icon">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#">
                        <img src="img/list.svg" alt="Orders Icon">
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="Admin_settings.php">
                        <img src="img/settings.svg" alt="Settings Icon">
                        Settings
                    </a>
                </li>
                <hr>
                <li class="nav-item">
                    <a href="signout.php">
                        <img src="img/logout.png" alt="Logout Icon">
                        Sign out
                    </a>
                </li>
            </ul>
        </div>
        <div class="dashboard">
            <h1>Dashboard</h1>
            <h2>Overview</h2>
            <div class="flexbox-container">
                <div class="flexbox-item">
                    <h3 class="item-header">Total Orders</h3>
                    <p class="item-value"><?php $result = $conn->query("SELECT COUNT(*) FROM orders"); $count = $result->fetch_row()[0];echo $count?></p>
                    <div class="item-icon">
                        <img src="img/total orders.png" alt="total">
                    </div>
                </div>
                <div class="flexbox-item">
                    <h3 class="item-header">Top Seller</h3>
                    <p class="item-value">Navy Suit</p>
                    <div class="item-icon">
                        <img src="img/top seller.png" alt="top">
                    </div>
                </div>
                <div class="flexbox-item">
                    <h3 class="item-header">Customer Satisfaction</h3>
                    <p class="item-value">95%</p>
                    <div class="item-icon">
                        <img src="img/customer satisfaction.png" alt="satisfaction">
                    </div>
                </div>
            </div>
        
            <div class="customers-table">
                <h3 class="table-header">Orders</h3>
                <table>
                    <tr>
                        <th>OID</th>
                        <th>Location</th>
                        <th>CID</th>
                        <th>Email</th>
                        <th>PhoneNo</th>
                    </tr>
                    <?php
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

                    // Fetch data from the orders table with customer details
                    $sql = "SELECT orders.*, customers.Email, customers.PhoneNo FROM orders INNER JOIN customers ON orders.CID = customers.CID";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row['OID']."</td>";
                            echo "<td>".$row['Location']."</td>";
                            echo "<td>".$row['CID']."</td>";
                            echo "<td>".$row['Email']."</td>";
                            echo "<td>".$row['PhoneNo']."</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
