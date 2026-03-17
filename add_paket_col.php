<?php
require 'includes/database.php';
try {
    $pdo->exec("ALTER TABLE students ADD COLUMN paket INT(11) NOT NULL DEFAULT 4");
    echo "Column paket successfully added.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column paket already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
