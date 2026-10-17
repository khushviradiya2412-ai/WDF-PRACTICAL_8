<?php
// StudentHub | Practical 8 - connection test page.
// Checks both MySQLi (db.php) and PDO (db_pdo.php) and shows row counts.

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/db_pdo.php';

// Table names allowed for counting.
// This fixed list stops anyone from sneaking in a bad table name.
$allowed_tables = ['users', 'students', 'events', 'registrations', 'audit_logs'];
$counts         = [];
$mysqli_version = null;

// Count rows in each table using MySQLi prepared statements (no input values).
if ($mysqli !== null) {
    $mysqli_version = $mysqli->server_info;
    foreach ($allowed_tables as $table) {
        try {
            $stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM `$table`");
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $counts[$table] = (int) $row['c'];
            $stmt->close();
        } catch (Throwable $e) {
            // Write the real error to the server log file only.
            error_log('MySQLi count failed: ' . $e->getMessage());
            $counts[$table] = 'error';
        }
    }
}

// Get the PDO server version for display.
$pdo_version = null;
if ($pdo !== null) {
    try {
        $pdo_version = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    } catch (Throwable $e) {
        // Write the real error to the server log file only.
        error_log('PDO version check failed: ' . $e->getMessage());
    }
}

// Demo of the stored procedure using a PDO prepared CALL statement.
$proc_row   = null;
$proc_error = null;
if ($pdo !== null) {
    try {
        $stmt = $pdo->prepare('CALL GetEventRegistrationCount(:eid)');
        $stmt->execute([':eid' => 1]);
        $proc_row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
    } catch (Throwable $e) {
        // Write the real error to the server log file only.
        error_log('Stored procedure call failed: ' . $e->getMessage());
        $proc_error = 'Stored procedure test failed. Please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub - Practical 8 Connection Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container nav-container">
            <a class="navbar-brand" href="index.php">StudentHub &mdash; Practical 8</a>
            <div class="nav-menu">
                <a class="nav-item" href="index.php">Home</a>
                <a class="nav-item active" href="test_connection.php">Connection Test</a>
            </div>
        </div>
    </nav>
</header>

<main class="container my-4">
    <h1 class="section-title">Database Connection Test</h1>

    <section class="section-box" aria-label="MySQLi connection status">
        <h2 class="h5">MySQLi Connection (db.php)</h2>
        <?php if ($mysqli !== null): ?>
            <p><span class="badge badge-success">MySQLi OK</span></p>
            <p>Server version: <?php echo htmlspecialchars((string) $mysqli_version, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else: ?>
            <p><span class="badge badge-danger">MySQLi FAILED</span></p>
            <p><?php echo htmlspecialchars((string) $db_error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </section>

    <section class="section-box" aria-label="PDO connection status">
        <h2 class="h5">PDO Connection (db_pdo.php)</h2>
        <?php if ($pdo !== null): ?>
            <p><span class="badge badge-success">PDO OK</span></p>
            <p>Server version: <?php echo htmlspecialchars((string) $pdo_version, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else: ?>
            <p><span class="badge badge-danger">PDO FAILED</span></p>
            <p><?php echo htmlspecialchars((string) $pdo_error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </section>

    <section class="section-box" aria-label="Table row counts">
        <h2 class="h5">Table Row Counts</h2>
        <?php if ($mysqli !== null): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr><th>Table</th><th>Rows</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($counts as $table => $count): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string) $table, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars((string) $count, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Row counts are unavailable because the MySQLi connection failed.</p>
        <?php endif; ?>
    </section>

    <section class="section-box" aria-label="Stored procedure test">
        <h2 class="h5">Stored Procedure Test: GetEventRegistrationCount(1)</h2>
        <?php if ($proc_row !== null): ?>
            <p>
                Event: <?php echo htmlspecialchars((string) $proc_row['event_title'], ENT_QUOTES, 'UTF-8'); ?>
                &mdash; Registrations: <?php echo htmlspecialchars((string) $proc_row['registration_count'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php elseif ($proc_error !== null): ?>
            <p><?php echo htmlspecialchars($proc_error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else: ?>
            <p>Stored procedure test was skipped (no PDO connection).</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-col">
            <h4>StudentHub</h4>
            <p>Course ITUE203 (CHARUSAT) &mdash; Practical 8</p>
        </div>
    </div>
    <div class="footer-bottom">StudentHub &mdash; Practical 8 Connection Test</div>
</footer>
</body>
</html>
