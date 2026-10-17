<?php
// StudentHub | Practical 8 - MySQLi database connection.
// XAMPP defaults: host = localhost, user = root, empty password.

// --- Database settings (kept in one place for easy change). ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'studenthub_p8');

// These two variables are used by test_connection.php
// to show a friendly message on the page.
$mysqli   = null;
$db_error = null;

try {
    // Open the database connection.
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // The connection failed, so stop here with an error.
    if ($mysqli->connect_error) {
        throw new Exception($mysqli->connect_error);
    }

    // Use utf8mb4 so all languages and emoji are stored correctly.
    $mysqli->set_charset('utf8mb4');
} catch (Throwable $e) {
    // Write the real error to the server log file only.
    // This keeps private details away from the website visitor.
    error_log('MySQLi connection failed: ' . $e->getMessage());

    // Show the visitor a simple message with no technical details.
    $mysqli   = null;
    $db_error = 'Database connection failed. Please try again later.';
}
