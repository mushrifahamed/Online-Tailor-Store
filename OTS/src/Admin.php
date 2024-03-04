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
    <link rel="stylesheet" href="styles/Dashboard_styles.css">
</head>
<body>
    <div class="container">
        <div class="navigation">
            <div class="logo">
				<a href= "index.html">
                <img src="img/logo.jpeg" alt="Logo">
				</a>
            </div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="#">
                        <img src="img/dashboard.svg" alt="Dashboard Icon">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="Admin_orders.php">
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
                    <h3 class="item-header">Customers</h3>
                    <p class="item-value" id="customer-count">Loading...</p>
                    <div class="item-icon">
                        <img src="img/customer.png" alt="Customers">
                    </div>
                </div>
                <div class="flexbox-item">
                    <h3 class="item-header">Sales</h3>
                    <p class="item-value">Rs. 12,400</p>
                    <div class="item-icon">
                        <img src="img/sales.png" alt="Sales">
                    </div>
                </div>
                <div class="flexbox-item">
                    <h3 class="item-header">Profit</h3>
                    <p class="item-value">Rs. 3,020</p>
                    <div class="item-icon">
                        <img src="img/profit.png" alt="Profit">
                    </div>
                </div>
            </div>
            
        <script>
        // Fetch customer count from PHP using PHP script execution in JavaScript
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

        // Fetch customer count from the customers table
        $sql = "SELECT COUNT(*) AS customerCount FROM customers";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customerCount = $row['customerCount'];
        } else {
            $customerCount = 0;
        }

        $conn->close();
        ?>

        // Update customer count in the HTML using JavaScript
        document.getElementById("customer-count").innerText = "<?php echo $customerCount; ?>";
    </script>
    
	<div class="customers-table">
		<h3 class="table-header">Customers</h3>
		<table>
			<tr>
				<th>CID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Email</th>
				<th>Phone No</th>
				<th>Action</th>
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

    // Check if the "id" parameter exists in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch the email associated with the customer row to be deleted
        $getEmailSql = "SELECT Email FROM customers WHERE CID = '$id'";
        $result = $conn->query($getEmailSql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $email = $row['Email'];

            // Delete the customer row from the "customers" table
            $deleteCustomerSql = "DELETE FROM customers WHERE CID = '$id'";
            if ($conn->query($deleteCustomerSql) === true) {
                echo "Record deleted successfully.";
            } else {
                echo "Error deleting customer row: " . $conn->error;
            }

            // Delete the user row from the "users" table using the email as foreign key
            $deleteUserSql = "DELETE FROM users WHERE Email = '$email'";
            if ($conn->query($deleteUserSql) === true) {
                echo "";
            } else {
                echo "Error deleting user row: " . $conn->error;
            }
        }
    }

    // Fetch data from the customers table
    $sql = "SELECT * FROM customers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['CID']."</td>";
            echo "<td>".$row['Fname']."</td>";
            echo "<td>".$row['Lname']."</td>";
            echo "<td>".$row['Email']."</td>";
            echo "<td>".$row['PhoneNo']."</td>";
            echo "<td><a href='?id=".$row['CID']."' onclick='return confirm(\"Are you sure you want to delete this customer?\")'>Delete</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No records found.</td></tr>";
    }

    $conn->close();
?>

		</table>
	</div>
	</div>
    </div>
	
</body>
</html>
