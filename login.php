<?php


session_start();

if (isset($_SESSION["user_name"]))
  unset($_SESSION["user_name"]);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>One Line BBS | Login</title>
</head>
<body>
  <h1>One line BBS</h1>
  <div id="message">
  <?php
    if (isset($_GET['incorrect'])) {
      echo "Incorrect";
    } else if (isset($_GET['session_destroied'])) {
      echo "Session has been destroied.";
    } else if (isset($_GET['logout'])) {
      echo "Logouted";
    }
    echo "<br>";
  ?>
  </div>
  <form action="confirm_login.php" method="POST">
    name: <input type='text' name='name' /><br>
    password: <input type='password' name='password' /><br>
    <input type='submit' value='login' />
  </form>
</body>
</html>