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
/***********************************************************************
 *** パラメータ取得
 ***********************************************************************/
// ページ
$page = $_REQUEST['page'] ?? 1;

// 検索フォーム
$search_id = $_GET['search_id'] ?? '';
$search_gender = $_GET['search_gender'] ?? [];
$search_pref_name = $_GET['search_pref_name'] ?? '';
$search_freeword = $_GET['search_freeword'] ?? '';

// ソート順
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'desc';
// リストで変換（SQLインジェクションの危険があるので、許可する値を限定）
$sort_list = [
    'id' => 'id',
    'created_at' => 'created_at'
];
$order_list = [
    'asc' => 'ASC',
    'desc' => 'DESC'
];
$sort = $sort_list[$sort] ?? 'id';
$order = $order_list[$order] ?? 'DESC';

/***********************************************************************
 *** 共通SQL（WHERE部分）
 ***********************************************************************/
$where = ' WHERE deleted_at IS NULL ';
$params = [];
if ($search_id !== '') {
  $where .= ' AND id = ?';
  $params[] = $search_id;
}
if (count($search_gender) === 1) {
  $where .= ' AND gender = ?';
  $params[] = $search_gender[0];
}
if ($search_pref_name !== '') {
  $where .= ' AND pref_name = ?';
  $params[] = $search_pref_name;
}
if ($search_freeword !== '') {
  $where .= '
    AND (
      name_sei LIKE ?
      OR name_mei LIKE ?
      OR email LIKE ?
    )
  ';
  $keyword = "%{$search_freeword}%";
  $params[] = $keyword;
  $params[] = $keyword;
  $params[] = $keyword;
}

/***********************************************************************
 *** 総件数取得
 ***********************************************************************/
$count_sql = 'SELECT COUNT(*) FROM members' . $where;
// ?が沢山入ったSQL
// stmtはステートメント（命令）の略
$stmt_count = $pdo->prepare($count_sql);
// paramsの配列を入れて実行
$stmt_count->execute($params);
// 総数を取得
$total_info = $stmt_count->fetchColumn();

// 1ページの表示数
$per_page = 10;
// 総ページ数
$total_pages = ceil($total_info / $per_page);
// 各ページで何件目から取得するか
$offset = ($page - 1) * $per_page;

/***********************************************************************
 *** 一覧取得
 ***********************************************************************/
$sql = '
SELECT id, name_sei, name_mei, gender, pref_name, address, created_at
FROM members
' . $where;

$sql .= " ORDER BY $sort $order";
$sql .= " LIMIT $per_page OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

/***********************************************************************
 *** ページャー用
 ***********************************************************************/
$query = [
  'search_id' => $search_id,
  'search_gender' => $search_gender,
  'search_pref_name' => $search_pref_name,
  'search_freeword' => $search_freeword,
  'sort' => $_GET['sort'] ?? 'id',
  'order' => $_GET['order'] ?? 'desc'
];

$start_page = max(1, $page - 1);
$end_page = min($total_pages, $page + 1);
// 1ページ目付近なら 1,2,3
if ($page <= 2) {
  $start_page = 1;
  $end_page = min(3, $total_pages);
}
// 最終ページ付近なら 7,8,9
if ($page >= $total_pages - 1) {
  $start_page = max(1, $total_pages - 2);
  $end_page = $total_pages;
}



?>

<main>
  <header class="header_admin">
      <div>
        <p>会員一覧</p>
      </div>
      <div>
        <a href="./index.php" class="button_a header_button_a">トップへ戻る</a>   
      </div>
  </header>
  
  <div class="wrapper">
    <div class="tr_container">

    <div class="center_div">
      <a href="./member_regist.php" class="button_a button_a_blue">会員登録</a>    
    </div>

    <form action="" method="get">
      <div>
        <p>ID</p>
        <div><input type="text" name="search_id"></div>
        <p>性別</p>
        <div>
          <?php foreach ($gender_list as $key => $value): ?>
            <label>
              <input type="checkbox" name="search_gender[]" value="<?php echo $key ?>">
              <?php echo $value ?>
            </label>
          <?php endforeach ?>			
        </div>
        <p>都道府県</p>
        <div>
          <select name="search_pref_name">
            <option value=""></option>
            <?php foreach ($pref_list as $value): ?>
              <option value="<?php echo $value ?>">
                <?php echo $value ?>
              </option>
            <?php endforeach ?>
          </select>          
        </div>
        <p>フリーワード</p>
        <div>
          <input type="text" name="search_freeword">
        </div>
      </div>
      <input type="submit" value="検索する" class="admin_search_button">
    </form>

    <div>
      <table>
        <thead>
          <tr>
            <th>
              <a href="?<?= http_build_query([
                'search_id' => $search_id,
                'search_gender' => $search_gender,
                'search_pref_name' => $search_pref_name,
                'search_freeword' => $search_freeword,
                'sort' => 'id',
                'order' => $sort === 'id' && $order === 'DESC' ? 'asc' : 'desc'
              ]) ?>">
                ID▼
              </a>
            </th>
            <th>氏名</th>
            <th>性別</th>
            <th>住所</th>
            <th>
              <a href="?<?= http_build_query([
                'search_id' => $search_id,
                'search_gender' => $search_gender,
                'search_pref_name' => $search_pref_name,
                'search_freeword' => $search_freeword,
                'sort' => 'created_at',
                'order' => $sort === 'created_at' && $order === 'ASC' ? 'desc' : 'asc'
              ]) ?>">
                登録日時▼
              </a>
            </th>
            <th>編集</th>
            <th>詳細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stmt as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><a href="./member_detail.php?id=<?= $row['id'] ?>">
                <?= htmlspecialchars($row['name_sei'].'　'.$row['name_mei']) ?>
              </a></td>
              <td><?= $gender_list[$row['gender']] ?? '' ?></td>
              <td><?= $row['pref_name'].$row['address'] ?></td>
              <td><?= date('Y/n/j', strtotime($row['created_at'])) ?></td>
              <td>
                <a href="./member_edit.php?id=<?= $row['id'] ?>">編集</a>
              </td>
              <td>
                <a href="./member_detail.php?id=<?= $row['id'] ?>">詳細</a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <nav class="member_nav">
      <?php if ($page > 1): ?>
        <a href="?<?= http_build_query(array_merge($query, ['page' => $page - 1])) ?>">
          <p class="prev_next">前へ&gt;</p>
        </a>
      <?php else: ?>
        <p class="dummy"></p>
      <?php endif ?>

      <div class="pages">
        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
          <?php if ($i == $page): ?>
            <p class="page selected_page"><?= $i ?></p>
          <?php else: ?>
            <a href="?<?= http_build_query(array_merge($query, ['page' => $i])) ?>">
              <p class="page"><?= $i ?></p>
            </a>
          <?php endif ?>
        <?php endfor ?>
      </div>

      <?php if ($page < $total_pages): ?>
        <a href="?<?= http_build_query(array_merge($query, ['page' => $page + 1])) ?>">
          <p class="prev_next">次へ&gt;</p>
        </a>
      <?php else: ?>
        <p class="dummy"></p>
      <?php endif ?>
    </nav>


    </div>
  </div>
</main>

<?php require_once '../layout/footer.php' ?>