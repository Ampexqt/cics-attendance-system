<?php
require_once __DIR__ . '/../Database.php';

try {
    $db = Database::getInstance();

    echo "Adding 'rejected' status to users table...\n";

    // Modify the column to include 'rejected'
    $sql = "ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'rejected') DEFAULT 'pending'";
    $db->query($sql);

    echo "Successfully added 'rejected' status.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
