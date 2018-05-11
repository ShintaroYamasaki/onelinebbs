<?php
$user_name = $_POST["name"];
$user_password = $_POST["password"];

require "dto.php";

$dto = new DTOBBS();
// Connect to DB
if ($dto->connect() == false) {
  echo $dto->error_msg;
  exit();
}

if ($dto->confirmLoginAccount($user_name, $user_password)) {
  session_start();
  $_SESSION["user_name"] = $user_name;
  header("Location: index.php");
} else {
  header("Location: login.php?incorrect");
}

exit();
?>