<?php
// 入力項目のチェック
include('functions.php');

if (
  !isset($_POST['categories']) || $_POST['categories'] === '' ||
  !isset($_POST['menu']) || $_POST['menu'] === '' ||
  !isset($_POST['weight']) || $_POST['weight'] === '' ||
  !isset($_POST['reps']) || $_POST['reps'] === '' ||
  !isset($_POST['max']) || $_POST['max'] === '' ||
  !isset($_POST['memo']) || $_POST['memo'] === '' ||
  !isset($_POST['date']) || $_POST['date'] === '' ||
  !isset($_POST['id']) || $_POST['id'] === ''
) {
  exit('paramError');
}

$category = $_POST['categories'][0];
$menu = $_POST['menu'][0];
$weight = $_POST['weight'][0];
$reps = $_POST['reps'][0];
$max = $_POST['max'][0];
$memo = $_POST['memo'][0];
$date = $_POST['date'];
$id = $_POST['id'];

$pdo = connect_to_db();//さくら用
// $pdo = connect_to_db_pre();//ローカルホスト用

// SQL実行
$sql = 'UPDATE workout_memo SET categories=:categories, menu=:menu, weight=:weight, reps=:reps, max=:max, memo=:memo, date=:date, updated_at=now() WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':categories', $category, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す
$stmt->bindValue(':menu', $menu, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す
$stmt->bindValue(':weight', $weight, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す
$stmt->bindValue(':reps', $reps, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す
$stmt->bindValue(':max', $max, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す
$stmt->bindValue(':memo', $memo, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す
$stmt->bindValue(':date', $date, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す
$stmt->bindValue(':id', $id, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header('Location:index.php');
exit();