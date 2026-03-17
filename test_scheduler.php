<?php
require_once 'includes/database.php';
require_once 'includes/Scheduler.php';

// Prepare data: add a test student with package 8
$pdo->exec("DELETE FROM students WHERE name='Test Budi Paket 8'");

$tanggal_masuk = date('Y-m-d');
// Use subject_id 7 (which has tutors) instead of 1
$sql = "INSERT INTO students (name, tanggal_masuk, class_type, subject_id, paket, availability_days) VALUES ('Test Budi Paket 8', '$tanggal_masuk', 'Reguler', 7, 8, 'Senin,Rabu')";
$pdo->exec($sql);
$studentId = $pdo->lastInsertId();

echo "Student Test Budi inserted with ID $studentId\n";

// Generate Schedule
$result = Scheduler::generateRegular($pdo);

echo "Generate Result:\n";
print_r($result);

// Check schedules for this student
$schedules = $pdo->query("SELECT * FROM schedules WHERE student_id = $studentId ORDER BY schedule_date ASC")->fetchAll(PDO::FETCH_ASSOC);

echo "Schedules assigned for student (1st gen): " . count($schedules) . "\n";
foreach($schedules as $s) {
    echo "  - " . $s['day'] . ", " . $s['schedule_date'] . " [" . $s['time_slot'] . "]\n";
}

// Second Generate (Should not add more schedules since quota met)
echo "\n--- Second Generate ---\n";
$result2 = Scheduler::generateRegular($pdo);
echo "Generate Result 2:\n";
print_r($result2);

$schedules2 = $pdo->query("SELECT * FROM schedules WHERE student_id = $studentId ORDER BY schedule_date ASC")->fetchAll(PDO::FETCH_ASSOC);
echo "Schedules assigned for student AFTER 2nd generate: " . count($schedules2) . "\n";

// Cleanup
$pdo->exec("DELETE FROM students WHERE id = $studentId");
$pdo->exec("DELETE FROM schedules WHERE student_id = $studentId");
