<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

<?php require_once '../db/pdo.php' ?>

<?php
// 値を変数に代入
$login_id = trim(mb_convert_kana($_POST['login_id'] ?? '', 's'));
$password = $_POST['password'] ?? '';

// 値をセッションに保存
$_SESSION['admin_login_input']=[
  'login_id'=>$login_id
];

// ログイン処理
$isError = false;
if ($login_id === '') {
  $isError = true;
} else {
  if (!preg_match('/^[a-zA-Z0-9]+$/', $login_id)) {
    $isError = true;
  }
  if (mb_strlen($login_id) < 7 || mb_strlen($login_id) > 10) {
    $isError = true;
  }
}
if (trim(mb_convert_kana($password, 's')) === '') {
  $isError = true;
} else {
  if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    $isError = true;
  }
  if (mb_strlen($password) < 8 || mb_strlen($password) > 20) {
    $isError = true;
  }
}

if (!$isError) {
  $sql = $pdo->prepare('SELECT * FROM administers WHERE login_id = ? AND deleted_at IS NULL');
  $sql->execute([$login_id]);
  $row = $sql->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    // ログインIDが存在しない
    $isError = true;
  } elseif (!password_verify($password, $row['password'])) {
      // パスワード不一致
      $isError = true;
  }
}
// ログインエラー時
if ($isError) {
  // エラーをセッションに保存
  $_SESSION['admin_login_input']['error_message'] = "※IDもしくはパスワードが間違っています";
  // ログイン画面に戻す
  header('Location: ./login.php');
  exit;
}
// エラーがなければ以下を実行
// 値をセッションに保存
$_SESSION['login_admin']=[
  'id'=>$row['id'], 'name'=>$row['name']
];
// セッション削除
unset($_SESSION['admin_login_input']);
// トップ画面に遷移
header('Location: ./index.php');
exit;

?>
