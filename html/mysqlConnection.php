<?php

$env = parse_ini_file('.env');

$servername = "localhost";
$uname      = $env["DBUSERNAME"];
$pass       = $env["DBPW"];
$dbname     = "id22184385_confessions";
try {
    $connection = new mysqli($servername, $uname, $pass);

    $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $dbname
CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;";
    if ($connection->query($sqlCreateDatabase) === FALSE) {
        die("Error creating database: " . $connection->error);
    }

    $connection->select_db($dbname);

    $sqlCreateUsersTable = "CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(30) PRIMARY KEY,
    email VARCHAR(30),
    pass VARCHAR(255)
    )
    CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;";

    $sqlInsertAnonymous = "INSERT INTO users VALUE(
                'ANONYMOUS',
                'ANONYMOUS',
                'ANONYMOUS'
            )";

    if ($connection->query($sqlCreateUsersTable) === FALSE || $connection->query($sqlInsertAnonymous) === FALSE) {
        die("Error creating table 'users': " . $connection->error);
    }


    $sqlCreateConfessionsTable = "CREATE TABLE IF NOT EXISTS confessions (
        confId INT PRIMARY KEY AUTO_INCREMENT,
        content TEXT,
        usernameBy VARCHAR(30),
        usernameTo VARCHAR(30),
        FOREIGN KEY (usernameBy) REFERENCES users(username),
        FOREIGN KEY (usernameTo) REFERENCES users(username)
    )";


    if ($connection->query($sqlCreateConfessionsTable) === FALSE) {
        throw (new Exception("Error creating table 'confessions': " . $connection->error));
    }


} catch (Exception $e) {
    echo "<script> alert($e) </script>";
} catch (Error $e) {
    echo "<script> alert($e) </script>";
}