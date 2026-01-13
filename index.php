<?php
//日付をカレンダーから取得
$date = $_GET['date'] ?? date('Y-m-d'); // 未指定なら今日

//DB接続
include('functions.php');   

// $pdo = connect_to_db();//さくら用
$pdo = connect_to_db_pre();//ローカルホスト用

//選択している日又は当日のデータのみを取得する
$sql = 'SELECT * FROM workout_memo WHERE date = :date';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':date', $date, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
//オブジェクトとして取得
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoryLabels = [
    'abs' => '腹筋',
    'back' => '背中',
    'chest' => '胸',
    'leg' => '脚',
    'shoulder' => '肩',
    'triceps' => '三頭',
    'biceps' => '二頭',
];

foreach ($result as $record) {
    $category = $categoryLabels[$record['categories']] ?? $record['categories'];

    $output .= "
        <div class='workout-card'>
            <div class='category'>{$category}</div>
            <div class='menu'>{$record['menu']}</div>

            <div class='numbers'>
                <span class='weight'>{$record['weight']}kg</span>
                <span class='reps'>{$record['reps']}回</span>
                <span class='max'>{$record['max']}</span>
            </div>

            <div class='memo'>{$record['memo']}</div>

            <div class='actions'>
                <a href='edit.php?id={$record['id']}'>edit</a>
                <a href='delete.php?id={$record['id']}'>delete</a>
            </div>
        </div>
    ";
}
// $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// //種目用
// $sql = 'SELECT menu, categories FROM workout_menu ORDER BY categories, menu';

// $stmt = $pdo->prepare($sql);

// try {
//   $status = $stmt->execute();
// } catch (PDOException $e) {
//   echo json_encode(["sql error" => "{$e->getMessage()}"]);
//   exit();
// }

// $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./index.css">    
    <title>workout_memo</title>
</head>
<body>
            <h1>今日のトレーニング</h1>
            <div class="selectedDate">
                📅 <?= htmlspecialchars($date) ?>
                <button type="button" id="dateSelect">日付選択</button>
            </div>
            <?= $output ?>

        
<script>
// カレンダークリックアクション
document.getElementById('dateSelect').addEventListener('click', () => {
    window.location.href = 'calendar.php';
});

</script>    
</body>
</html>