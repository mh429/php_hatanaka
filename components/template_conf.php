<main>
  <header class="header_admin">
      <div>
				<?php if($url === '/admin/member_regist_confirm.php'): ?>
        	<p>会員登録</p>
				<?php elseif($url === '/admin/member_edit_confirm.php'): ?>
        	<p>会員編集</p>
				<?php endif ?>
      </div>
      <div>
        <a href="./member.php" class="button_a header_button_a">一覧へ戻る</a>   
      </div>
  </header>

  <div class="wrapper">   
    <div class="mr_container">
      <table class="mr_confirmTable">
        <tbody>
          <tr>
            <th>ID</th>
            <td><?= $member_id ?? '登録後に自動採番' ?></td>
          </tr>
          <tr>
            <th>氏名</th>
            <td><?php echo $name_sei.' '.$name_mei ?></td>
          </tr>
          <tr>
            <th>性別</th>
            <td><?php echo $gender_list[$gender] ?? '' ?></td>
          </tr>
          <tr>
            <th>住所</th>
            <td><?php echo $pref_name.' '.$address ?></td>
          </tr>
          <tr>
            <th>パスワード</th>
            <td>セキュリティのため非表示</td>
          </tr>
          <tr>
            <th>メールアドレス</th>
            <td><?php echo $email ?></td>
          </tr>
        </tbody>
      </table>

			<?php if($url === '/admin/member_regist_confirm.php'): ?>
        <form action="./member_regist_complete.php" method="post">
          <div class="center_div">
            <input type="submit" value="登録完了">
          </div>
        </form>
        <div class="center_div">
          <a href="./member_regist.php" class="button_a">前に戻る</a>    
        </div>
			<?php elseif($url === '/admin/member_edit_confirm.php'): ?>
        <form action="./member_edit_complete.php" method="post">
          <input type="hidden" name="member_id" value="<?= $member_id ?>">
          <div class="center_div">
            <input type="submit" value="編集完了">
          </div>
        </form>
        <div class="center_div">
          <a href="./member_edit.php?id=<?= $member_id ?>" class="button_a">前に戻る</a>    
        </div>
			<?php endif ?>

    </div>    
  </div>
</main>