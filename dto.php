<?php
class DTOBBS {
  public $error_msg = "";
  private $pdo = null;

  const DB_HOST = "127.0.0.1";
  const DB_PORT = "3306";
  const DB_NAME = "oneline_bbs";
  const DB_USER = "root";
  const DB_PASSWORD = "root";

  const SQL_INSERT_COMMENT = "INSERT INTO post values(null, :name, :comment, :datetime);";
  const SQL_DELETE_COMMENT = "DELETE FROM post WHERE id = :id;";
  const SQL_SELECT_ALLCOMMENT = "SELECT * FROM post ORDER BY created_at DESC";
  const SQL_SELECT_USER = "SELECT * FROM user WHERE name = :name;";

  protected function __constract() {
    // do something
  }

  public function connect() {
    $dsn = 'mysql:dbname='.self::DB_NAME.';host='.self::DB_HOST.';port='.self::DB_PORT;

    if ($this->pdo == null) {
      try {
        $this->pdo = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        // Failed to connect
        $this->error_msg = $e->getMessage();
        $this->pdo = null;
        return false;
      }
    }

    // Succeeded to connect
    return true;
  }

  public function disconnect() {
    $this->pdo = null;
    return true;
  }

  public function confirmLoginAccount($name, $password) {
    try {
      if ($this->pdo == null) {
        throw new PDOException("Database is NOT connected.", 2332, null);
      }

      $statement = $this->pdo->prepare(self::SQL_SELECT_USER);
      $statement->bindParam(':name', $name);
      $statement->execute();

      if ($statement == false)
        throw new PDOException("Failed to select from database.", 2332, null);

      $is_correct = false;
      foreach ($statement->fetchAll() as $user) {
        $is_correct = ($user['password'] == $password);
      }

      return $is_correct;
    } catch (PDOException $e) {
      $this->error_msg = $e->getMessage();
      return false;
    }

    return true;

  }

  public function sendComment($name, $comment){
    try {
      if ($this->pdo == null) {
        throw new PDOException("Database is NOT connected.", 2332, null);
      }

      $this->pdo->beginTransaction();

      $statement = $this->pdo->prepare(self::SQL_INSERT_COMMENT);

      date_default_timezone_set('Asia/Tokyo');
      $current_time = date('Y-m-d H:i:s');
      $statement->bindParam(':name', $name);
      $statement->bindParam(':comment', $comment);
      $statement->bindParam(':datetime', $current_time);

      $statement->execute();

      $exerst = $statement->rowCount();
      if ($exerst != 1)
        throw  new PDOException("Failed to insert to database", 2332, null);

      $this->pdo->commit();

      $statement->closeCursor();
    } catch (PDOException $e) {
      $this->pdo->rollBack();
      $statement->closeCursor();

      $this->error_msg = $e->getMessage();
      $this->pdo = null;
      return false;
    }
    return true;
  }

  public function receiveComments() {
    $posts = array();

    try {
      if ($this->pdo == null) {
        throw new PDOException("Database is NOT connected.", 2332, null);
      }

      $statement = $this->pdo->prepare(self::SQL_SELECT_ALLCOMMENT);
      $statement->execute();

      if ($statement == false)
        throw new PDOException("Failed to select from database.", 2332, null);

      foreach ($statement->fetchAll() as $post) {
        $posts[] = $post;
      }
    } catch (PDOException $e) {
      $this->error_msg = $e->getMessage();
      $this->pdo = null;
      return null;
    }

    return $posts;
  }

  public function deleteComment($id) {
    try {
      if ($this->pdo == null) {
        throw new PDOException("Database is NOT connected.", 2332, null);
      }

      $statement = $this->pdo->prepare(self::SQL_DELETE_COMMENT);
      $statement->bindParam(':id', $id);
      $statement->execute();

      $exerst = $statement->rowCount();
      if ($exerst != 1)
        throw  new PDOException("Failed to delete from database", 2332, null);

    } catch (PDOException $e) {
      $this->error_msg = $e->getMessage();
      $this->pdo = null;
      return false;
    }

    return true;
  }
}
?>