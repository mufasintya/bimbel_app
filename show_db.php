<?php
require 'includes/database.php';
$stmt = $pdo->query("SHOW CREATE TABLE students");
print_r($stmt->fetch(PDO::FETCH_ASSOC)['Create Table']);
echo "\n";
$stmt = $pdo->query("SHOW CREATE TABLE schedules");
print_r($stmt->fetch(PDO::FETCH_ASSOC)['Create Table']);
