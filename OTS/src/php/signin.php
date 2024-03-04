<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "OTS";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve email and password from the submitted form
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute the query to check the user credentials
$query = "SELECT * FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user exists and the password is correct
if ($user && $password === $user['password']) {
    // Set the user type and redirect based on user type
    $userType = $user['type'];
    switch ($userType) {
        case 'Customer':
            // Redirect to customer homepage
            header("Location: ../index.html");
            break;
        case 'Admin':
            // Redirect to admin dashboard
            header("Location: ../Admin.php");
            break;
        case 'Tailor':
            // Redirect to tailor dashboard
            header("Location: ../tailor.php");
            break;
        case 'DeliveryPerson':
            // Redirect to delivery dashboard
            header("Location: ../delivery.php");
            break;
        default:
            // Invalid user type, handle accordingly
            break;
    }

    // Store the user's email in the session
    $_SESSION['email'] = $email;
} else {
    // Invalid login credentials, display an error message or redirect to an error page
    header("Location: login_error.php");
}
?>
