<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

<?php require_once '../db/pdo.php' ?>

<?php
// 未ログインならリダイレクト
if (!isset($_SESSION['login_admin'])) {
	header('Location: ./login.php');
	exit;	
}

if (isset($_POST['delete_member_id'])) {
  $sql = $pdo->prepare(
    'UPDATE members
    SET deleted_at = NOW()
    WHERE id = ?'
  );
  $sql->execute([$_POST['delete_member_id']]);

  // 会員一覧に遷移
  header('Location: admin/member.php');
  exit;
}
?>