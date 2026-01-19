<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./login.css">
  <title>ログイン画面</title>
</head>

<body>
  <form action="login_act.php" method="POST">
    <fieldset>
      <legend><span class="colorTitle">筋トレメモ</span> ログイン画面</legend>
      <div class="textArea">
        <div class="email">
          <div>ユーザーネーム:</div>
          <input type="text" name="username" class="username" placeholder="メールアドレスを入力して下さい">
        </div>
        <div class="pass">
          <div>パスワード:</div>
          <input type="text" name="password" class="password" placeholder="パスワードを入力して下さい">
        </div>
        <div class="login">
          <button class="loginButton">ログイン</button>
        </div>
        <div class="register">
          <span class="refisterOr">または</span>
          <a href="register.php">登録する</a>
        </div>
      </div>
    </fieldset>
  </form>

</body>

</html>