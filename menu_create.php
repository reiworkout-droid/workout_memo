<?php
//DB接続
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
$pdo = connect_to_db();

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

header("Location:index.php");
exit();

?>