<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

<?php require_once './db/pdo.php' ?>

<?php
// 値を変数に代入
$email = trim(mb_convert_kana($_POST['email'] ?? '', 's'));
$password = $_POST['password'] ?? '';

// 値をセッションに保存
$_SESSION['login_input']=[
  'email'=>$email
];

// ログイン処理
$isError = false;
if ($email === '') {
  $isError = true;
}
if (trim(mb_convert_kana($password, 's')) === '') {
  $isError = true;
}
if (!$isError) {
  $sql = $pdo->prepare('SELECT * FROM members WHERE email = ? AND deleted_at IS NULL');
  $sql->execute([$email]);
  $row = $sql->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    // メールアドレスが存在しない
    $isError = true;
  } elseif (!password_verify($password, $row['password'])) {
      // パスワード不一致
      $isError = true;
  }
}
// ログインエラー時
if ($isError) {
  // エラーをセッションに保存
  $_SESSION['login_input']['error_message'] = "※IDもしくはパスワードが間違っています";
  // ログイン画面に戻す
  header('Location: login.php');
  exit;
}
// エラーがなければ以下を実行
// 値をセッションに保存
$_SESSION['login_member']=[
  'id'=>$row['id'], 'name_sei'=>$row['name_sei'], 'name_mei'=>$row['name_mei'], 
];
// セッション削除
unset($_SESSION['login_input']);
// トップ画面に遷移
header('Location: index.php');
exit;

?>
