<?php
require_once 'includes/database.php';

echo "=== grades ===\n";
$stmt = $pdo->query("DESCRIBE grades");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n=== tutor_notes ===\n";
$stmt = $pdo->query("DESCRIBE tutor_notes");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
