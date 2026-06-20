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
// 値を変数に代入
$member_id = $_POST['member_id'] ?? '';
$name_sei = trim(mb_convert_kana($_POST['name_sei'] ?? '', 's'));
$name_mei = trim(mb_convert_kana($_POST['name_mei'] ?? '', 's'));
$gender = $_POST['gender'] ?? '';
$pref_name = $_POST['pref_name'] ?? '';
$address = $_POST['address'] ?? '';
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$email = trim(mb_convert_kana($_POST['email'] ?? '', 's'));

// 値をセッションに保存
$_SESSION['member_edit_byadmin'][$member_id]=[
  'name_sei'=>$name_sei, 'name_mei'=>$name_mei, 
  'gender'=>$gender, 
  'pref_name'=>$pref_name, 'address'=>$address, 
  'password'=>$password, 
  'email'=>$email
];

// バリデーション
$errors = [];
// 名前
if ($name_sei === '') {
  $errors['name'][] = '※氏名（姓）は必須入力です';
}
if (mb_strlen($name_sei) > 20) {
  $errors['name'][] = '※氏名（姓）は20文字以内で入力してください';
}
if ($name_mei === '') {
  $errors['name'][] = '※氏名（名）は必須入力です';
}
if (mb_strlen($name_mei) > 20) {
  $errors['name'][] = '※氏名（名）は20文字以内で入力してください';
}
// 性別
if ($gender === '') {
  $errors['gender'][] = '※性別を選択してください';
} else {
  if (!array_key_exists($gender, $gender_list)) {
  $errors['gender'][] = '※性別を正しく選択してください';
}	
}
// 住所
if ($pref_name === '選択してください') {
  $errors['address'][] = '※住所（都道府県）を選択してください';
} else {
  if (!in_array($pref_name, $pref_list)) {
    $errors['address'][] = '※都道府県を正しく選択してください';
  }		
}
if (mb_strlen($address) > 100) {
  $errors['address'][] = '※住所（それ以降の住所）は100文字以内で入力してください';
}
// パスワードが入力されていたらチェック
if ($password !== '' && $password_confirm !== '') {
  // パスワード
  if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    $errors['password'][] = '※パスワードは半角英数字で入力してください';
  }
  if (mb_strlen($password) < 8 || mb_strlen($password) > 20) {
    $errors['password'][] = '※パスワードは8～20文字で入力してください';
  }
  // パスワード確認
  if (!preg_match('/^[a-zA-Z0-9]+$/', $password_confirm)) {
    $errors['password_confirm'][] = '※パスワード確認は半角英数字で入力してください';
  }
  if (mb_strlen($password_confirm) < 8 || mb_strlen($password_confirm) > 20) {
    $errors['password_confirm'][] = '※パスワード確認は8～20文字で入力してください';
  }
  if ($password !== $password_confirm) {
    $errors['password_confirm'][] = '※パスワードが一致しません';
  }
}

// メールアドレス
if ($email === '') {
  $errors['email'][] = '※メールアドレスは必須入力です';
} else {
  if (mb_strlen($email) > 200) {
    $errors['email'][] = '※メールアドレスは200文字以内で入力してください';
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'][] = '※メールアドレスの形式で入力してください';
  }
  // 重複チェック
  if (!isset($errors['email'])) {
    $sql = $pdo->prepare('SELECT COUNT(*) FROM members WHERE email = ? AND id != ?');
    $sql->execute([$email, $member_id]);
    if ($sql->fetchColumn() > 0) {
      $errors['email'][] = '※メールアドレスは既に登録されています';
    }
  }
}
// エラーがあった時
if (!empty($errors)) {
  // エラーをセッションに保存
  $_SESSION['member_edit_byadmin'][$member_id]['errors'] = $errors;
  // 登録画面に戻す
  header("Location: ./member_edit.php?id={$member_id}");
  // スクリプトを終了する
  exit;
}
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
      <table class="mr_confirmTable">
        <tbody>
          <tr>
            <th>ID</th>
            <td><?= $member_id ?></td>
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

      <form action="./member_edit_complete.php" method="post">
        <input type="hidden" name="member_id" value="<?= $member_id ?>">
        <div class="center_div">
          <input type="submit" value="編集完了">
        </div>
      </form>
    </div>    
  </div>
</main>

<?php require_once '../layout/footer.php' ?>