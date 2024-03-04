<?php
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

// Function to generate the next CID value
function generateCID($conn)
{
	$query = "SELECT MAX(CID) as max_cid FROM customers";
	$result = $conn->query($query);
	if (!$result) {
		die("Error: " . $conn->error);
	}
	$row = $result->fetch_assoc();
	$maxCID = $row['max_cid'];
	$currentNumber = intval(substr($maxCID, 1));
	$nextNumber = $currentNumber + 1;
	$nextCID = 'C' . $nextNumber;
	return $nextCID;
}

// Function to validate the password
function validatePassword($password)
{
	// Simple password validation rules
	// At least 8 characters
	// At least one uppercase letter, one lowercase letter, and one digit
	$regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/";
	return preg_match($regex, $password);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get the form inputs
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phoneNo = $_POST['phone'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// Check if the email already exists in the users table
	$checkUserQuery = "SELECT * FROM users WHERE email = '$email'";
	$checkUserResult = $conn->query($checkUserQuery);
	if (!$checkUserResult) {
		die("Error: " . $conn->error);
	}
	if ($checkUserResult->num_rows > 0) {
		echo "Email already exists. Please choose a different email.";
	} else {
		// Validate the password
		if (!validatePassword($password)) {
			echo "Password should have at least 8 characters, including uppercase, lowercase, and digits.";
		} else {
			// Generate the next CID
			$nextCID = generateCID($conn);

			// Insert data into users table
			$userInsertQuery = "INSERT INTO users (email, password, type) VALUES ('$email', '$password', 'Customer')";
			if ($conn->query($userInsertQuery) === TRUE) {
				echo "Data inserted into users table successfully.";
			} else {
				echo "Error inserting data into users table: " . $conn->error;
			}

			// Insert data into customer table
			$customerInsertQuery = "INSERT INTO customers (CID, fname, lname, phoneNo, email) VALUES ('$nextCID', '$fname', '$lname', '$phoneNo', '$email')";
			if ($conn->query($customerInsertQuery) === TRUE) {
				echo "Data inserted into customer table successfully.";
			} else {
				echo "Error inserting data into customer table: " . $conn->error;
			}

			// Redirect to the login page
			header("Location: ../login.html");
			exit();
		}
	}
}

$conn->close();
?>