# Practical 8 — Test Cases

| # | Test case | Input | Expected result | Actual result |
|---|-----------|-------|-----------------|---------------|
| 1 | Import `database.sql` in phpMyAdmin | Import tab → choose `database.sql` → Go | `studenthub_p8` created with 5 tables, seed rows, and procedure `GetEventRegistrationCount` |  |
| 2 | Open the connection test page | Browser → `http://localhost/.../"PRACTICAL 8"/test_connection.php` | Page shows **MySQLi OK** and **PDO OK** with server versions and row counts (users 2, students 6, events 4, registrations 3, audit_logs 1) |  |
| 3 | Wrong DB password shows a friendly error | Temporarily set a wrong password in `db.php` / `db_pdo.php`, reload the page, then restore | Page shows "Database connection failed. Please try again later." with no raw SQL error on screen |  |
| 4 | Stored procedure call | In SQL tab run `CALL GetEventRegistrationCount(1);` | One row: event title of event 1 with registration count 2 |  |
| 5 | PDO prepared select | Call `pdo_get_student_by_email($pdo, 'aarav.patel@charusat.edu.in')` | Returns the matching student row; an email with a quote character (e.g. `' OR '1'='1`) returns no row and causes no error |  |
| 6 | Connection test screenshot (post-lab evidence) | Take a screenshot of the passing `test_connection.php` page | Screenshot shows MySQLi OK / PDO OK and the row-count table |  |
