<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

<?php
// 値をセッションから取得
$name_sei = $_SESSION['login_member']['name_sei'] ?? '';
$name_mei = $_SESSION['login_member']['name_mei'] ?? '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHPkadai</title>
	<link rel="stylesheet" href="../static/style.css">
	<link rel="stylesheet" href="./static/style.css">
</head>
<body>

<header>
  <div class="header_wrapper">
    <?php if (isset($_SESSION['login_member'])): ?>
      <div>
        <p>ようこそ<?= $name_sei.$name_mei ?> 様</p>
      </div>
      <div>
        <a href="logout.php" class="button_a header_button_a">ログアウト</a>   
      </div>
    <?php else: ?>
      <div>
      </div>
      <div>
        <a href="member_regist.php" class="button_a header_button_a">新規会員登録</a>    
        <a href="login.php" class="button_a header_button_a">ログイン</a> 
      </div>
    <?php endif ?>
  </div>
</header>