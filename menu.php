<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu</title>
</head>
<body>
    <form action="menu_create.php">
        <fieldset>
            <legend>種目を追加</legend>
            <a href="./index.php">一覧画面</a>
            <div>
                カテゴリー：
                <select name="categories" id="categories">
                    <option value="" disabled selected>選択してください</option>
                    <option value="chest">胸</option>
                    <option value="back">背中</option>
                    <option value="leg">脚</option>
                    <option value="biceps">腕</option>
                    <option value="shoulder">肩</option>
                    <option value="abs">腹筋</option>
                </select>
            </div>
            <div>
                種目名：<input type="text" name="menu">
            </div>
            <div>
                <button>登録</button>
            </div>
        </fieldset>
    </form>
</body>
</html>