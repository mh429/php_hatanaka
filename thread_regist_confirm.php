<?php require_once './layout/header.php' ?>

<?php
// 未ログインならリダイレクト
if (!isset($_SESSION['login_member'])) {
	header('Location: index.php');
	exit;	
}
?>

<?php
// 値を変数に代入
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

// 値をセッションに保存
$_SESSION['thread_regist']=[
  'title'=>$title, 
  'content'=>$content
];

// バリデーション
$errors = [];
// タイトル
if (trim(mb_convert_kana($title ?? '', 's')) === '') {
  $errors['title'][] = '※タイトルは必須入力です';
} else {
  if (mb_strlen($title) > 100) {
    $errors['title'][] = '※タイトルは100文字以内で入力してください';
  }
}
// コメント
if (trim(mb_convert_kana($content ?? '', 's')) === '') {
  $errors['content'][] = '※コメントは必須入力です';
} else {
  if (mb_strlen($content) > 500) {
    $errors['content'][] = '※コメントは500文字以内で入力してください';
  }
}
// エラーがあった時
if (!empty($errors)) {
  // エラーをセッションに保存
  $_SESSION['thread_regist']['errors'] = $errors;
  // 作成画面に戻す
  header('Location: thread_regist.php');
  // スクリプトを終了する
  exit;
}
?>

<main>
  <div class="wrapper">
    <h1>スレッド作成確認画面</h1>
    
    <div class="tr_container">
      <table class="tr_confirmTable">
        <tbody>
          <tr>
            <th>スレッドタイトル</th>
            <td><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></td>
          </tr>
          <tr>
            <th>コメント</th>
            <td><?php echo nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8')) ?></td>
          </tr>
        </tbody>
      </table>

      <form action="thread_regist_complete.php" method="post">
        <div class="center_div">
          <input type="submit" value="スレッドを作成する">
        </div>
      </form>
      <div class="center_div">
        <a href="thread_regist.php" class="button_a">前に戻る</a>    
      </div>
    </div>    
  </div>
</main>

<?php require_once './layout/footer.php' ?>