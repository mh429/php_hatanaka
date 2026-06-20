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
  // 検索パラメータの受取
  $search_id = $_GET['search_id'] ?? '';
  $search_gender = $_GET['search_gender'] ?? '';
  $search_pref_name = $_GET['search_pref_name'] ?? '';
  $search_freeword = $_GET['search_freeword'] ?? '';

  $sql = '
  SELECT id, name_sei, name_mei, gender, pref_name, address, created_at
  FROM members
  WHERE deleted_at IS NULL
  ';

  $params = [];

  if ($search_id !== '') {
    $sql .= ' AND id = ?';
    $params[] = $search_id;
  }
  if ($search_gender !== '') {
    $sql .= ' AND gender = ?';
    $params[] = $search_gender;
  }
  if ($search_pref_name !== '') {
    $sql .= ' AND pref_name = ?';
    $params[] = $search_pref_name;
  }
  if ($search_pref_name !== '') {
    $sql .= ' AND pref_name = ?';
    $params[] = $search_pref_name;
  }
  if ($search_freeword !== '') {
    $sql .= '
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

  // URLパラメータの受取
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

  $sql .= " ORDER BY $sort $order";

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
?>

<main>
  <header>
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
              <input type="checkbox" name="search_gender" value="<?php echo $key ?>">
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
      <input type="submit" value="検索する">
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
                ID
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
                登録日時
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
                <?= htmlspecialchars($row['name_sei']).' '.htmlspecialchars($row['name_mei']) ?>
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

    </div>
  </div>
</main>

<?php require_once '../layout/footer.php' ?>