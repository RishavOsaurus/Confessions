<?php
require './mysqlConnection.php';
session_start();

if (isset($_SESSION['email'])) {
	$email = $_SESSION['email'];
    $name= $_SESSION['username'];
}
$GLOBALS['username'] = "";



?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" type="image/x-icon" href="../images/confessions-favicon-color.png" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Salsa&display=swap"
		rel="stylesheet" />
	<link rel="stylesheet" href="../style/profile.css" />
	<link rel="stylesheet" href="../style/hamburger.css">
	<script src="../javaScript/script.js"></script>
	<title>Confession</title>
</head>

<body>
	<?php include "navbar.php"; ?>
	<main>
		<?php
		if (!isset($_SESSION['email']) && !isset($_GET['username'])) {
			echo "Either login, or provide a username to view!<br>";
			echo "<input type='text' id='noLoginProfile'> <input type='submit' onclick='profileRedirect()'>";
		} else {
			?>
			<div id="confessions-section">
				<div class="broder-class confessions-broder-class height">
					<div id="sentConfessions" class="confession-container">
						<div class="profile-confessions">Sent Confessions</div>

						<div class="main-confession-data">
							<div
								style="display: flex; justify-content: space-around; background-color: white; padding: 5px; border-radius: 4px;">
								<div class='username'>User</div>
								<div class='content'>Confession</div>
							</div>
							<?php

							// Set the character set and collation for the connection
							if (!$connection->set_charset("utf8mb4")) {
								echo "<script>alert('Error loading character set utf8mb4: " . $connection->error . "');</script>";
								exit();
							}

							if ($connection->query("SET collation_connection = 'utf8mb4_unicode_ci'") === FALSE) {
								echo "<script>alert('Error setting collation: " . $connection->error . "');</script>";
								exit();
							}

							if (!isset($_GET['username'])) {
								$userQuery           = "SELECT * FROM users WHERE email='$email'";
								$userSql             = mysqli_query($connection, $userQuery);
								$user                = mysqli_fetch_assoc($userSql);
								$GLOBALS['username'] = $user["username"];
							} else {
								$GLOBALS['username'] = $_GET["username"];
							}

							$username  = $GLOBALS['username'];
							$confQuery = "SELECT * FROM confessions WHERE usernameBy = '$username'";
							$confSql   = mysqli_query($connection, $confQuery);
							if($name==$GLOBALS['username']){
							if (mysqli_num_rows($confSql) > 0) {
								while ($row = mysqli_fetch_assoc($confSql)) {
									$content     = $row['content'];
									$confessedTo = $row['usernameTo'];
									echo "<div class='data-container'><div class='username'>$confessedTo</div><div class='content'>$content</div></div>";
								}
							} else {
								echo "No confessions made by you yet!";
							}
						} else {
							echo "<a href='./login.php'>Login</a> to view this part!";
						}
							?>
						</div>
					</div>
					<div id="receivedConfessions" class="confession-container">
						<div class="profile-confessions">Received Confessions</div>
						<div class="main-confession-data">
							<div
								style="display: flex; justify-content: space-around; background-color: white; padding: 5px; border-radius: 4px;">
								<div class='content'>Confession</div>
								<div class='username'>User</div>
							</div>
							<?php
							if (!isset($_SESSION['email'])) {
								echo "<a href='./login.php'>Login</a> to view this part!";
							} else {
								$username = $GLOBALS['username'];
								$confQuery = "SELECT * FROM confessions WHERE usernameTo = '$username'";
								$confSql = mysqli_query($connection, $confQuery);
								
								if (mysqli_num_rows($confSql) > 0) {
									while ($row = mysqli_fetch_assoc($confSql)) {
										$content = $row['content'];
										$confessedBy = $row['usernameBy'];
										$confessedBy = ($GLOBALS['username'] == $name) ? $confessedBy : 'anon';
										echo "<div class='data-container'><div class='content'>$content</div><div class='username'>$confessedBy</div></div>";
									}
								} else {
									echo "No confessions made for you yet!";
								}
								
							}
							?>
						</div>
					</div>
				</div>
				<div class="broder-class change">
					<div class="info" onclick="copyToClipBoard('<?php echo $GLOBALS['username'] ?>')">Click here to copy
						your Confession Link</div>
				</div>
			</div>
		<?php } ?>
	</main>
	<?php include ("footer.php"); ?>
</body>

</html>