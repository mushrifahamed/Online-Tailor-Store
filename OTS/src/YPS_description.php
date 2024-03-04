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

// Fetch the user's CID from the customers table
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ots";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Manually assign the desired product ID
$productID = 'P8';

// Assign the product ID to the superglobal variable
$_SESSION['current_product_id'] = $productID;

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

// Close the prepared statement
$stmt->close();

// Manually assign the desired product ID from the session
$productID = $_SESSION['current_product_id'];

// Check if the "Add to cart" button is pressed
if (isset($_POST['add_to_cart'])) {
    // Get the selected options from the dropdowns
    $colours = $_POST['colours'];
	$size = $_POST['size'];
	$quantity = $_POST['quantity'];

    // Fetch the last inserted cartID from the cart table
    $stmt = $conn->prepare("SELECT cart_id FROM cart ORDER BY cart_id DESC LIMIT 1");
    $stmt->execute();
    $stmt->bind_result($lastCartID);

    // Generate the new cartID by incrementing the lastCartID
    if ($stmt->fetch()) {
        $lastNumber = intval(substr($lastCartID, 1));
        $newCartID = "C" . ($lastNumber + 1);
    } else {
        // Set the cartID as C1 if no previous entry exists
        $newCartID = "C1";
    }

    // Close the prepared statement
    $stmt->close();

    // Prepare the SQL statement to insert the cart details into the database
    $stmt = $conn->prepare("INSERT INTO cart (cart_id, quantity, colours, size, PID, CID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $newCartID, $quantity, $colours, $size, $productID, $customerCID);
    $stmt->execute();
    $stmt->close();

    // Redirect to the cart page or display a success message
    header("Location: cart.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="UTF-8">
	<title>Yellow printed shirt</title>
	<link rel="stylesheet" href="styles/descriptionpg.css">
	
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
	
	<h1 class = "text-despg">Yellow printed shirt<h1>
	<div><img src = "img/collection/imgYPSdes.jpg" class = "image1-despg"></div>
	<div><img src = "img/collection/img1YPSdes.jpg" class = "image2-despg"></div>
	<div><img src = "img/collection/img2YPSdes.jpg" class = "image3-despg"></div>
	<!--<div><img src = "img/collection/img3NBSdes.jpg" class = "image4-despg"></div>
	<div><img src = "img/collection/img4NBSdes.jpg" class = "image5-despg"></div>-->
	<p class= "description1-despg">Description</p>
	<p class = "description2-despg">This sunny yellow shirt gives you an all-natural sunny look with shades of yellow and orange mixed together with a floral print. A soft white twill weave helps you stand out in style, while 100% cotton ensures your comfort. Dress your best, and dress to impress in this comfortable shirt.</p>
	
	<p class = "price-despg">LKR 12000.00</p>
	<form method="post" action="">
    <div class="dropdown">
        <select name="colours" id="Colours">
            <option value="blue">blue</option>
            <option value="Black">Black</option>
            <option value="Maroon">Maroon</option>
            <option value="grey">grey</option>
        </select>
        <br>
        <select name="size" id="Size">
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
            <option value="X-Large">X-Large</option>
        </select>
        <br>
        <select name="quantity" id="Quantity">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>	
        </select>
    </div>
	
    <button class="custombtn-despg"><a href = "customize.php">Customize</a></button>
    <button class="addbtn-despg" name="add_to_cart">Add to cart</button>
	</form>
	
	
	<h2 class= "reviewtitle-despg">Reviews</h2>
	
	<div class= "reviewcontainer1-despg"><div class="avatar"><img src="img/collection/avatar1.png" alt="Avatar">
    <div class="badge"></div>
    </div><div class = "reviewname-despg">Mushrif Ahamed</div><div class = "reviewdes-despg">I completely love this site, found it on EBay at first now I just order directly through them...I am always complemented on my outfits I will be back for more...Thank you for having cute trendy clothes that fit and look good.
    </div></div>
	
	<div class= "reviewcontainer2-despg"><div class="avatar"><img src="img/collection/avatar2.png" alt="Avatar">
    <div class="badge"></div>
    </div><div class = "reviewname-despg">Aathif Zahir</div><div class = "reviewdes-despg">I completely love this site, found it on EBay at first now I just order directly through them...I am always complemented on my outfits I will be back for more...Thank you for having cute trendy clothes that fit and look good.
    </div></div>
	
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