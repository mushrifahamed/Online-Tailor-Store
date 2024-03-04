<!--session code-->
<?php
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
    <link rel="stylesheet" href="styles/tailor.css">
</head>
<body>
    <div class="container">
        <div class="navigation">
            <div class="logo">
                <img src="img/logo.jpg" alt="Logo">
            </div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="tailor.php">
                        <img src="img/dashboard.svg" alt="Dashboard Icon">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="tailor_settings.php">
                        <img src="img/settings.svg" alt="Settings Icon">
                        Settings
                    </a>
                </li>
                <hr>
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
            <h2>Overview</h2>
            <div class="flexbox-container">
                <div class="flexbox-item">
                    <h3 class="item-header">Item 1</h3>
                    <p class="item-value">Value 1</p>
                    <div class="item-icon">
                        <img src="icon1.jpg" alt="Icon 1">
                    </div>
                </div>
                <div class="flexbox-item">
                    <h3 class="item-header">Item 2</h3>
                    <p class="item-value">Value 2</p>
                    <div class="item-icon">
                        <img src="icon2.jpg" alt="Icon 2">
                    </div>
                </div>
                <div class="flexbox-item">
                    <h3 class="item-header">Item 3</h3>
                    <p class="item-value">Value 3</p>
                    <div class="item-icon">
                        <img src="icon3.jpg" alt="Icon 3">
                    </div>
                </div>
            </div>
        
            <div class="customers-table">
                <div class="customers-table">
					<h3 class="table-header">Orders</h3>
					<table>
						<tr>
							<th>Order ID</th>
							<th>Date</th>
							<th>Preference ID</th>
							<th>Colours</th>
							<th>Fabric</th>
							<th>Pattern</th>
							<th>Style</th>
							<th>Height</th>
							<th>Width</th>
							<th>Neck Measurement</th>
							<th>Quantity</th>
							<th>Product Name</th>
							<th>Product Type</th>
							<th>Phone Number</th>
							<th>Email</th>
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

						$query = "SELECT o.OID, o.date, p.pref_id, p.colours, p.fabric, p.pattern, p.style, p.height, p.width, p.neck_measurement, p.quantity, pd.p_name, pd.p_type, c.PhoneNo, c.Email
								  FROM orders o
								  INNER JOIN preferences p ON o.OID = p.OID
								  INNER JOIN products pd ON p.PID = pd.PID
								  INNER JOIN customers c ON o.CID = c.CID";

						$result = $conn->query($query);
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								echo "<tr>";
								echo "<td>" . $row['OID'] . "</td>";
								echo "<td>" . $row['date'] . "</td>";
								echo "<td>" . $row['pref_id'] . "</td>";
								echo "<td>" . $row['colours'] . "</td>";
								echo "<td>" . $row['fabric'] . "</td>";
								echo "<td>" . $row['pattern'] . "</td>";
								echo "<td>" . $row['style'] . "</td>";
								echo "<td>" . $row['height'] . "</td>";
								echo "<td>" . $row['width'] . "</td>";
								echo "<td>" . $row['neck_measurement'] . "</td>";
								echo "<td>" . $row['quantity'] . "</td>";
								echo "<td>" . $row['p_name'] . "</td>";
								echo "<td>" . $row['p_type'] . "</td>";
								echo "<td>" . $row['PhoneNo'] . "</td>";
								echo "<td>" . $row['Email'] . "</td>";
								echo "</tr>";
							}
						} else {
							echo "<tr><td colspan='17'>No orders found.</td></tr>";
						}

						$conn->close();
						?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
