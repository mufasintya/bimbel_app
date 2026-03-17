<?php
require_once 'includes/database.php';

echo "Memulai migrasi tabel catatan...\n";

try {
    $pdo->beginTransaction();

    // 1. Buat tabel notes baru
    $sqlCreate = "
    CREATE TABLE IF NOT EXISTS notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        schedule_id INT NOT NULL UNIQUE,
        tutor_id INT NOT NULL,
        student_id INT NULL,
        grade VARCHAR(10) NULL,
        note TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE,
        FOREIGN KEY (tutor_id) REFERENCES tutors(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sqlCreate);
    echo "Tabel 'notes' berhasil dibuat.\n";

    // 2. Migrasi dari tutor_notes terlebih dahulu
    $checkTutorNotes = $pdo->query("SHOW TABLES LIKE 'tutor_notes'")->rowCount();
    $migratedFromTutorNotes = 0;
    if ($checkTutorNotes > 0) {
        // Ambil student_id dari schedules karena tutor_notes tidak punya student_id
        $sqlMigrateTutorNotes = "
            INSERT IGNORE INTO notes (schedule_id, tutor_id, student_id, note, created_at, updated_at)
            SELECT 
                tn.schedule_id, 
                tn.tutor_id, 
                s.student_id, 
                tn.note, 
                tn.created_at, 
                tn.updated_at
            FROM tutor_notes tn
            JOIN schedules s ON tn.schedule_id = s.id
        ";
        $migratedFromTutorNotes = $pdo->exec($sqlMigrateTutorNotes);
        echo "Berhasil memigrasi $migratedFromTutorNotes catatan dari tutor_notes.\n";
    }

    // 3. Migrasi & Merge dari grades
    $checkGrades = $pdo->query("SHOW TABLES LIKE 'grades'")->rowCount();
    $migratedFromGradesInsert = 0;
    $migratedFromGradesUpdate = 0;
    
    if ($checkGrades > 0) {
        $stmtGrades = $pdo->query("SELECT * FROM grades");
        $grades = $stmtGrades->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($grades as $g) {
            // Coba lihat apakah schedule_id ini sudah ada di tabel notes
            $stmtCheck = $pdo->prepare("SELECT id, note FROM notes WHERE schedule_id = ?");
            $stmtCheck->execute([$g['schedule_id']]);
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // UPDATE: Gabungkan note jika ada note baru di grades, dan set grade
                $newNote = $existing['note'];
                if (!empty($g['notes'])) {
                    if (empty($newNote)) {
                        $newNote = $g['notes'];
                    } else if (strpos($newNote, $g['notes']) === false) { // Tambahkan kalau tidak sama persis / duplikat
                        $newNote .= "\n\n-- Tambahan dari Nilai/Penilaian --\n" . $g['notes'];
                    }
                }
                
                $stmtUpdate = $pdo->prepare("
                    UPDATE notes 
                    SET grade = ?, note = ?
                    WHERE id = ?
                ");
                $stmtUpdate->execute([$g['grade'], $newNote, $existing['id']]);
                $migratedFromGradesUpdate++;
            } else {
                // INSERT baru
                $stmtInsert = $pdo->prepare("
                    INSERT INTO notes (schedule_id, tutor_id, student_id, grade, note, created_at)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmtInsert->execute([
                    $g['schedule_id'],
                    $g['tutor_id'],
                    $g['student_id'],
                    $g['grade'],
                    $g['notes'],
                    $g['created_at']
                ]);
                $migratedFromGradesInsert++;
            }
        }
        echo "Berhasil memigrasi dari tabel grades: $migratedFromGradesInsert sisipan baru, $migratedFromGradesUpdate diupdate.\n";
    }

    // 4. Drop tabel lama
    if ($checkTutorNotes > 0) {
        $pdo->exec("DROP TABLE tutor_notes");
        echo "Tabel 'tutor_notes' lama berhasil dihapus.\n";
    }
    if ($checkGrades > 0) {
        $pdo->exec("DROP TABLE grades");
        echo "Tabel 'grades' lama berhasil dihapus.\n";
    }

    $pdo->commit();
    echo "Migrasi Catatan Selesai!\n";

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
?>
