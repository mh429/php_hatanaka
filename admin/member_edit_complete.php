<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

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
// セッションがない場合はリダイレクト
if (empty($_SESSION['member_edit_byadmin'][$member_id])) {
  header('Location: ./index.php');
  exit;
}

// 値をセッションから取得
$name_sei = $_SESSION['member_edit_byadmin'][$member_id]['name_sei'] ?? '';
$name_mei = $_SESSION['member_edit_byadmin'][$member_id]['name_mei'] ?? '';
$gender = $_SESSION['member_edit_byadmin'][$member_id]['gender'] ?? '';
$pref_name = $_SESSION['member_edit_byadmin'][$member_id]['pref_name'] ?? '';
$address = $_SESSION['member_edit_byadmin'][$member_id]['address'] ?? '';
$password = $_SESSION['member_edit_byadmin'][$member_id]['password'] ?? '';
$email = $_SESSION['member_edit_byadmin'][$member_id]['email'] ?? '';

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
if ($password !== '') {
  if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    $errors['password'][] = '※パスワードは半角英数字で入力してください';
  }
  if (mb_strlen($password) < 8 || mb_strlen($password) > 20) {
    $errors['password'][] = '※パスワードは8～20文字で入力してください';
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

// DB登録
if ($password === '') {
  $sql = $pdo->prepare(
    'UPDATE members
    SET name_sei = ?,
      name_mei = ?,
      gender = ?,
      pref_name = ?,
      address = ?,
      email = ?,
      updated_at = NOW()
    WHERE id = ?'
  );
  $sql->execute([$name_sei, $name_mei, $gender, $pref_name, $address, $email, $member_id]);
} else {
  $password_hash = password_hash($password, PASSWORD_DEFAULT);
  $sql = $pdo->prepare(
    'UPDATE members
    SET name_sei = ?,
      name_mei = ?,
      gender = ?,
      pref_name = ?,
      address = ?,
      password = ?,
      email = ?,
      updated_at = NOW()
    WHERE id = ?'
  );
  $sql->execute([$name_sei, $name_mei, $gender, $pref_name, $address, $password_hash, $email, $member_id]);
}

// セッション削除
unset($_SESSION['member_edit_byadmin'][$member_id]);
// 会員一覧に遷移
header('Location: ./member.php');
// スクリプトを終了する
exit;
?>
