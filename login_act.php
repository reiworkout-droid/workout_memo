<?php
// データ受け取り
include('functions.php');
session_start();

// SQL実行
$sql = 'SELECT * FROM users_table WHERE username=:username AND deleted_at is NULL';

$username = $_POST['username'];
$password = $_POST['password'];

// DB接続
//サクラ
// $pdo = connect_to_db();
//ローカルホスト
$pdo = connect_to_db_pre();

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// ユーザ有無で条件分岐

$user = $stmt->fetch(PDO::FETCH_ASSOC);//一つを取り出す


// パスワードをハッシュ化したものとの正誤を判別
if (!$user || !password_verify($password, $user['password'])) {
    echo "<p>ログイン情報に誤りがあります。</p>";
    echo "<a href = login.php>ログイン</a>";
    exit();
} else {
    $_SESSION = array();
    $_SESSION['session_id'] =  session_id();
    $_SESSION['user_id'] =  $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is_admin'] = $user['is_admin'];
    header('location: index.php');
    exit();
}

