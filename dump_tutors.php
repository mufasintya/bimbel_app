<?php
require 'includes/database.php';
$ts = $pdo->query('SELECT * FROM tutors')->fetchAll(PDO::FETCH_ASSOC);
print_r($ts);
$ts_sub = $pdo->query('SELECT * FROM tutor_subjects')->fetchAll(PDO::FETCH_ASSOC);
print_r($ts_sub);
