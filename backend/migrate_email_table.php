<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration - Email Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
            margin: 20px 0;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            margin: 20px 0;
        }

        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #bee5eb;
            margin: 20px 0;
        }

        button {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        button:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>📧 Email Notifications Table Migration</h1>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_migration'])) {
            require_once __DIR__ . '/database/Database.php';

            try {
                $db = Database::getInstance()->getConnection();

                // Read the migration SQL
                $sql = file_get_contents(__DIR__ . '/database/migrations/create_email_notifications_table.sql');

                // Execute the SQL
                $db->exec($sql);

                echo '<div class="success">';
                echo '<h3>✅ Migration Successful!</h3>';
                echo '<p>The <code>email_notifications</code> table has been created successfully.</p>';
                echo '<p>You can now use the parent email notification feature.</p>';
                echo '<p><strong>Next step:</strong> Go back to the Active Sessions page and try ending a session again.</p>';
                echo '</div>';
            } catch (PDOException $e) {
                echo '<div class="error">';
                echo '<h3>❌ Migration Failed</h3>';
                echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';

                // Check if table already exists
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    echo '<p>The table already exists. You can proceed with using the email notification feature.</p>';
                } else {
                    echo '<p>Please check your database connection and try again.</p>';
                }
                echo '</div>';
            }
        } else {
        ?>
            <div class="info">
                <h3>ℹ️ About This Migration</h3>
                <p>This migration will create the <code>email_notifications</code> table in your database.</p>
                <p>This table is required for the parent email notification feature to work.</p>
                <p><strong>What it does:</strong></p>
                <ul>
                    <li>Creates a table to log all email notifications sent to parents</li>
                    <li>Tracks notification status (pending, sent, failed)</li>
                    <li>Stores notification content and metadata</li>
                </ul>
            </div>

            <form method="POST">
                <button type="submit" name="run_migration">Run Migration Now</button>
            </form>

            <h3>SQL Preview:</h3>
            <pre><?php echo htmlspecialchars(file_get_contents(__DIR__ . '/database/migrations/create_email_notifications_table.sql')); ?></pre>
        <?php
        }
        ?>
    </div>
</body>

</html>