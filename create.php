<?php
//DB接続
include('functions.php');
session_start();

if (
    empty($_POST['categories']) ||
    empty($_POST['menu']) ||
    empty($_POST['weight']) ||
    empty($_POST['reps']) ||
    empty($_POST['max']) ||
    empty($_POST['memo']) ||
    empty($_POST['date'])
) {
    exit('paramError');
}
$user_id = $_SESSION['user_id'];
$categories = $_POST['categories'];
$menu = $_POST['menu'];
$weight = $_POST['weight'];
$reps = $_POST['reps'];
$max = $_POST['max'];
$memo = $_POST['memo'];
$date = $_POST['date'];

// DB接続
// $pdo = connect_to_db();//さくら用
$pdo = connect_to_db_pre();//ローカルホスト用

$sql = 'INSERT INTO workout_memo(id, user_id, categories, menu, weight, reps, max, memo, date, created_at, updated_at) VALUES(NULL, :user_id, :categories, :menu, :weight, :reps, :max, :memo, :date, now(), now())';

$stmt = $pdo->prepare($sql);

for ($i = 0; $i < count($weight); $i++) {

    // 空行スキップ（超重要）
    if ($weight[$i] === '' || $reps[$i] === '') {
        continue;
    }

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':categories', $categories[$i], PDO::PARAM_STR);
    $stmt->bindValue(':menu', $menu[$i], PDO::PARAM_STR);
    $stmt->bindValue(':weight', $weight[$i], PDO::PARAM_INT);
    $stmt->bindValue(':reps', $reps[$i], PDO::PARAM_INT);
    $stmt->bindValue(':max', $max[$i], PDO::PARAM_STR);
    $stmt->bindValue(':memo', $memo[$i], PDO::PARAM_STR);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);

    try {
    $status = $stmt->execute();
    } catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
    }
}

header("Location:index.php");
exit();

?>