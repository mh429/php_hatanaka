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
// URLを取得
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// URLパラメータの受取
$member_id = $_REQUEST['id'] ?? '';

// 会員情報の取得
$sql = $pdo->prepare(
  'SELECT id, name_sei, name_mei, gender, pref_name, address, email
  FROM members
  WHERE id = ?'
  );
$sql->execute([$member_id]);
$member_info = $sql->fetch(PDO::FETCH_ASSOC);

// 入力中の値か元の会員情報を代入
$name_sei = $_SESSION['member_edit_byadmin'][$member_id]['name_sei'] ?? $member_info['name_sei'];
$name_mei = $_SESSION['member_edit_byadmin'][$member_id]['name_mei'] ?? $member_info['name_mei'];
$gender = $_SESSION['member_edit_byadmin'][$member_id]['gender'] ?? $member_info['gender'];
$pref_name = $_SESSION['member_edit_byadmin'][$member_id]['pref_name'] ?? $member_info['pref_name'];
$address = $_SESSION['member_edit_byadmin'][$member_id]['address'] ?? $member_info['address'];
$email = $_SESSION['member_edit_byadmin'][$member_id]['email'] ?? $member_info['email'];

// セッションからエラーを取得
$errors= $_SESSION['member_edit_byadmin'][$member_id]['errors'] ?? '';
?>

<?php require_once '../components/template.php' ?>

<?php require_once '../layout/footer.php' ?>