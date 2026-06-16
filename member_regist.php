<?php require_once './layout/header.php' ?>
<?php require_once './data/list.php' ?>

<?php
// 値をセッションから取得
$name_sei = $_SESSION['member_regist']['name_sei'] ?? '';
$name_mei = $_SESSION['member_regist']['name_mei'] ?? '';
$gender = $_SESSION['member_regist']['gender'] ?? '';
$pref_name = $_SESSION['member_regist']['pref_name'] ?? '';
$address = $_SESSION['member_regist']['address'] ?? '';
$email = $_SESSION['member_regist']['email'] ?? '';
$errors= $_SESSION['member_regist']['errors'] ?? '';
?>

<main>
	<h1>会員情報登録フォーム</h1>
	
	<div class="mr_container">
		<form action="member_regist_confirm.php" method="post">
			<div class="mr_contentsWrapper">
				<div class="mr_nameInputWrapper">
					<p>氏名</p>
					<div class="mr_inputs">
						<label>
							<span class="mr_nameTitle">姓</span>
							<input type="text" name="name_sei" value="<?php echo htmlspecialchars($name_sei) ?>">
						</label>
						<label>
							<span class="mr_nameTitle">名</span>
							<input type="text" name="name_mei" value="<?php echo htmlspecialchars($name_mei) ?>">
						</label>	
					</div>
				</div>
				<div class="mr-errors">
					<?php if (isset($errors['name'])): ?>
						<?php foreach ($errors['name'] as $error): ?>
							<p><?php echo $error ?></p>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>

			<div class="mr_contentsWrapper">
				<div class="mr_genderInputWrapper">
					<p>性別</p>
					<div class="mr_inputs">
						<?php foreach ($gender_list as $key => $value): ?>
							<label>
								<input type="radio" name="gender" value="<?php echo $key ?>" <?php if ($key == $gender) echo 'checked' ?> >
								<?php echo $value ?>
							</label>
						<?php endforeach ?>						
					</div>
				</div>
				<div class="mr-errors">
					<?php if (isset($errors['gender'])): ?>
						<?php foreach ($errors['gender'] as $error): ?>
							<p><?php echo $error ?></p>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>

			<div class="mr_contentsWrapper">
				<div class="mr_addressInputWrapper">
					<p>住所</p>
					<div class="mr_prefInputs">
						<label>
							<span class="mr_prefTitle">都道府県</span>
							<select name="pref_name">
								<option value="選択してください">選択してください</option>
								<?php foreach ($pref_list as $value): ?>
									<option value="<?php echo $value ?>" <?php if ($value == $pref_name) echo 'selected' ?> >
										<?php echo $value ?>
									</option>
								<?php endforeach ?>
							</select>
						</label>
						<label>
							<span class="mr_prefTitle">それ以降の住所</span>
							<input type="text" name="address" value="<?php echo htmlspecialchars($address) ?>" class="mr_addressInput">
						</label>						
					</div>
				</div>
				<div class="mr-errors">
					<?php if (isset($errors['address'])): ?>
						<?php foreach ($errors['address'] as $error): ?>
							<p><?php echo $error ?></p>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>

			<div class="mr_contentsWrapper">
				<label>
					<span class="mr_title">パスワード</span>
					<input type="password" name="password" class="mr_Input">
				</label>
				<div class="mr-errors">
					<?php if (isset($errors['password'])): ?>
						<?php foreach ($errors['password'] as $error): ?>
							<p><?php echo $error ?></p>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>

			<div class="mr_contentsWrapper">
				<label>
					<span class="mr_title">パスワード確認</span>
					<input type="password" name="password_confirm" class="mr_Input">
				</label>
				<div class="mr-errors">
					<?php if (isset($errors['password_confirm'])): ?>
						<?php foreach ($errors['password_confirm'] as $error): ?>
							<p><?php echo $error ?></p>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>

			<div class="mr_contentsWrapper">
				<label>
					<span class="mr_title">メールアドレス</span>
					<input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>" class="mr_Input">
				</label>
				<div class="mr-errors">
					<?php if (isset($errors['email'])): ?>
						<?php foreach ($errors['email'] as $error): ?>
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
      <a href="index.php" class="button_a">トップに戻る</a>    
    </div>
	</div>
</main>

<?php require_once './layout/footer.php' ?>