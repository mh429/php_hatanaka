<main>
  <header class="header_admin">
      <div>
				<?php if($url === '/admin/member_regist.php'): ?>
        	<h1>会員登録</h1>
				<?php elseif($url === '/admin/member_edit.php'): ?>
        	<h1>会員編集</h1>
				<?php endif ?>
      </div>
      <div>
        <a href="./member.php" class="button_a header_button_a">一覧へ戻る</a>   
      </div>
  </header>

	<div class="wrapper">
		<div class="mr_container">

			<?php if($url === '/admin/member_regist.php'): ?>
			  <form action="./member_regist_confirm.php" method="post">
			<?php elseif($url === '/admin/member_edit.php'): ?>
			  <form action="./member_edit_confirm.php" method="post">
			<?php endif ?>

				<div class="mr_contentsWrapper">
					<span class="mr_title_id">ID</span>
          <?php if($url === '/admin/member_regist.php'): ?>
            登録後に自動採番
          <?php elseif($url === '/admin/member_edit.php'): ?>
            <input type="hidden" name="member_id" value="<?= $member_id ?>">						
            <?= $member_id ?>
          <?php endif ?>
				</div>

				<div class="mr_contentsWrapper">
					<div class="mr_nameInputWrapper">
						<p>氏名</p>
						<div class="mr_inputs">
							<label>
								<span class="mr_nameTitle">姓</span>
								<input type="text" name="name_sei" value="<?php echo htmlspecialchars($name_sei) ?>" required>
							</label>
							<label>
								<span class="mr_nameTitle">名</span>
								<input type="text" name="name_mei" value="<?php echo htmlspecialchars($name_mei) ?>" required>
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
									<input type="radio" name="gender" value="<?php echo $key ?>" <?php if ($key == $gender) echo 'checked' ?> required>
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
								<select name="pref_name" required>
									<option value="">選択してください</option>
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
						<?php if($url === '/admin/member_regist.php'): ?>
							<input type="text" name="password" class="mr_Input mask" required>
						<?php elseif($url === '/admin/member_edit.php'): ?>
							<input type="text" name="password" class="mr_Input mask">
						<?php endif ?>
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
						<?php if($url === '/admin/member_regist.php'): ?>
							<input type="text" name="password_confirm" class="mr_Input mask" required>
						<?php elseif($url === '/admin/member_edit.php'): ?>
							<input type="text" name="password_confirm" class="mr_Input mask">
						<?php endif ?>
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
						<input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>" class="mr_Input" required>
					</label>
					<div class="mr-errors">
						<?php if (isset($errors['email'])): ?>
							<?php foreach ($errors['email'] as $error): ?>
								<p><?php echo $error ?></p>
							<?php endforeach ?>
						<?php endif ?>
					</div>
				</div>
				
				<div class="center_div admin_submit_button">
					<input type="submit" value="確認画面へ">
				</div>
			</form>			
			
		</div>
	</div>
</main>