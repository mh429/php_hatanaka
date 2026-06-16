<?php require_once './layout/header.php' ?>

<?php
// ログイン済みならリダイレクト
if (isset($_SESSION['login_member'])) {
	header('Location: index.php');
	exit;	
}
?>
<?php
// 値をセッションから取得
$email = $_SESSION['login_input']['email'] ?? '';
$error_message = $_SESSION['login_input']['error_message'] ?? '';
?>

<main>
	<h1>ログイン</h1>
	
	<div class="mr_container">
		<form action="login_confirm.php" method="post">
			<div class="mr_contentsWrapper">
				<label>
					<span class="login_title">メールアドレス（ID）</span>
					<input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>" class="mr_Input" required>
				</label>
			</div>

			<div class="mr_contentsWrapper">
				<label>
					<span class="login_title">パスワード</span>
					<input type="password" name="password" class="mr_Input" required>
				</label>

			</div>

      <div class="login-errors">
        <p><?= $error_message ?? '' ?></p>
      </div>
			
			<div class="center_div">
				<input type="submit" value="ログイン">
			</div>
		</form>
    <div class="center_div">
      <a href="index.php" class="button_a">トップに戻る</a>    
    </div>
	</div>

</main>

<?php require_once './layout/footer.php' ?>