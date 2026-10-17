<?php
// StudentHub | Practical 8 - PDO database connection (Intermediate level).
// PDO is a safe way to talk to the database using prepared statements.

// --- Database settings (kept in one place for easy change). ---
define('PDO_HOST', 'localhost');
define('PDO_NAME', 'studenthub_p8');
define('PDO_USER', 'root');
define('PDO_PASS', '');
define('PDO_CHARSET', 'utf8mb4');

// These two variables are used by test_connection.php
// to show a friendly message on the page.
$pdo       = null;
$pdo_error = null;

try {
    // Build the address (DSN) of the database.
    $dsn = 'mysql:host=' . PDO_HOST . ';dbname=' . PDO_NAME . ';charset=' . PDO_CHARSET;

    // Open the PDO connection.
    $pdo = new PDO($dsn, PDO_USER, PDO_PASS, [
        // Show database errors as exceptions so we can catch them safely.
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Give results as arrays with column names (easy to read).
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    // Write the real error to the server log file only.
    // This keeps private details away from the website visitor.
    error_log('PDO connection failed: ' . $e->getMessage());

    // Show the visitor a simple message with no technical details.
    $pdo       = null;
    $pdo_error = 'Database connection failed. Please try again later.';
}

// Find one student by email using a prepared statement.
// The email value is never mixed into the SQL text, so SQL injection is blocked.
function pdo_get_student_by_email(PDO $pdo, string $email): ?array
{
    $stmt = $pdo->prepare('SELECT id, full_name, email FROM students WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch();

    return $row === false ? null : $row;
}
