<?php require_once './layout/header.php' ?>
<?php require_once './db/pdo.php' ?>

<?php 
// URLパラメータの受取
$thread_id = $_REQUEST['id'] ?? 1;
$page = $_REQUEST['page'] ?? 1;

// 値をセッションから取得
$member_id = $_SESSION['login_member']['id'] ?? '';
$comment = $_SESSION['comment'][$thread_id]['comment'] ?? '';
$error_message = $_SESSION['comment'][$thread_id]['error_message'] ?? '';

/***********************************************************************
 *** スレッド
 ***********************************************************************/
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

/***********************************************************************
 *** コメント
 ***********************************************************************/
// 総コメント数の取得
$count_sql = $pdo->prepare(
  'SELECT COUNT(*)
  FROM comments
  WHERE thread_id = ?'
);
$count_sql->execute([$thread_id]);
$total_comments = $count_sql->fetchColumn();

// 1ページのコメント数
$per_page = 5;
// 総ページ数
$total_pages = ceil($total_comments / $per_page);
// 各ページで何件目から取得するか
$offset = ($page - 1) * $per_page;
// SQL作る
$comment_sql = $pdo->prepare(
  'SELECT
    comments.id, comments.comment, comments.created_at,
    members.name_sei, members.name_mei,
    COUNT(likes.id) AS like_count
  FROM comments
  JOIN members
  ON comments.member_id = members.id
  LEFT JOIN likes
  ON comments.id = likes.comment_id
  WHERE comments.thread_id = ?
  GROUP BY comments.id
  ORDER BY comments.created_at ASC
  LIMIT ?
  OFFSET ?'
);
// LIMITやOFFSETは文字列だとエラーになるので、INT型を指定して？に入れる
$comment_sql->bindValue(1, $thread_id, PDO::PARAM_INT);
$comment_sql->bindValue(2, $per_page, PDO::PARAM_INT);
$comment_sql->bindValue(3, $offset, PDO::PARAM_INT);
// SQL実行
$comment_sql->execute();
$comments = $comment_sql->fetchAll(PDO::FETCH_ASSOC);

// いいね済みコメントIDの配列を取得
$sql_isLike = $pdo->prepare(
  'SELECT comment_id
    FROM likes
    WHERE member_id = ?'
);
$sql_isLike->execute([$member_id]);
$liked_comment_ids = $sql_isLike->fetchAll(PDO::FETCH_COLUMN);

/***********************************************************************
 *** コメント投稿
 ***********************************************************************/
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

/***********************************************************************
 *** いいねトグル
 ***********************************************************************/
if (isset($_POST['like_comment_id'])) {
  // 値を変数に代入
  $like_comment_id = $_POST['like_comment_id'] ?? '';
  if (in_array($like_comment_id, $liked_comment_ids)) {
    // DB削除
    $sql_delete=$pdo->prepare('DELETE FROM likes WHERE member_id = ? AND comment_id = ?');
    $sql_delete->execute([$member_id, $like_comment_id]);
  } else {
    // DB登録
    $sql_add=$pdo->prepare('INSERT INTO likes VALUES(NULL, ?, ?)');
    $sql_add->execute([$member_id, $like_comment_id]);
  }
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
    <div class="td_title">
      <h2><?= htmlspecialchars($thread_info['title']) ?></h2>
      <div class="td_title_bottom">
        <p><?= $total_comments ?>コメント</p>
        <time datetime="<?= date('c', strtotime($thread_info['created_at'])) ?>">
          <?= date('Y/n/j G:i', strtotime($thread_info['created_at'])) ?>
        </time>
      </div>
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

    <div class="thread_contents">
      <div class="thread_contents_top">
        <p>投稿者:<?= htmlspecialchars($thread_info['name_sei']). ' '. htmlspecialchars($thread_info['name_mei']) ?></p>
        <time datetime="<?= date('c', strtotime($thread_info['created_at'])) ?>">
          <?= date('Y.n.j G:i', strtotime($thread_info['created_at'])) ?>
        </time>
      </div>
      <div class="thread_contents_content">
        <?= nl2br(htmlspecialchars($thread_info['content'])) ?>
      </div>
    </div>

    <ol start="<?= $offset + 1 ?>" class="thread_detail_ol">
      <?php $comment_number = $offset; ?>
      <?php foreach ($comments as $row): ?>
        <li>
          <?= htmlspecialchars($row['name_sei']). ' '. htmlspecialchars($row['name_mei']) ?>
          <time class="comment_time" datetime="<?= date('c', strtotime($row['created_at'])) ?>">
            <?= date('Y.n.j G:i', strtotime($row['created_at'])) ?>
          </time>
          
          <p class="thread_detail_comment"><?= nl2br(htmlspecialchars($row['comment'])) ?></p>

          <div class="like_wrapper">
            <?php if (isset($_SESSION['login_member'])): ?>
              <form action="" method="post">
                <input type="hidden" name="like_comment_id" value="<?= $row['id'] ?>">
                <button type="submit">
                  <?php if (in_array($row['id'], $liked_comment_ids)): ?>
                    <svg class="heart" viewBox="0 0 24 24" fill="red" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                  <?php else: ?>         
                    <svg class="heart" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                  <?php endif ?>
                </button>
              </form>
            <?php else: ?>
              <a href="member_regist.php" class="like-button">
                <svg class="heart" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
              </a>
            <?php endif ?>
            <p><?= $row['like_count'] ?></p>
          </div>
          <hr>
        </li>
      <?php endforeach ?>
    </ol>

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
        <div class="comment_form_wrapper">
          <textarea name="input_comment" class="comment_textarea"><?= htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') ?></textarea>
					<div class="comment_error">
						<?php if (isset($error_message)): ?>
							<p><?= $error_message ?></p>
						<?php endif ?>
					</div>          
          <div class="comment_button_wrapper">
            <input type="submit" value="コメントする" class="comment_send_button">            
          </div>
        </div>      
      </form>
    <?php endif ?>

  </div>
</main>

<?php require_once './layout/footer.php' ?>