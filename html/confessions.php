<?php
session_start();
if (!isset($_SESSION['email']) && !isset($_POST['submit'])) {
  header("Location: ./login.php");
  exit();
}

include './mysqlConnection.php';

function alert($msg)
{
  echo "<script type='text/javascript'>alert('$msg');</script>";
}

if ($connection->connect_error) {
  alert("Database connection failed: " . $connection->connect_error);
  exit();
}

if (!$connection->set_charset("utf8mb4")) {
  alert("Error loading character set utf8mb4: " . $connection->error);
  exit();
}

if ($connection->query("SET collation_connection = 'utf8mb4_unicode_ci'") === FALSE) {
  alert("Error setting collation: " . $connection->error);
  exit();
}

if (isset($_POST['submit'])) {
  $content       = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
  $username      = $_POST['anonymous'] ? "ANONYMOUS" : $_SESSION['username'];
  $mentionedUser = htmlspecialchars($_POST['mentionedUser'], ENT_QUOTES, 'UTF-8');

  // Check if the mentioned user exists
  $userCheckQuery = "SELECT username FROM users WHERE username = ?";
  $userCheckStmt  = mysqli_prepare($connection, $userCheckQuery);
  mysqli_stmt_bind_param($userCheckStmt, "s", $mentionedUser);
  mysqli_stmt_execute($userCheckStmt);
  mysqli_stmt_store_result($userCheckStmt);

  if (mysqli_stmt_num_rows($userCheckStmt) == 0) {
    alert("The mentioned user does not exist.");
    mysqli_stmt_close($userCheckStmt);
  } else {
    mysqli_stmt_close($userCheckStmt);

    // Prepare and execute the insert query
    $insertQuery = "INSERT INTO confessions (content, usernameBy, usernameTo) VALUES (?, ?, ?)";
    $insertSql   = mysqli_prepare($connection, $insertQuery);

    if (!$insertSql) {
      alert("Failed to prepare statement: " . mysqli_error($connection));
      exit();
    }

    mysqli_stmt_bind_param($insertSql, "sss", $content, $username, $mentionedUser);

    if (mysqli_stmt_execute($insertSql)) {
      alert('Confession sent successfully!');
    } else {
      alert('Error sending confession! Please try again.');
    }

    mysqli_stmt_close($insertSql);
  }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Salsa&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../style/style.css" />
  <link rel="icon" type="image/x-icon" href="../images/confessions-favicon-color.png" />
  <title>Confess Secretly! ❤️</title>
</head>

<body>
  <?php include 'navbar.php'; ?>
  <form class="confession" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div id="confession-qn">
      <span id="confession-qn-text">Send a Message!</span>
    </div>
    <div id="mention">
      <input type="text" id="mention-text" placeholder="Mention Someone ('cheeku' for test)" name="mentionedUser"
        required>
    </div>
    <div><input type="checkbox" name="anonymous" id="anonymous"> Send as Anonymous?</div>
    <textarea id="confession-ans" name="content"></textarea>
    <div class="confess_container">
      <input type="submit" class="confess_btn" name="submit" value="Confess!😁">
    </div>
  </form>
  <script src="../javaScript/script.js"></script>
  <?php include ("footer.php"); ?>
</body>

</html>