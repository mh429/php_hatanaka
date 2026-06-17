<?php require_once './layout/header.php' ?>

<?php
// 値をセッションから取得
$name_sei = $_SESSION['login_member']['name_sei'] ?? '';
$name_mei = $_SESSION['login_member']['name_mei'] ?? '';
?>

<main>
  <header>
    <?php if (isset($_SESSION['login_member'])): ?>
      <div>
        <p>ようこそ<?= $name_sei.$name_mei ?> 様</p>
      </div>
      <div>
        <a href="thread_regist.php" class="button_a header_button_a">新規スレッド作成</a>   
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
  </header>

  <h1 class="topLogo">〇〇掲示板</h1>
</main>

<?php require_once './layout/footer.php' ?>