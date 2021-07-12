<?php
$dbhost = '127.0.0.1';
$dbname = 'guestbook';
$dbuser = '';
$dbpass = '';
$dbchar = 'utf8mb4';

$dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$dbchar";

$opt = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false
];

try {
  $pdo = new PDO($dsn, $dbuser, $dbpass, $opt);
} catch(\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if(isset($_POST['submit'])) {
  $sql = "INSERT INTO posts (post_name, post_content) VALUES (:post_name, :post_content)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':post_name' => htmlentities($_POST['post_name']),
    ':post_content' => htmlentities($_POST['post_content'])
  ]);
}

$sql = "SELECT * FROM posts ORDER BY post_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet">
  <title>Guestbook</title>
</head>
<body>
  <div class="header">
    <h1>Guestbook</h1>
  </div>
  <div class="container">
    <div class="submit">
      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
          <input type="text" name="post_name" placeholder="Name"></textarea>
        </div>
        <div class="form-group">
          <textarea name="post_content" placeholder="Post"></textarea>
        </div>
        <div class="form-group">
          <input type="submit" name="submit" value="Post">
        </div>
      </form>
    </div>
    <div class="posts">
      <?php foreach($posts as $post): ?>
        <div class="post">
          <p><?php echo $post['post_content']; ?></p>
          <span>By <?php echo $post['post_name']; ?> on <?php echo date('l j F Y \a\t H:i', strtotime($post['post_date'])); ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="footer">
    <p>&copy; Guestbook 2021</p>
  </div>
</body>
</html>