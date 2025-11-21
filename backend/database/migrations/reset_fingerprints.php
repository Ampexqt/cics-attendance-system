<?php
require_once __DIR__ . '/../Database.php';

try {
    $db = Database::getInstance();

    echo "Resetting device fingerprints for all users...\n";
    echo "Users will need to re-register or login again to generate new fingerprints.\n\n";

    // Clear all device fingerprints
    $sql = "UPDATE users SET device_fingerprint = NULL WHERE role = 'student'";
    $db->query($sql);

    echo "Successfully reset fingerprints for all students.\n";
    echo "Students can now login from any browser on their registered device.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
