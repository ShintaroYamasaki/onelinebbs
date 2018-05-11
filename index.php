<?php
require "dto.php";

// TODO: If session doesn't have login info, then the page redirects to login page.
session_start();

if (!isset($_SESSION["user_name"])) {
  header("Location: login.php?sessiont_destroied");
  exit();
}

$user_name = $_SESSION["user_name"];

$dto = new DTOBBS();
// Connect to DB
if ($dto->connect() == false) {
  echo $dto->error_msg;
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>One Line BBS</title>
</head>
<body>
  <h1>One line BBS</h1>
  <?php if (isset($_GET["failed"])) echo "Failed<br>"; ?>
  <form action="delete_comment.php" method="POST">
  <table border="1">
    <tr><th>time</th><th>name</th><th>comment</th><th>select</th></tr>
    <?php
    $rst = $dto->receiveComments();
    if ($rst == null) {
      echo $dto->error_msg;
    } else {
      foreach ($rst as $post) {
        $id = $post["id"];
        $created_at = htmlspecialchars($post["created_at"]);
        $name = htmlspecialchars($post["name"]);
        $comment = htmlspecialchars($post["comment"]);
    ?>
    <tr>
      <td><?php echo $created_at; ?></td>
      <th><?php echo $name; ?></th>
      <th><?php echo $comment; ?></th>
      <th>
      <?php if ($name == $user_name): ?>
        <input type="checkbox" name="select[]"  value="<?php echo $id; ?>"/>
      <?php endif; ?>
      </th>
    </tr>
    <?php
      }
    }
    ?>
  </table>
  <input type="submit" value="delete" /></form>
<hr>
<form action="insert_comment.php" method="POST">
  name: <?php echo htmlspecialchars($user_name);?><input type="hidden" name="name" value="<?php echo $user_name;?>" /><br>
  comment: <input type="text" name="comment" /><br>
  <input type="submit" value="send" />
</form>
<?php $dto->disconnect(); ?>
<p>
  <a href="confirm_logout.php">Logout</a>
</p>
</body>
</html>