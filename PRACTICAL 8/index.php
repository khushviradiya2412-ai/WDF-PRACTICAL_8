<?php
// StudentHub | Practical 8 - landing page.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub - Practical 8</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container nav-container">
            <a class="navbar-brand" href="index.php">StudentHub &mdash; Practical 8</a>
            <div class="nav-menu">
                <a class="nav-item active" href="index.php">Home</a>
                <a class="nav-item" href="test_connection.php">Connection Test</a>
            </div>
        </div>
    </nav>
</header>

<main>
    <section class="hero">
        <h1>MySQL Schema Design &amp; Database Connectivity</h1>
        <p>Practical 8 &mdash; students, events and registrations with MySQLi + PDO.</p>
        <a class="btn btn-primary" href="test_connection.php">Run Connection Test</a>
    </section>

    <section class="container">
        <h2 class="section-title">What This Practical Covers</h2>
        <div class="card-grid">
            <article class="card">
                <h3>Normalized Schema</h3>
                <p>Tables for users, students, events, registrations and audit logs with proper keys.</p>
            </article>
            <article class="card">
                <h3>Secure Connections</h3>
                <p>MySQLi and PDO connections with friendly errors and prepared statements.</p>
            </article>
            <article class="card">
                <h3>Stored Procedure</h3>
                <p>GetEventRegistrationCount() returns the registration count for one event.</p>
            </article>
        </div>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-col">
            <h4>StudentHub</h4>
            <p>Course ITUE203 (CHARUSAT) &mdash; Practical 8</p>
        </div>
    </div>
    <div class="footer-bottom">StudentHub &mdash; Practical 8</div>
</footer>
</body>
</html>
