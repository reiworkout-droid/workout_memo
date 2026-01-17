<?php
include("functions.php");

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザ登録画面</title>
</head>

<body>
  <form action="register_act.php" method="POST">
    <fieldset>
      <legend>筋トレメモ ユーザ登録画面</legend>
      <div>
        メールアドレス: <input type="text" name="username">
      </div>
      <div>
        password: <input type="text" name="password">
      </div>
      <div>
        <button>登録</button>
      </div>
      <a href="login.php">or login</a>
    </fieldset>
  </form>

</body>

</html>