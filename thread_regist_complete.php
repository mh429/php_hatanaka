<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

<?php require_once './db/pdo.php' ?>

<?php
// 未ログインならリダイレクト
if (!isset($_SESSION['login_member'])) {
	header('Location: index.php');
	exit;	
}
// セッションがない場合はリダイレクト
if (empty($_SESSION['thread_regist'])) {
  header('Location: index.php');
  exit;
}
?>

<?php
// 値をセッションから取得
$title = $_SESSION['thread_regist']['title'] ?? '';
$content = $_SESSION['thread_regist']['content'] ?? '';
$errors= $_SESSION['thread_regist']['errors'] ?? '';
$member_id = $_SESSION['login_member']['id'] ?? '';

// バリデーション
$errors = [];
// タイトル
if (trim(mb_convert_kana($title ?? '', 's')) === '') {
  $errors['title'][] = '※タイトルは必須入力です';
}
// コメント
if (trim(mb_convert_kana($content ?? '', 's')) === '') {
  $errors['content'][] = '※コメントは必須入力です';
}
// エラーがあった時
if (!empty($errors)) {
  // エラーをセッションに保存
  $_SESSION['thread_regist']['errors'] = $errors;
  // 作成画面に戻す
  header('Location: thread_regist.php');
  // スクリプトを修了する
  exit;
}

// DB登録
$replaced_content = preg_replace('/<br\s*\/?>/i', "\n", $content);

$sql_insert=$pdo->prepare('INSERT INTO threads VALUES(NULL, ?, ?, ?, NOW(),NOW(),NULL)');
$message_h1 = '';
$message_p = '';
if ($sql_insert->execute([$member_id, $title, $replaced_content])) {
  $message_h1 = 'スレッド作成完了';
  $message_p = 'スレッド作成が完了しました。';
} else {
  $message_h1 = 'スレッド作成エラー';
  $message_p = '作成に失敗しました。';
}

// セッション削除
unset($_SESSION['thread_regist']);
// トップ画面に遷移
header('Location: index.php');
exit;

?>