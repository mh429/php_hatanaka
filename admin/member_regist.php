<?php require_once '../layout/header.php' ?>
<?php require_once '../data/list.php' ?>
<?php
// 未ログインならリダイレクト
if (!isset($_SESSION['login_admin'])) {
	header('Location: ./login.php');
	exit;	
}
?>

<?php
// URLを取得
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 値をセッションから取得
$name_sei = $_SESSION['member_regist_byadmin']['name_sei'] ?? '';
$name_mei = $_SESSION['member_regist_byadmin']['name_mei'] ?? '';
$gender = $_SESSION['member_regist_byadmin']['gender'] ?? '';
$pref_name = $_SESSION['member_regist_byadmin']['pref_name'] ?? '';
$address = $_SESSION['member_regist_byadmin']['address'] ?? '';
$email = $_SESSION['member_regist_byadmin']['email'] ?? '';
$errors= $_SESSION['member_regist_byadmin']['errors'] ?? '';
?>

<?php require_once '../components/template.php' ?>

<?php require_once '../layout/footer.php' ?>