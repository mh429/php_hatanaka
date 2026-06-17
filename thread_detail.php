<?php require_once './layout/header.php' ?>
<?php require_once './db/pdo.php' ?>

<?php 
// URLパラメータの受取
$thread_id = $_REQUEST['id'] ?? '';

$sql = $pdo->prepare(
  'SELECT threads.title, threads.content, threads.created_at, members.name_sei, members.name_mei
  FROM threads
  JOIN members
  ON threads.member_id = members.id
  WHERE threads.id = ?
  ');
$sql->execute([$thread_id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);
?>

<main>
  <header class="thread">
    <div>
      <a href="thread.php" class="button_a header_button_a">スレッド一覧に戻る</a>   
    </div>
  </header>
  <div class="header_dummy"></div>

  <div>
    <h2><?= htmlspecialchars($row['title']) ?></h2>
    <time datetime="<?= date('c', strtotime($row['created_at'])) ?>">
      <?= date('Y/n/j G:i', strtotime($row['created_at'])) ?>
    </time>
  </div>

  <nav class="thread_nav>"></nav>

  <div>
    <div>
      <p>投稿者:<?= htmlspecialchars($row['name_sei']). ' '. htmlspecialchars($row['name_mei']) ?></p>
      <p><?= date('Y.n.j G:i', strtotime($row['created_at'])) ?></p>
    </div>
    <div>
      <?= nl2br(htmlspecialchars($row['content'])) ?>
    </div>
  </div>

  <nav class="thread_nav>"></nav>

  <?php if (isset($_SESSION['login_member'])): ?>
    <form action="" method="post">
      <div>
        <textarea name="comment"></textarea>
        <input type="submit" value="コメントする">
      </div>      
    </form>
  <?php endif ?>

</main>

<?php require_once './layout/footer.php' ?>