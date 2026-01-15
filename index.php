<?php
//日付をカレンダーから取得
$date = $_GET['date'] ?? date('Y-m-d'); // 未指定なら今日

//DB接続
include('functions.php');   

$pdo = connect_to_db();//さくら用
// $pdo = connect_to_db_pre();//ローカルホスト用

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

$output = '';

foreach ($result as $record) {
    $category = $categoryLabels[$record['categories']] ?? $record['categories']; 

    $output .= "
        <div class='workout-card'>
            <div>
                <input type='hidden' name='id' value='{$record['id']}'>
            </div>
            <div class='category'>部位:{$category}</div>
            <div class='menu'>{$record['menu']}</div>

            <div class='numbers'>
                <div class='weight'>{$record['weight']}kg</div>
                <div class='reps'>{$record['reps']}回</div>
                <div class='max'>1RM: {$record['max']}kg</div>
            </div>

            <div class='memo'>メモ: {$record['memo']}</div>

            <div class='actions'>
                <a href='edit.php?id={$record['id']}'>編集</a>
                <a href='delete.php?id={$record['id']}'>削除</a>
            </div>
        </div>
    ";
}

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
    <div id="button">
        <button type="button" id="addButton">+</button>
    </div>

        
<script>
// カレンダークリックアクション
document.getElementById('dateSelect').addEventListener('click', () => {
    window.location.href = 'calendar.php';
});

//追加ボタンクリックアクション
document.getElementById('addButton').addEventListener('click', () => {
    window.location.href = 'input.php';
});

</script>    
</body>
</html>