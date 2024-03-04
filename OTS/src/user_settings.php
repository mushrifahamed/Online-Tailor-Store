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

// Fetch the user's information from the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ots";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query to fetch user information
$selectQuery = "SELECT c.Fname, c.Lname, u.Password FROM customers AS c
                INNER JOIN users AS u ON c.Email = u.Email
                WHERE c.Email = ?";
$selectStmt = $conn->prepare($selectQuery);
$selectStmt->bind_param('s', $email);
$selectStmt->execute();
$result = $selectStmt->get_result();

// Check if a row is returned
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle the case when the user is not found
    // Redirect to an error page or display an error message
    exit();
}

if (isset($_POST['change_password'])) {
    // Get the current password, new password, and confirm password from the form
    $currentPassword = $_POST['current-password'];
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-new-password'];

    // Verify if the current password matches the one in the database
    if ($currentPassword === $user['Password']) {
        // Verify if the new password and confirm password match
        if ($newPassword === $confirmPassword) {
            // Update the user's password in the database
            $updateQuery = "UPDATE users SET Password = ? WHERE Email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('ss', $newPassword, $email);
            $updateStmt->execute();

            // Redirect to the same page to refresh the data
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Handle the case when the new password and confirm password do not match
            // Redirect to an error page or display an error message
            exit();
        }
    } else {
        // Handle the case when the current password is incorrect
        // Redirect to an error page or display an error message
        exit();
    }
}

if (isset($_POST['delete_account'])) {
    // Show the confirmation message and delete the account if confirmed
    if ($_POST['confirmation'] === 'confirm') {
        
        // Delete the customer from the customers table
        $deleteCustomerQuery = "DELETE FROM customers WHERE Email = ?";
        $deleteCustomerStmt = $conn->prepare($deleteCustomerQuery);
        $deleteCustomerStmt->bind_param('s', $email);
        $deleteCustomerStmt->execute();
        
        // Delete the user from the users table
        $deleteUserQuery = "DELETE FROM users WHERE Email = ?";
        $deleteUserStmt = $conn->prepare($deleteUserQuery);
        $deleteUserStmt->bind_param('s', $email);
        $deleteUserStmt->execute();

        // Destroy the session and redirect to the login page
        session_destroy();
        header("Location: login.html");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title>User Password Settings</title>
	<link rel="stylesheet" href="styles/User_Profilesettings_styles.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <a href="#" class="logo-hlink"><img src="img/logo.jpeg" alt="Logo" class="logo-h"></a>
            <nav class="navigation top">
                <ul>
                    <li><a href="index.html" class="home-nav">Home</a></li>
                    <li><a href="collection.html">Collection</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.html" class="contact-nav">Contact</a></li>
                </ul>
            </nav>
            <div class="icons">
                <a href="#" class="cart-icon"><img src="img/cart.svg" class="svg-icon"></a>
                <a href="#" class="profile-icon"><img src="img/account.svg" class="svg-icon"></a>
                <a href="#" class="profile-name"><?php echo $user['Fname'] . ' ' . $user['Lname']; ?></a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="left-section">
            <h2>Account settings</h2>
            <div class="profile-block">
                <div class="profile-details">
                    <p><strong>Name:</strong> <?php echo $user['Fname'] . ' ' . $user['Lname']; ?></p>
                    <p><strong>Email:</strong> <?php echo $email; ?></p>
                </div>
            </div>
            <hr>
            <ul class="nav-list">
                <li class="nav-item"><a href="user.php">Edit profile</a></li>
                <li class="nav-item"><a href="user_setting.php">Security</a></li>
                <li class="nav-item"><a href="signout.php">Sign out</a></li><hr>
                <li class="nav-item">
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="confirmation" value="confirm">
                        <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="right-section">
            <h2>Change Password</h2>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current-password" placeholder="Enter current password" required>
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-new-password">Confirm New Password</label>
                    <input type="password" id="confirm-new-password" name="confirm-new-password" placeholder="Confirm new password" required>
                </div>
                <div class="btn-container-right">
                    <button type="reset">Reset</button>
                    <button type="submit" name="change_password">Change Password</button>
                </div>
            </form>
        </div>
    </div>
    
    <footer>
    <div class="footer-logo">
        <a href="#" class="logo"><img src="img/logo.jpeg" alt="Logo" class="logo-f"></a>
    </div>
    <nav class="footer-navigation">
        <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Terms and Conditions</a></li>
            <li><a href="#">Privacy and Cookies</a></li>
        </ul>
    </nav>
    <hr class="footer-hr">
    <div class="footer-bottom">
    <div></div>
    <p class="footer-copyright">
        &copy; 2022 Brand. All rights reserved.
    </p>
    <div class="footer-social-media">
        <ul>
            <li><a href="#"><img src="img/facebook.svg" alt="facebook" class="footer-social"></a></li>
            <li><a href="#"><img src="img/twitter.svg" alt="twitter" class="footer-social"></a></li>
            <li><a href="#"><img src="img/instagram.svg" alt="instagram" class="footer-social"></a></li>
        </ul>
    </div>
    </div>
  </footer>
  
</body>
</html>
