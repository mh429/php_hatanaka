<?php require_once '../layout/header.php' ?>

<?php
// 未ログインならリダイレクト
if (!isset($_SESSION['login_admin'])) {
	header('Location: ./login.php');
	exit;	
}
?>
<?php
// 値をセッションから取得
$name = $_SESSION['login_admin']['name'] ?? '';
?>

<main>
  <header class="header_admin">
      <div>
        <h1>掲示板管理画面メインメニュー</h1>
      </div>
      <div class="header_admin_right">
        <p>ようこそ<?= $name ?> さん</p>
        <div><a href="./logout.php" class="button_a header_button_a">ログアウト</a></div>
      </div>

  </header>

  <div class="center_div admin_main_div">
    <a href="./member.php" class="button_a">会員一覧</a>    
  </div>


</main>

<?php require_once '../layout/footer.php' ?>