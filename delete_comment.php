<?php
require "dto.php";

// if ($_POST["name"] != $_SESSION["user_name"]) {
//   header("Location: index.php?failed");
//   exit();
// }

$dto = new DTOBBS();
// Connect to DB
if ($dto->connect() == false) {
  echo $dto->error_msg;
  exit();
}

print_r($_POST);

if (isset($_POST['select']))  {
  foreach($_POST['select'] as $id) {
    $rst = $dto->deleteComment($id);
    if ($rst == false) {
      echo $dto->error_msg;
      exit();
    }
  }
}

$dto->disconnect();

header("Location: index.php");
exit();
?>