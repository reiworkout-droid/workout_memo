<?php
//DB接続
include('functions.php');

if (
  !isset($_POST['menu']) || $_POST['menu'] === '' ||
  !isset($_POST['weight']) || $_POST['weight'] === '' ||
  !isset($_POST['reps']) || $_POST['reps'] === '' ||
  !isset($_POST['memo']) || $_POST['memo'] === '' ||
  !isset($_POST['max']) || $_POST['max'] === '' ||
  !isset($_POST['date']) || $_POST['date'] === ''
) {
  exit('paramError');
}

$categories = $_POST['categories'];
$menu = $_POST['menu'];
$weight = $_POST['weight'];
$reps = $_POST['reps'];
$memo = $_POST['memo'];
$max = $_POST['max'];
$date = $_POST['date'];

// DB接続
$pdo = connect_to_db();//さくら用
// $pdo = connect_to_db_pre();//ローカルホスト用

$sql = 'INSERT INTO workout_menu(id, menu, weight, reps, date,created_at, updated_at) VALUES(NULL, :menu, :weight, reps, date, now(), now())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':menu', $menu, PDO::PARAM_STR);
$stmt->bindValue(':weight', $weight, PDO::PARAM_STR);
$stmt->bindValue(':reps', $reps, PDO::PARAM_STR);
$stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
$stmt->bindValue(':max', $max, PDO::PARAM_STR);
$stmt->bindValue(':date', $date, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:index.php");
exit();

?>