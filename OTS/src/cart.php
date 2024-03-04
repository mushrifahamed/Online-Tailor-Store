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

// Fetch cart details from the cart table for the logged-in user
$sql = "SELECT cart.*, products.p_name, products.price FROM cart
        INNER JOIN products ON cart.PID = products.PID
        WHERE cart.CID = (SELECT CID FROM customers WHERE Email = '$email')";
$result = $conn->query($sql);

// Array to store cart items
$cartItems = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $cartItem = array(
            'cart_id' => $row['cart_id'],
            'product_name' => $row['p_name'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        );
        array_push($cartItems, $cartItem);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="styles/cart.css">
    <script>
        // JavaScript code for removing a product and updating the totals
        function removeCartItem(cartId) {
            // Ask for confirmation
            if (confirm("Are you sure you want to remove this item from the cart?")) {
                // Redirect to the PHP script to delete the cart item from the database
                window.location.href = "delete_cart_item.php?id=" + cartId;
            }
        }
    </script>
</head>
<body>
    <!--<header>
        <div class="top-bar">
            <a href="index.html" class="logo-hlink"><img src="img/logo.jpeg" alt="Logo" class="logo-h"></a>
            <nav class="navigation top">
                <ul>
                    <li><a href="index.html" class="home-nav">Home</a></li>
                    <li><a href="collection.html">Collection</a></li>
                    <li><a href="About Us.html">About</a></li>
                    <li><a href="contact.php" class="contact-nav">Contact</a></li>
                </ul>
            </nav>
            <div class="icons">
                <a href="cart.php" class="cart-icon"><img src="img/cart.svg" class="svg-icon"></a>
                <a href="user.php" class="profile-icon"><img src="img/account.svg" class="svg-icon"></a>
				<!--<a href="#" class="profile-name">Aathif Zahir</a>
            </div>
        </div>
    </header>-->
    <h1>Cart</h1>
    <div class="container">
        <div class="content">
            <h3 class="header3">Products</h3>
            <?php if (!empty($cartItems)) : ?>
                <?php foreach ($cartItems as $cartItem) : ?>
                    <div class="product-row">
                        <img src="product1.jpg" alt="Product Image">
                        <div class="product-details">
                            <h3><?php echo $cartItem['product_name']; ?></h3>
                            <p>Quantity: <?php echo $cartItem['quantity']; ?></p>
                            <p>Price: $<?php echo $cartItem['price']; ?></p>
                        </div>
                        <span class="remove" onclick="removeCartItem('<?php echo $cartItem['cart_id']; ?>')">Remove</span>
                    </div>
                    <hr class="product-separator">
                <?php endforeach; ?>
            <?php else : ?>
                <p>No orders in the cart.</p>
            <?php endif; ?>
        </div>
        <div class="summary">
            <h2>Summary</h2>
            <?php if (!empty($cartItems)) : ?>
                <?php
                $subtotal = 0;
                foreach ($cartItems as $cartItem) {
                    $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                    $subtotal += $itemTotal;
                    ?>
                    <div class="total">
                        <span><?php echo $cartItem['product_name']; ?></span>
                        <span>$<?php echo $itemTotal; ?></span>
                    </div>
                <?php } ?>
                <div class="total-line"></div>
                <div class="total">
                    <span>Total:</span>
                    <span>$<?php echo $subtotal; ?></span>
                </div>
                <div class="checkout-button">
                    <a href="CheckoutPage.php">Proceed to Checkout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
<!--<footer>
		<div class="footer-logo">
			<a href="index.html" class="logo"><img src="img/logo.jpeg" alt="Logo" class="logo-f"></a>
		</div>
		<nav class="footer-navigation">
			<ul>
				<li><a href="About Us.html">About Us</a></li>
				<li><a href="contact.php">Contact Us</a></li>
				<li><a href="FAQpage.html">FAQ</a></li>
				<li><a href="terms and conditions.html">Terms and Conditions</a></li>
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
	</footer>-->
</body>
</html>
