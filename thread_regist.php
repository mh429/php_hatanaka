<?php require_once './layout/header.php' ?>
<?php
// 未ログインならリダイレクト
if (!isset($_SESSION['login_member'])) {
	header('Location: index.php');
	exit;	
}
?>

<?php
// 値をセッションから取得
$title = $_SESSION['thread_regist']['title'] ?? '';
$content = $_SESSION['thread_regist']['content'] ?? '';
$errors= $_SESSION['thread_regist']['errors'] ?? '';
?>

<main>
	<h1>スレッド作成フォーム</h1>
	
	<div class="tr_container">
    <form action="thread_regist_confirm.php" method="post">
			<div class="mr_contentsWrapper">
				<label class="tr_label">
					<span class="tr_title">スレッドタイトル</span>
					<input type="text" name="title" value="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" class="tr_input" required>
				</label>
				<div class="mr-errors">
					<?php if (isset($errors['title'])): ?>
						<?php foreach ($errors['title'] as $error): ?>
							<p><?php echo $error ?></p>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>

			<div class="mr_contentsWrapper">
				<label class="tr_label">
					<span class="tr_title">コメント</span>
          <textarea name="content" class="tr_textarea" required><?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
				</label>
				<div class="mr-errors">
					<?php if (isset($errors['content'])): ?>
						<?php foreach ($errors['content'] as $error): ?>
							<p><?php echo $error ?></p>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>
			
			<div class="center_div">
				<input type="submit" value="確認画面へ">
			</div>
		</form>
		<div class="center_div">
      <a href="thread.php" class="button_a">スレッド一覧に戻る</a>    
    </div>
	</div>
</main>

<?php require_once './layout/footer.php' ?>