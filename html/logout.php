<?php

session_start();
$_SESSION['email'] = "";
session_reset();
session_destroy();
header("Location: ./login.php");

?>