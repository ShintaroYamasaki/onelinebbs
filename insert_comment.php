<?php
require "dto.php";

$dto = new DTOBBS();
// Connect to DB
if ($dto->connect() == false) {
  echo $dto->error_msg;
  exit();
}

if (isset($_POST['name']) && isset($_POST['comment']))  {
  $rst = $dto->sendComment($_POST['name'], $_POST['comment']);
  if ($rst == false) {
    echo $dto->error_msg;
  }
}

$dto->disconnect();

header("Location: index.php");
exit();
?>