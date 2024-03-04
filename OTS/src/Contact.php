<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="UTF-8">
	<title>Contact Us</title>
	<link rel="stylesheet" href="styles/contact styles.css">
</head>
<body>
  
  <header>
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
				<!--<a href="#" class="profile-name">Aathif Zahir</a>-->
            </div>
        </div>
    </header>
  
  <div id="contact-header">
    <h1>Drop Us a Line</h1>
    <div class="link-lists">
      <ul class="link-list">
        <li><img src="img/location.svg" class="list-img"><a href="#">Kandy, Sri Lanka</a></li>
        <li><img src="img/mail.svg" class="list-img"><a href="#" >ots@gmail.com</a></li>
        <li><img src="img/call.svg" class="list-img"><a href="#">075 - 120 3402</a></li>
      </ul>
    </div>
  </div>

  <main>
    <section class="main-section">
      <div class="contact-form">
        <form method="POST" action="">
          <h2>Contact Us</h2>
          <label>Name</label>
          <input type="text" name="name" placeholder="Your Name">
          <label>Email</label>
          <input type="email" name="email" placeholder="Your Email">
          <label>Question</label>
          <textarea name="message" placeholder="Your Message"></textarea>
          <button type="submit">Submit</button>
        </form>
      </div>
      <div class="contact-image">
        <img src="img/contact-image.jpg" alt="Contact Image">
      </div>
    </section>
  </main>
  
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

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST["name"];
		$email = $_POST["email"];
		$message = $_POST["message"];
		$date = date("Y-m-d");

		// Prepare the SQL statement
		$sql = "INSERT INTO inquiry (Name, Email, Message, Date)
				VALUES ('$name', '$email', '$message', '$date')";

		if ($conn->query($sql) === true) {
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}

	$conn->close();
	?>

  
  <footer>
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
	</footer>

</body>
</html>
