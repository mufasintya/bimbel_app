<?php
require_once 'includes/database.php';

echo "Memulai migrasi tabel absensi...\n";

try {
    $pdo->beginTransaction();

    // 1. Buat tabel attendances baru
    $sqlCreate = "
    CREATE TABLE IF NOT EXISTS attendances (
        id INT AUTO_INCREMENT PRIMARY KEY,
        schedule_id INT NOT NULL,
        
        -- Kolom Penanda Role
        user_type ENUM('student', 'tutor') NOT NULL, 
        student_id INT NULL, 
        tutor_id INT NULL,   
        
        -- Kolom Bersama
        status ENUM('Hadir', 'Tidak Hadir', 'Izin', 'Alpha') NOT NULL,
        attendance_date DATE NOT NULL DEFAULT CURDATE(),
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        -- Kolom Khusus Tentor (NULL/Default untuk siswa)
        validation_status ENUM('Pending','Valid','Invalid') DEFAULT 'Pending',
        proof_file VARCHAR(255) NULL,
        honor DECIMAL(10,2) DEFAULT 0.00,
        
        FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (tutor_id) REFERENCES tutors(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sqlCreate);
    echo "Tabel 'attendances' berhasil dibuat.\n";

    // 2. Migrasi data student_attendance
    // Asumsi tabel lamanya masih ada. Jika sudah pernah drop, abaikan.
    $checkStudentTable = $pdo->query("SHOW TABLES LIKE 'student_attendance'")->rowCount();
    if ($checkStudentTable > 0) {
        $sqlMigrateStudent = "
            INSERT INTO attendances (schedule_id, user_type, student_id, status, attendance_date, timestamp)
            SELECT schedule_id, 'student', student_id, status, attendance_date, timestamp
            FROM student_attendance
        ";
        $stmtS = $pdo->exec($sqlMigrateStudent);
        echo "Berhasil memigrasi $stmtS data absensi murid.\n";
    }

    // 3. Migrasi data tutor_attendance
    $checkTutorTable = $pdo->query("SHOW TABLES LIKE 'tutor_attendance'")->rowCount();
    if ($checkTutorTable > 0) {
        $sqlMigrateTutor = "
            INSERT INTO attendances (schedule_id, user_type, tutor_id, status, attendance_date, timestamp, validation_status, proof_file, honor)
            SELECT schedule_id, 'tutor', tutor_id, status, attendance_date, timestamp, validation_status, proof_file, honor
            FROM tutor_attendance
        ";
        $stmtT = $pdo->exec($sqlMigrateTutor);
        echo "Berhasil memigrasi $stmtT data absensi tentor.\n";
    }

    // 4. Drop tabel lama
    if ($checkStudentTable > 0) {
        $pdo->exec("DROP TABLE student_attendance");
        echo "Tabel 'student_attendance' lama berhasil dihapus.\n";
    }
    if ($checkTutorTable > 0) {
        $pdo->exec("DROP TABLE tutor_attendance");
        echo "Tabel 'tutor_attendance' lama berhasil dihapus.\n";
    }

    $pdo->commit();
    echo "Migrasi Selesai!\n";

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
?>
