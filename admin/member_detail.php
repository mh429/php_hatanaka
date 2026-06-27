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

$name_sei = $member_info['name_sei'];
$name_mei = $member_info['name_mei'];
$gender = $member_info['gender'];
$pref_name = $member_info['pref_name'];
$address = $member_info['address'];
$email = $member_info['email'];
?>

<main>
  <header class="header_admin">
      <div>
        <p>会員詳細</p>
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
    </div>    

    <div class="center_div">
      <a href="./member_edit.php?id=<?= $member_id ?>" class="button_a">編集</a>    
    </div>

    <div class="center_div">
      <form action="./member_delete.php" method="post">
        <input type="hidden" name="delete_member_id" value="<?= $member_id ?>">
        <input type="submit" value="削除" class="mw_submit">
      </form>
    </div>    

  </div>
</main>

<?php require_once '../layout/footer.php' ?>