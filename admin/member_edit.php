<?php require_once '../layout/header.php' ?>
<?php require_once '../data/list.php' ?>
<?php require_once '../db/pdo.php' ?>
<?php
// 未ログインならリダイレクト
if (!isset($_SESSION['login_admin'])) {
	header('Location: ./login.php');
	exit;	
}
?>

<?php
// URLパラメータの受取
$member_id = $_REQUEST['id'] ?? '';

// 会員情報の取得
$sql = $pdo->prepare(
  'SELECT id, name_sei, name_mei, gender, pref_name, address, email
  FROM members
  WHERE id = ?'
  );
$sql->execute([$member_id]);
$member_info = $sql->fetch(PDO::FETCH_ASSOC);

// 入力中の値か元の会員情報を代入
$name_sei = $_SESSION['member_edit_byadmin'][$member_id]['name_sei'] ?? $member_info['name_sei'];
$name_mei = $_SESSION['member_edit_byadmin'][$member_id]['name_mei'] ?? $member_info['name_mei'];
$gender = $_SESSION['member_edit_byadmin'][$member_id]['gender'] ?? $member_info['gender'];
$pref_name = $_SESSION['member_edit_byadmin'][$member_id]['pref_name'] ?? $member_info['pref_name'];
$address = $_SESSION['member_edit_byadmin'][$member_id]['address'] ?? $member_info['address'];
$email = $_SESSION['member_edit_byadmin'][$member_id]['email'] ?? $member_info['email'];

// セッションからエラーを取得
$errors= $_SESSION['member_edit_byadmin'][$member_id]['errors'] ?? '';
?>

<main>
  <header class="header_admin">
      <div>
        <p>会員編集</p>
      </div>
      <div>
        <a href="./member.php" class="button_a header_button_a">一覧へ戻る</a>   
      </div>
  </header>

	<div class="wrapper">
		<div class="mr_container">
			<form action="./member_edit_confirm.php" method="post">

				<div class="mr_contentsWrapper">
					<span class="mr_title">ID</span>
					<input type="hidden" name="member_id" value="<?= $member_id ?>">						
					<p><?= $member_id ?></p>
				</div>

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
		</div>
	</div>
</main>

<?php require_once '../layout/footer.php' ?>