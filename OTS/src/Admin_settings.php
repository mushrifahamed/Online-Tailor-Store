<!--session code and form code-->
<?php
session_start();

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

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page or display an error message
    header("Location: login.html");
    exit(); // Stop executing the rest of the code
}

// Get the email of the logged-in user
$email = $_SESSION['email'];

// Retrieve user information from the 'staff' table
$query = "SELECT * FROM staff WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Retrieve the password from the 'users' table
$getPasswordQuery = "SELECT password FROM users WHERE email = ?";
$getPasswordStmt = $conn->prepare($getPasswordQuery);
$getPasswordStmt->bind_param('s', $email);
$getPasswordStmt->execute();
$passwordResult = $getPasswordStmt->get_result();
$passwordRow = $passwordResult->fetch_assoc();
$user_password = $passwordRow['password'];

// Update user profile information when the "Update info" form is submitted
if (isset($_POST['update_info'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];

    $updateQuery = "UPDATE staff SET Fname = ?, Lname = ?, PhoneNo = ? WHERE Email = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ssss', $fname, $lname, $phone_number, $email);
    $updateStmt->execute();

    // Redirect to the same page to refresh the data
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Update user password when the "Change Password" form is submitted
if (isset($_POST['change_password'])) {
    // Update password
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Compare the current password with the stored password (in plain text)
    if ($current_password === $user_password && $new_password === $confirm_password) {
        // Update the password in the 'users' table
        $updatePasswordQuery = "UPDATE users SET password = ? WHERE email = ?";
        $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
        $updatePasswordStmt->bind_param('ss', $new_password, $email);
        $updatePasswordStmt->execute();
    }

    // Redirect to the same page to refresh the data
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/Admin_settings.css">
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
                    <a href="Admin_orders.php">
                        <img src="img/list.svg" alt="Orders Icon">
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#">
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
			<div class="profile">
				<h2>Edit Profile</h2>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<label for="first_name">First Name:</label>
					<input type="text" id="first_name" name="first_name" value="<?php echo $user['Fname']; ?>" required><br><br>

					<label for="last_name">Last Name:</label>
					<input type="text" id="last_name" name="last_name" value="<?php echo $user['Lname']; ?>" required><br><br>

					<label for="phone_number">Phone Number:</label>
					<input type="tel" id="phone_number" name="phone_number" value="<?php echo $user['PhoneNo']; ?>" required><br><br>

					<span class="edit-buttons">
						<input type="reset" value="Reset">
						<input type="submit" name="update_info" value="Update info">
					</span>
				</form>
			</div>
			
			<div class="security">
				<h2>Security</h2>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required><br><br>

                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required><br><br>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

                    <span class="security-buttons">
                        <input type="reset" value="Reset">
                        <input type="submit" name="change_password" value="Change Password">
                    </span>
				</form>
				</div>
        </div>
    </div>
</body>
</html>
