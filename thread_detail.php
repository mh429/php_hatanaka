<?php require_once './layout/header.php' ?>
<?php require_once './db/pdo.php' ?>

<?php 
// URLパラメータの受取
$thread_id = $_REQUEST['id'] ?? '';

// スレッド情報の取得
$sql = $pdo->prepare(
  'SELECT threads.title, threads.content, threads.created_at, members.name_sei, members.name_mei
  FROM threads
  JOIN members
  ON threads.member_id = members.id
  WHERE threads.id = ?'
  );
$sql->execute([$thread_id]);
$thread_info = $sql->fetch(PDO::FETCH_ASSOC);

// コメントの取得
// $comment_sql = $pdo->prepare(
//   'SELECT comments.id, comments.comment, comments.created_at, members.name_sei, members.name_mei
//   FROM comments
//   JOIN members
//   ON comments.member_id = members.id
//   WHERE comments.thread_id = ?
//   ORDER BY created_at ASC'
//   );
// $comment_sql->execute([$thread_id]);
// // PDOStatement オブジェクトを配列に変換
// $comments = $comment_sql->fetchAll(PDO::FETCH_ASSOC);

// 総コメント数の取得
$count_sql = $pdo->prepare(
    'SELECT COUNT(*)
     FROM comments
     WHERE thread_id = ?'
);
$count_sql->execute([$thread_id]);
$total_comments = $count_sql->fetchColumn();

// 現在のページ番号
$page = $_GET['page'] ?? 1;
$page = max(1, (int)$page);

// 1ページのコメント数
$per_page = 5;
// 総ページ数
$total_pages = ceil($total_comments / $per_page);
// 各ページで何件目から取得するか
$offset = ($page - 1) * $per_page;
// SQL作る
$comment_sql = $pdo->prepare(
  'SELECT comments.id, comments.comment, comments.created_at, members.name_sei, members.name_mei
  FROM comments
  JOIN members
  ON comments.member_id = members.id
  WHERE comments.thread_id = ?
  ORDER BY comments.created_at ASC
  LIMIT ?
  OFFSET ?'
);
// ？に入れる
$comment_sql->bindValue(1, $thread_id, PDO::PARAM_INT);
$comment_sql->bindValue(2, $per_page, PDO::PARAM_INT);
$comment_sql->bindValue(3, $offset, PDO::PARAM_INT);
// SQL実行
$comment_sql->execute();
$comments = $comment_sql->fetchAll(PDO::FETCH_ASSOC);







// コメント投稿機能
// 値をセッションから取得
$comment = $_SESSION['comment'][$thread_id]['comment'] ?? '';
$error_message = $_SESSION['comment'][$thread_id]['error_message'] ?? '';
// POSTが来ていたら
if (isset($_POST['input_comment'])) {
  // 値を変数に代入
  $input_comment = $_POST['input_comment'] ?? '';
  // 値をセッションに保存
  $_SESSION['comment'][$thread_id]['comment'] = $input_comment;
  // バリデーション
  if (trim(mb_convert_kana($input_comment, 's')) === '') {
    $error_message = '※コメントを入力してください';
  } else if (mb_strlen($input_comment) > 500) {
    $error_message = '※コメントは500文字以内で入力してください';
  } else {
    $error_message = '';
  }
  // エラーがあった時
  if (!empty($error_message)) {
    // エラーをセッションに保存
    $_SESSION['comment'][$thread_id]['error_message'] = $error_message;
    // スレッド詳細画面に戻す
    header("Location: thread_detail.php?id={$thread_id}&page={$page}");
    // スクリプトを終了する
    exit;
  }
  // DB登録
  $replaced_comment = preg_replace('/<br\s*\/?>/i', "\n", $input_comment);
  $sql_insert=$pdo->prepare('INSERT INTO comments VALUES(NULL, ?, ?, ?, NOW(),NOW(),NULL)');
  $sql_insert->execute([$_SESSION['login_member']['id'], $thread_id, $replaced_comment]);
  // セッション削除
  unset($_SESSION['comment'][$thread_id]);
  // スレッド詳細画面をリロード
  header("Location: thread_detail.php?id={$thread_id}&page={$page}");
  // スクリプトを終了する
  exit;
}
?>

<main>
  <header class="header_thread">
    <div>
      <a href="thread.php" class="button_a header_button_a">スレッド一覧に戻る</a>   
    </div>
  </header>

  <div class="wrapper">
    <div>
      <h2><?= htmlspecialchars($thread_info['title']) ?></h2>
      <p><?= $total_comments ?>コメント</p>
      <time datetime="<?= date('c', strtotime($thread_info['created_at'])) ?>">
        <?= date('Y/n/j G:i', strtotime($thread_info['created_at'])) ?>
      </time>
    </div>

    <nav class="thread_nav">
      <div>
        <?php if ($page > 1): ?>
          <a href="?id=<?= $thread_id ?>&page=<?= $page - 1 ?>">
            <p>前へ</p>
            <p>＞</p>
          </a>
        <?php else: ?>
          <span class="disabled">
            <p>前へ</p>
            <p>＞</p>
          </span>
        <?php endif ?>
      </div>
      <div>
        <?php if ($page < $total_pages): ?>
          <a href="?id=<?= $thread_id ?>&page=<?= $page + 1 ?>">
            <p>次へ</p>
            <p>＞</p>
          </a>
        <?php else: ?>
          <span class="disabled">
            <p>次へ</p>
            <p>＞</p>
          </span>
        <?php endif ?>
      </div>
    </nav>

    <div>
      <div>
        <p>投稿者:<?= htmlspecialchars($thread_info['name_sei']). ' '. htmlspecialchars($thread_info['name_mei']) ?></p>
        <p><?= date('Y.n.j G:i', strtotime($thread_info['created_at'])) ?></p>
      </div>
      <div>
        <?= nl2br(htmlspecialchars($thread_info['content'])) ?>
      </div>
    </div>



    <div>
      <?php $comment_number = $offset; ?>
      <?php foreach ($comments as $row): ?>
        <?php $comment_number++; ?>        
        <div>
          <p><?= $comment_number ?>.</p>
          <p><?= htmlspecialchars($row['name_sei']). ' '. htmlspecialchars($row['name_mei']) ?></p>
          <p><?= date('Y.n.j G:i', strtotime($row['created_at'])) ?></p>
          <p><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
          <hr>
        </div>
      <?php endforeach ?>
    </div>

    <nav class="thread_nav">
      <div>
        <?php if ($page > 1): ?>
          <a href="?id=<?= $thread_id ?>&page=<?= $page - 1 ?>">
            <p>前へ</p>
            <p>＞</p>
          </a>
        <?php else: ?>
          <span class="disabled">
            <p>前へ</p>
            <p>＞</p>
          </span>
        <?php endif ?>
      </div>
      <div>
        <?php if ($page < $total_pages): ?>
          <a href="?id=<?= $thread_id ?>&page=<?= $page + 1 ?>">
            <p>次へ</p>
            <p>＞</p>
          </a>
        <?php else: ?>
          <span class="disabled">
            <p>次へ</p>
            <p>＞</p>
          </span>
        <?php endif ?>
      </div>
    </nav>

    <?php if (isset($_SESSION['login_member'])): ?>
      <form action="" method="post">
        <div>
          <textarea name="input_comment"><?= htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') ?></textarea>
          <input type="submit" value="コメントする">
					<div class="mr-errors">
						<?php if (isset($error_message)): ?>
							<p><?= $error_message ?></p>
						<?php endif ?>
					</div>
        </div>      
      </form>
    <?php endif ?>

  </div>
</main>

<?php require_once './layout/footer.php' ?>