<?php
//DB接続
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('functions.php');

if (
  !isset($_POST['categories']) || $_POST['categories'] === '' ||
  !isset($_POST['menu']) || $_POST['menu'] === ''
) {
  exit('paramError');
}

$menu = $_POST['menu'];
$categories = $_POST['categories'];

// DB接続
$pdo = connect_to_db();//さくら用
// $pdo = connect_to_db_pre();//ローカルホスト用

$sql = 'INSERT INTO workout_menu(id, menu, categories, created_at, updated_at) VALUES(NULL, :menu, :categories, now(), now())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':menu', $menu, PDO::PARAM_STR);
$stmt->bindValue(':categories', $categories, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:menu.php");
exit();

?>