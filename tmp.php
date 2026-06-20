<?php require_once './db/pdo.php' ?>
<?php

$sql = $pdo->query('SELECT id, password FROM administers');

foreach ($sql as $row) {
    $hash = password_hash($row['password'], PASSWORD_DEFAULT);

    $update = $pdo->prepare(
        'UPDATE administers SET password = ? WHERE id = ?'
    );

    $update->execute([$hash, $row['id']]);
}

echo '完了';