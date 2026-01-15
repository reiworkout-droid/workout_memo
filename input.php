<?php
//日付をカレンダーから取得
$date = $_GET['date'] ?? date('Y-m-d'); // 未指定なら今日

//DB接続
include('functions.php');   

$pdo = connect_to_db();//さくら用
    // $pdo = connect_to_db_pre();//ローカルホスト用

//カテゴリー
$sql = 'SELECT DISTINCT categories FROM workout_menu ORDER BY categories';
$stmt = $pdo->prepare($sql);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categoryLabels = [
    'abs' => '腹筋',
    'back' => '背中',
    'chest' => '胸',
    'leg' => '脚',
    'shoulder' => '肩',
    'triceps' => '三頭',
    'biceps' => '二頭',
];

//種目用
$sql = 'SELECT menu, categories FROM workout_menu ORDER BY categories, menu';
$stmt = $pdo->prepare($sql);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./input.css">    
    <title>workout_memo</title>
</head>
<body>
    <form action="create.php" method="POST">
        <fieldset>
            <legend>今日のトレーニング</legend>
            <div class="selectedDate">
                📅 <?= htmlspecialchars($date) ?>
            </div>
            <button type="button" id="addMenu">メニュー追加</button>
            <div class="textArea">
                <div class="categories">
                    <select name="categories[]" class="categorySelect" required>
                        <option value="">部位選択</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php
                                $key = $cat['categories'];//保存用(value)
                                $label = $categoryLabels[$key];
                            ?>
                            <option value="<?= htmlspecialchars($key) ?>">
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="menu">
                    <select name="menu[]" class="menuSelect" required>
                        <option value="">種目選択</option>
                    </select>
                </div>
                <div class="mainText">
                    <div class="numberText">
                        <div class="weight">
                            <input type="number" name="weight[]"><span>kg</span>
                        </div>
                        <div class="reps">
                            <input type="number" name="reps[]"><span>回</span>
                        </div>
                        <div class="max">
                            1RM: 
                            <span class="rmDisplay">-</span>
                            <input type="hidden" name="max[]" class="rm"><span>kg</span>
                        </div>
                    </div>
                    <div class="memo">
                        メモ: <input type="text" name="memo[]">
                    </div>
                </div>
                <div class="setButton">
                    <button type="button" class="addSet">＋</button>
                </div>
            </div>

            <div class="formButton">
                <button type="button" class="addForm">＋</button>
            </div>

            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
        
            <button id="saveButton">保存</button>
        
        </fieldset>
    </form>
<script>
    document.addEventListener('click', function (event) {
        // .addSet がクリックされたか判定
        if (event.target.matches('.addSet')) {

            // 今いる textArea を取得
            const textArea = event.target.closest('.textArea');
            const setButton = event.target.closest('.setButton');

            // 選択中の値を取得 ← ★これが足りなかった
            const category = textArea.querySelector('.categorySelect')?.value ?? '';
            const menu     = textArea.querySelector('.menuSelect')?.value ?? '';

            const set = `
            <div class="mainText">
                <input type="hidden" name="categories[]" value="${category}">
                <input type="hidden" name="menu[]" value="${menu}">
                <div class="numberText">
                    <div class="weight">
                        <input type="number" name="weight[]"><span>kg</span>
                    </div>
                    <div class="reps">
                        <input type="number" name="reps[]"><span>回</span>
                    </div>
                    <div class="max">
                        1RM: 
                        <span class="rmDisplay">-</span>
                        <input type="hidden" name="max[]" class="rm"><span>kg</span>
                    </div>
                </div>
                <div class="memo">
                    メモ: <input type="text" name="memo[]">
                </div>
            </div>
            `;
        
            // 直前に追加
            setButton.insertAdjacentHTML('beforebegin', set);
        }
    });

    //種目追加
    const form = 
    `<div class="textArea">
        <div class="categories">
            <select name="categories[]" class="categorySelect" required>
                <option value="">部位選択</option>
                <?php foreach ($categories as $cat): ?>
                    <?php
                        $key = $cat['categories'];//保存用(value)
                        $label = $categoryLabels[$key];
                    ?>
                    <option value="<?= htmlspecialchars($key) ?>">
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="menu">
            <select name="menu[]" class="menuSelect" required>
                <option value="">種目選択</option>
            </select>
        </div>
        <div class="mainText">
            <div class="numberText">
                <div class="weight">
                    <input type="number" name="weight[]"><span>kg</span>
                </div>
                <div class="reps">
                    <input type="number" name="reps[]"><span>回</span>
                </div>
                <div class="max">
                    1RM: 
                    <span class="rmDisplay">-</span>
                    <input type="hidden" name="max[]" class="rm"><span>kg</span>
                </div>
            </div>
            <div class="memo">
                メモ: <input type="text" name="memo[]">
            </div>
            <div class="setButton">
                <button type="button" class="addSet">＋</button>
            </div>
        </div>
    </div>`;

    document.addEventListener('click', function (event) {
    // .addSet がクリックされたか判定
    if (event.target.matches('.addForm')) {

        // 一番近い .setButton を探す
        const formButton = event.target.closest('.formButton');

        // 直前に追加
        formButton.insertAdjacentHTML('beforebegin', form);
    }
    });

    //種目選択エリア
const menus = <?= json_encode($menus, JSON_UNESCAPED_UNICODE) ?>;

document.addEventListener('change', function (event) {

  const categorySelect = event.target.closest('.categorySelect');
  if (!categorySelect) return;

  const textArea = categorySelect.closest('.textArea');
  const menuSelect = textArea.querySelector('.menuSelect');

  const selectedCategory = categorySelect.value;

  // 初期化
  menuSelect.innerHTML = '<option value="">種目選択</option>';

  menus.forEach(menu => {
    if (menu.categories === selectedCategory) {
      const option = document.createElement('option');
      option.value = menu.menu;
      option.textContent = menu.menu;
      menuSelect.appendChild(option);
    }
  });
});

//1RMの重量を計算する
document.addEventListener('input', function (event) {

  // weight / reps 以外の入力は無視
  if (
    !event.target.closest('input[name="weight[]"]') &&
    !event.target.closest('input[name="reps[]"]')
  ) {
    return;
  }

  // 同じ種目ブロックを取得
  const mainText = event.target.closest('.mainText');
  if (!mainText) return;

  // 各input取得
  const weightInput = mainText.querySelector('input[name="weight[]"]');
  const repsInput   = mainText.querySelector('input[name="reps[]"]');
  const rmHidden    = mainText.querySelector('input[name="max[]"]');
  const rmDisplay   = mainText.querySelector('.rmDisplay');

  const weight = parseFloat(weightInput.value);
  const reps   = parseInt(repsInput.value, 10);

  // 未入力・不正値ならクリア
  if (!weight || !reps || reps <= 0) {
    rmHidden.value = '';
    rmDisplay.textContent = '-';
    return;
  }

  // 1RM計算（Epley式）
  const rm = weight * (1 + (reps - 1) / 40);
  // 表示用（小数1桁）
  rmDisplay.textContent = rm.toFixed(1);

  // 保存用（hidden）
  rmHidden.value = rm.toFixed(1);
});

document.getElementById('addMenu').addEventListener('click', () => {
    window.location.href = 'menu.php'
});
</script>    
</body>
</html>