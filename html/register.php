<?php
if (isset($_POST['submit'])) {
    try {
        $username = $_POST['username'];
        $email    = $_POST['email'];
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        include './mysqlConnection.php';

        $checkQuery = "SELECT username, email FROM users";
        $checkSql   = mysqli_query($connection, $checkQuery);
        while ($row = mysqli_fetch_row($checkSql)) {
            if ($row[0] == $username || $row[1] == $email) {
                echo ("<script>alert('Username or Email already in use'); window.location.href = './register.php'</script>");
                die();
            }
        }

        $registerQuery = "INSERT INTO users value ('$username', '$email', '$hashedPassword')";
        $registerSql   = mysqli_query($connection, $registerQuery);
        echo ("<script>alert('User created succesfully'); window.location.href = './login.php'</script></script>");
        die();
    } catch (Exception $e) {
        header("Location: error.php?type=RegisterError");
    } catch (Error $e) {
        header("Location: error.php?type=RegisterError");
    }
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
    <title>Signup to Confessions</title>
</head>

<body>
    <?php
    include "navbar.php";
    ?>

    <div class="login-space">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <input type="text" id="username" name="username" class="input-field" placeholder="Username">
            <input type="email" id="email" name="email" class="input-field" placeholder="Email">
            <input type="password" id="password" name="password" class="input-field" placeholder="Password">
            <button type="submit" id="submit" class="submit" name="submit" onclick="return register()">Register</button>
            <p class="invalid_password" hidden id="invalid_password"></p>
        </form>
    </div>


    <?php include ("footer.php"); ?>
    <script src="../javaScript/script.js"></script>
</body>

</html>