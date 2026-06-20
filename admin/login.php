<?php require_once '../layout/header.php' ?>

<?php
// ログイン済みならリダイレクト
if (isset($_SESSION['login_admin'])) {
	header('Location: ./index.php');
	exit;	
}
?>
<?php
// 値をセッションから取得
$login_id = $_SESSION['admin_login_input']['login_id'] ?? '';
$error_message = $_SESSION['admin_login_input']['error_message'] ?? '';
?>

<main>
  <header class="header_admin">
  </header>

	<div class="wrapper">
		<h1>管理画面</h1>
		
		<div class="mr_container">
			<form action="./login_confirm.php" method="post">
				<div class="mr_contentsWrapper">
					<label>
						<span class="login_title">ログインID</span>
						<input type="text" name="login_id" value="<?php echo htmlspecialchars($login_id) ?>" class="mr_Input" required>
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
		</div>
	</div>

	<footer class="footer_admin">
	</footer>	
</main>

<?php require_once '../layout/footer.php' ?>