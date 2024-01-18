<?php
session_start();

  $email = $password = "";

  if (isset($_POST['login'])) {
    
  $email    = $_POST['email'];
  $password = $_POST['password'];
  include './mysqlConnection.php';

  $checkQuery = "SELECT email, `password` FROM users WHERE email = '$email'";

  $checkSql = mysqli_query($connection, $checkQuery);

  if (mysqli_num_rows($checkSql) > 0) {
      $row = mysqli_fetch_row($checkSql);
  
      if ($row[1] == $password) {
        $_SESSION['email'] = $email;
        echo("<script> alert('You may now enter the site!'); window.location.href='./confessions.php'; </script>");
          die();
      } else {
        echo("<script> document.getElementById('invalid_pass').innerText = 'Incorrect Password!'; </script>");
      }
  } else {
    echo ("<script> alert('No such user found!'); window.location.href='./login.php'; </script>");
      die();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Salsa&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style/login.css">
  <title>Login to Confessions</title>
</head>

<body>
  <?php
  include "navbar.php";
  ?>

  <div class="login-space">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="email" id="email" name="email" class="input-field" placeholder="Email" value="<?php echo $email ?>">


      <p class="invalid_email" id="invalid_email"></p>
      <input type="password" id="password" name="password" class="input-field" placeholder="Password">
      <p class="invalid_password" id="invalid_password"></p>
      <button type="submit" id="submit" name="login">Login</button>
      <p><a href="./register.php">Register?</a></p>
    </form>
  </div>



  <script src="../javaScript/script.js"></script>
</body>

</html>