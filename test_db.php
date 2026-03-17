<?php
require 'includes/database.php';

echo "OWNERS:\n";
$stmt = $pdo->query('SELECT * FROM owners');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "ATTENDANCES:\n";
$stmt = $pdo->query('SELECT * FROM attendances LIMIT 1');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "NOTES:\n";
$stmt = $pdo->query('SELECT * FROM notes LIMIT 1');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
