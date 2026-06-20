<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

<?php require_once '../db/pdo.php' ?>

<?php
// セッション削除
unset($_SESSION['login_admin']);
unset($_SESSION['member_regist_byadmin']);
unset($_SESSION['member_edit_byadmin']);
// ログイン画面に遷移
header('Location: ./login.php');
exit;

?>