<?php
include("functions.php");

session_start();
check_session_id();

session_start();
//セッションを空にする
$_SESSION = array();
//情報を削除する
if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 42000, '/');
}
//サーバーが用意した領域を壊す
session_destroy();
header('login.php');
exit();