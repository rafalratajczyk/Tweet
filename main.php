<?php

require_once 'src/Connection.php';

session_start();

if (isset($_SESSION["user"]) == false) {
    header("location: login.php");
}

$myUser = $_SESSION["user"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Tweet::createTweet($myUser->getId(), $_POST["tweet"]);
}
echo("Hello " . $myUser->getEmail());

?>


