<?php
require_once 'includes/database.php';

$stmt = $pdo->query('SHOW TABLES');
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $table = $row[0];
    echo "=== TABLE: $table ===\n";
    $desc = $pdo->query("DESCRIBE `$table`");
    print_r($desc->fetchAll(PDO::FETCH_ASSOC));
    echo "\n";
}
?>
