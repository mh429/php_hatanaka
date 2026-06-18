<?php require_once './layout/header.php' ?>
<?php require_once './db/pdo.php' ?>

<?php 
$search_key = $_GET['search_key'] ?? '';

if (trim(mb_convert_kana($search_key, 's')) !== '') {
  $keyword = "%{$search_key}%";
  $sql = $pdo->prepare(
    'SELECT id, title, created_at FROM threads 
    WHERE title LIKE ?
    OR content LIKE ?
    ORDER BY created_at DESC'
  );
  $sql->execute([$keyword, $keyword]);  
} else {
  $sql = $pdo->query(
    'SELECT id, title, created_at 
    FROM threads 
    ORDER BY created_at DESC'
    );
}
?>

<main>
  <header class="header_thread">
    <?php if (isset($_SESSION['login_member'])): ?>
      <div>
        <a href="thread_regist.php" class="button_a header_button_a">新規スレッド作成</a>   
      </div>
    <?php endif ?>
  </header>
  
  <div class="wrapper">
    <form action="thread.php" method="get">
      <div>
        <input type="text" name="search_key" placeholder="キーワードを入力">
        <input type="submit" value="スレッド検索" />
      </div>
    </form>

    <div>
      <?php foreach ($sql as $row): ?>
        <div>
          <p>ID:<?= $row['id'] ?></p>
          <p>
            <a href="thread_detail.php?id=<?= $row['id'] ?>&page=1">
              <?= htmlspecialchars($row['title']) ?>
            </a>
          </p>
          <p><?= date('Y.n.j G:i', strtotime($row['created_at'])) ?></p>
        </div>
      <?php endforeach ?>
    </div>

    <div class="center_div">
      <a href="index.php" class="button_a">トップに戻る</a>    
    </div>
  </div>
</main>

<?php require_once './layout/footer.php' ?>