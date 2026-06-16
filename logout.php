<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>

<?php require_once './db/pdo.php' ?>

<?php
// セッション削除
unset($_SESSION['login_member']);
// トップ画面に遷移
header('Location: index.php');
exit;

?>