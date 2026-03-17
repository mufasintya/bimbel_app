<?php
require_once 'includes/database.php';

echo "=== student_attendance ===\n";
$stmt = $pdo->query("DESCRIBE student_attendance");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n=== tutor_attendance ===\n";
$stmt = $pdo->query("DESCRIBE tutor_attendance");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
