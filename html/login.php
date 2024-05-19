<?php

session_start();



if (isset($_SESSION['email'])) {
  header("Location: ./confessions.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../images/confessions-favicon-color.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Salsa&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../style/login.css">
  <title>Login to Confessions</title>
</head>

<body>
  <?php
  include "navbar.php";
  ?>

  <div class="login-space">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
      <input type="email" id="email" name="email" class="input-field" placeholder="Email" value=<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>>

      <p class="invalid_email" id="invalid_email"></p>
      <input type="password" id="password" name="password" class="input-field" placeholder="Password">
      <p class="invalid_password" id="invalid_password"></p>
      <button type="submit" class="submit" id="submit" name="login">Login</button>
      <a class="submit" href="./register.php">Register?</a>
      <?php if (isset($_GET["error"])) {
        echo ("<div class='error'> Couldn't login, please try again later! </div>");
      } ?>
    </form>
  </div>

  <?php

  if (isset($_POST['login'])) {
    try {
      $email    = $_POST['email'];
      $password = $_POST['password'];
      include './mysqlConnection.php';

      $checkQuery = "SELECT email, pass, username FROM users WHERE email = '$email'";
      $checkSql   = mysqli_query($connection, $checkQuery);
      if (mysqli_num_rows($checkSql) > 0) {
        $row = mysqli_fetch_row($checkSql);

        if (password_verify($password, $row[1])) {
          $_SESSION['username'] = $row[2];
          $_SESSION['email']    = $email;
          echo ("<script> window.location.href='./confessions.php'; </script>");
          die();
        } else {
          // Incorrect password, redirect with email parameter
          echo ("<script> 
                    document.getElementById('invalid_password').innerText = 'Incorrect Password!';
                    document.getElementById('email').value = '" . htmlspecialchars($email) . "';
                    // window.location.href='./login.php?email=" . urlencode($email) . "';
                  </script>");
          die();
        }
      } else {
        // User not registered, redirect with email parameter
        echo ("<script> 
                // window.location.href='./login.php?email=" . urlencode($email) . "'; 
                document.getElementById('invalid_email').innerText = 'User Not Registered!';
                document.getElementById('email').value = '" . htmlspecialchars($email) . "';
              </script>");
        die();
      }
    } catch (Exception $e) {
      header("Location: error.php?type=LoginError");
    } catch (Error $e) {
      header("Location: error.php?type=LoginError");
    }
  }



  ?>

  <script src="../javaScript/script.js"></script>
  <?php include("footer.php"); ?>
</body>

</html>