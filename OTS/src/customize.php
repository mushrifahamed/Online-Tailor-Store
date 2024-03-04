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

// Prepare a statement to fetch the CID using the email
$stmt = $conn->prepare("SELECT CID FROM customers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($CID);

// Fetch the CID value
if ($stmt->fetch()) {
    $customerCID = $CID;
} else {
    // Handle the case when the CID is not found
    // Redirect to an error page or display an error message
    header("Location: error.html");
    exit(); // Stop executing the rest of the code
}

$stmt->close(); // Close the first statement

$productID = $_SESSION['current_product_id'];

// Prepare a statement to count the rows in the cart table
$countStmt = $conn->prepare("SELECT COUNT(*) AS cart_count FROM cart");
$countStmt->execute();
$countStmt->store_result(); // Store the result set
$countStmt->bind_result($cart_count);

// Fetch the cart count value
if ($countStmt->fetch()) {
    $cart_id = "C" . ($cart_count + 1);
} else {
    $cart_id = "C1";
}

$countStmt->close(); // Close the second statement

// Retrieve the form data and validate
$quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : "";
$colours = isset($_POST["colour"]) ? $_POST["colour"] : "";
$fabric = isset($_POST["fabric"]) ? $_POST["fabric"] : "";
$pattern = isset($_POST["pattern"]) ? $_POST["pattern"] : "";
$style = isset($_POST["style"]) ? $_POST["style"] : "";
$height = isset($_POST["height"]) ? $_POST["height"] : "";
$width = isset($_POST["width"]) ? $_POST["width"] : "";
$neck_measurement = isset($_POST["neck"]) ? $_POST["neck"] : "";

// Validate form data (you can add more validation as per your requirements)
if (empty($quantity) || empty($colours) || empty($fabric) || empty($pattern) || empty($style) || empty($height) || empty($width) || empty($neck_measurement)) {
    echo "Please fill in all the form fields.";
} else {
    // Prepare and execute the INSERT statement
    $insertStmt = $conn->prepare("INSERT INTO cart (cart_id, quantity, colours, fabric, pattern, style, height, width, neck_measurement, CID, PID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insertStmt->bind_param("sisssssddss", $cart_id, $quantity, $colours, $fabric, $pattern, $style, $height, $width, $neck_measurement, $customerCID, $productID);
    $insertStmt->execute();

    if ($insertStmt->affected_rows > 0) {
        echo "Product added to cart successfully.";
    } else {
        echo "Error adding product to cart.";
    }

    $insertStmt->close(); // Close the third statement
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
	<title>Customize</title>
	<style>
	body{
		padding: 0px;
	}
		.logo-hlink {
		display: flex;
		justify-content: flex-start;
		align-items: center;
	}

	.logo-h {
	  width: 40px;
	  padding: 0;
	  margin: 0;
	  padding-left: 10px;
	}

	.top-bar {
		background-color: #f5f5f5;
		padding: 0;
		margin: 0;
		display: flex;
		justify-content: space-between;
	}

	.top-bar .navigation {
		display: flex;
		margin: 0 0 0 150px;
		text-align: center;
		display: flex;
	  justify-content: center;
	  align-items: center;
	}

	.top-bar .navigation ul {
		display: flex;
		list-style-type: none;
		padding: 0;
		margin: 0;
		display: inline-block;
		justify-content: center;
		align-items: center;
	}

	.top-bar .navigation ul li {
		display: inline-block;
		margin: 0;
		padding: 0;
	}

	.top-bar .navigation ul li a {
		text-decoration: none;
		color: #777;
		font-size: 16px;
		padding: 0 20px;
	}

	.top-bar .navigation ul li a:hover{
		color: #333;
	}

	.contact-nav{
		padding: 0;
	}

	.icons {
	  display: flex;
	  justify-content: flex-end;
	  align-items: center;
	}
	.cart-icon,
	.profile-icon {
		display: inline-block;
		/* Add icon styling, such as icon images or font icons */
	}

	.svg-icon {
	  transform: scale(0.5);
	}

	.profile-name {
	  text-decoration: none;
	  color: #777;
	  margin-right: 10px;
	}

	.profile-name:hover{
	  color:#333;
	}
			<!--.container {
				width: 300px;
				margin: 0 auto;
				background-color: white;
				border-radius: 8px;
				padding: 20px;
			}-->
			form {
				text-align: left;
				font-weight: bold;
			}

			label {
				font-size: 20px;
			}

			select,
			input[type="text"] {
				width: 275px;
				padding: 8px;
				border-radius: 4px;
				border: 1px solid #ccc;
				margin-bottom: 10px;
			}

			button {
				width: 100%;
				padding: 10px;
				border-radius: 4px;
				background-color: #000000;
				color: white;
				border: none;
				cursor: pointer;
			}
			
			footer {
	  background-color: #f5f5f5;
	  padding: 20px;
	  text-align: center;
	}

	.footer-logo {
	  margin-bottom: 20px;
	}

	.logo-f {
	  width: 40px;
	  padding: 0;
	  margin: 0;
	  padding-left: 10px;
	}

	.footer-navigation ul {
	  list-style-type: none;
	  padding: 0;
	  margin: 0;
	}

	.footer-navigation ul li {
	  display: inline-block;
	  margin-right: 15px;
	}

	.footer-navigation ul li a {
	  text-decoration: ;
	  color: #333;
	}

	.footer-hr {
	  border: none;
	  border-top: 1px solid #ccc;
	  margin: 20px 0;
	}

	.footer-bottom {
	  display: flex;
	  flex-direction: row;
	  justify-content: space-between;
	  align-items: center;
	}

	.footer-social-media {
		align-self: flex-end;
	}
	.footer-social-media ul {
	  list-style-type: none;
	  padding: 0;
	  margin: 0;
	}

	.footer-social-media ul li {
	  display: inline-block;
	  padding: 0;
	  margin-left: px;
	}

	.footer-social-media ul li a {
	  text-decoration: none;
	  color: #333;
	  margin: 0;
	}

	.footer-social {
	  transform: scale(0.5);
	}

	.footer-copyright {
	  align-self: center;
	  font-size: 12px;
	  padding: 0;
	  margin: 0 0 0 160px;
	  color: #777;
	}
	</style>
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
				<a href="#" class="profile-name">Aathif Zahir</a>
            </div>
        </div>
    </header>
	
	<div class="container">
		<form method = "POST" action="">
		
			<label for="colours">Colours</label><br>
			<select name="colour" id="Colours">
				<option value="blue">Blue</option>
				<option value="Black">Black</option>
				<option value="Maroon">Maroon</option>
				<option value="grey">Grey</option>
			</select>
			<br>
			<label for="fabric">Fabric</label><br>
			<select name="fabric" id="Fabric">
				<option value="linen">Linen</option>
				<option value="wool">Wool</option>
				<option value="cotton">Cotton</option>
				<option value="silk">Silk</option>
			</select>
			<br>
			<label for="pattern">Pattern</label><br>
			<select name="pattern" id="Pattern">
				<option value="stripped">Stripped</option>
				<option value="solid">Solid</option>
				<option value="checked">Checked</option>
				<option value="plaid">Plaid</option>	
			</select>
			<br>
			<label for="style">Style</label><br>
			<select name="style" id="style">
				<option value="slim">Slim fit</option>
				<option value="regular">Regular fit</option>
				<option value="tapered">Tapered fit</option>	
			</select>
			<br>
			<label for="height">Height</label><br>
			<input type="text" placeholder="Enter height" name="height">
			<br>
			<label for="width">Width</label><br>
			<input type="text" placeholder="Enter width" name="width">
			<br>
			<label for="neck">Neck measurement</label><br>
			<input type="text" placeholder="Enter neck measurement" name="neck">
			<br>
			<label for="id">Quantity</label><br>
			<input type="text" placeholder="Enter quantity" name="quantity">
			<br>
			<button type="submit" class="btn" >Add to cart</button>
		</form>
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
				<li><a href="privacy_policy.html">Privacy and Cookies</a></li>
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
