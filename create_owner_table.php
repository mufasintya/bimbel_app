<?php
require_once 'includes/database.php';

try {
    $pdo->beginTransaction();

    // 1. Create table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS owners (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 2. Insert owner
    $username = 'owner';
    $password = password_hash('owner123', PASSWORD_DEFAULT);
    $name = 'Ratih Tahiyatur';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM owners WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() == 0) {
        $stmtInsert = $pdo->prepare("INSERT INTO owners (username, password, name) VALUES (?, ?, ?)");
        $stmtInsert->execute([$username, $password, $name]);
    }

    // 3. Drop role column from admins if exists
    try {
        $pdo->exec("ALTER TABLE admins DROP COLUMN role");
    } catch (PDOException $e) {
        // Ignore if column doesn't exist
    }

    // 4. Delete owner from admins if exists
    $pdo->exec("DELETE FROM admins WHERE username = 'owner'");

    $pdo->commit();
    echo "Tabel owners berhasil dibuat dan data disiapkan.\n";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
?>
