<nav class="navBar">
  <div id="logo-container">
    <img src="../images/logo-no-background.png" alt="Confession_Logo" id="logo" />
  </div>

  <div id="text-container">
    <p>Confess Your Love.</p>
  </div>
  <div id="login-container">
    <?php if (isset($_SESSION['email'])) {
      $email = $_SESSION['email'];
      ?>
      <a href='./profile.php'>Profile</a>
      <button id='logout_btn' onclick='logout()'>Logout</button>
    <?php } else {
      echo ("<button id='login_btn' onclick='loginRedirect()'>Login</button>");
    } ?>
  </div>
</nav>