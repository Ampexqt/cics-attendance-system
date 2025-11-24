<?php
require_once __DIR__ . '/database/Database.php';

try {
    $db = Database::getInstance();
    $student = $db->fetchOne("SELECT * FROM students LIMIT 1");
    if ($student) {
        echo "Found student: ID=" . $student['id'] . " UserID=" . $student['user_id'] . "\n";
        print_r($student);
    } else {
        echo "No students found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
