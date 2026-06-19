<?php require_once './layout/header.php' ?>
<?php require_once './db/pdo.php' ?>

<?php 
// 未ログインならリダイレクト
if (!isset($_SESSION['login_member'])) {
	header('Location: index.php');
	exit;	
}

// セッションから会員IDを取得
$member_id = $_SESSION['login_member']['id'] ?? '';

// 「退会する」がクリックされた時
if (isset($_POST['delete_member_id'])) {
  $sql = $pdo->prepare(
    'UPDATE members
    SET deleted_at = NOW()
    WHERE id = ?'
  );
  $sql->execute([$member_id]);

  // セッション削除
  unset($_SESSION['login_member']);
  unset($_SESSION['member_regist']);
  unset($_SESSION['thread_regist']);
  unset($_SESSION['comment']);
  // トップ画面に遷移
  header('Location: index.php');
  exit;
}
?>

<main>
  <header class="header_thread">
    <div>
      <a href="index.php" class="button_a header_button_a">トップに戻る</a>   
    </div>
  </header>
  
  <div class="wrapper">
    <h1>退会</h1>
    <form action="" method="post">
      <div class="center_div">
        <p class="mw_p">退会しますか？</p>
        <input type="hidden" name="delete_member_id">
        <input type="submit" value="退会する" class="mw_submit">
      </div>
    </form>
  </div>
</main>

<?php require_once './layout/footer.php' ?>