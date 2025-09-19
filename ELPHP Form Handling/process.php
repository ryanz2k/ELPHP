<?php
session_start();

$email = $_POST["email"];
$password = $_POST["password"];

$_SESSION["email"] = $email;
$_SESSION["password"] = $password;

setcookie("email", $email, time() + 3600, "/");
setcookie("password", $password, time() + 3600, "/");

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 0;
}

$_SESSION['user']++;

$redirectpage = "profile.php";

$redirectURL = $redirectpage . "?user=" . $_SESSION['user'];

header("Location: " . $redirectURL);
exit();
?>