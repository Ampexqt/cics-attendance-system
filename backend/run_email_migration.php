<?php

/**
 * Run Email Notifications Table Migration
 * Execute this file once to create the email_notifications table
 */

require_once __DIR__ . '/../database/Database.php';

try {
    $db = Database::getInstance()->getConnection();

    echo "Creating email_notifications table...\n";

    $sql = file_get_contents(__DIR__ . '/../database/migrations/create_email_notifications_table.sql');

    // Execute the SQL
    $db->exec($sql);

    echo "✅ SUCCESS: email_notifications table created successfully!\n";
    echo "You can now use the parent email notification feature.\n";
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
