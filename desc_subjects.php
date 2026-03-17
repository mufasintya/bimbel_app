<?php
require_once 'includes/database.php';

echo "=== subjects ===\n";
$stmt = $pdo->query("DESCRIBE subjects");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n=== tutor_subjects ===\n";
$stmt = $pdo->query("DESCRIBE tutor_subjects");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
