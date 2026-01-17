<?php
// データ受け取り
include('functions.php');

if (
  !isset($_GET['id']) || $_GET['id'] === ''
) {
  exit('paramError');
}

$id = $_GET['id'];

// $pdo = connect_to_db();//さくら用
$pdo = connect_to_db_pre();//ローカルホスト用

// SQL実行
//テーブルから削除
$sql = 'DELETE FROM workout_memo WHERE id=:id';

// 論理削除
// $sql = 'UPDATE todo_table SET deleted_at=now() WHERE id=:id';


$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);//SQL CHAR, VARCHAR, または他の文字列データ型を表す

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header('Location:index.php');
exit();