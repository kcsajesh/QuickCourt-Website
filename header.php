<?php
/*
 * header.php
 * Opening HTML + navigation bar, included on every page.
 * Expects $page_title to be set before including (optional).
 * Also expects auth.php to have been included so is_member()/is_admin() work.
 */
if (!isset($page_title)) {
    $page_title = "QuickCourt";
}
// Make sure auth helpers are available even if a page forgot to include them
if (!function_exists('is_member')) {
    require_once __DIR__ . '/auth.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?> | QuickCourt</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="logo">Quick<span>Court</span></a>

            <nav class="main-nav">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="courts.php">Courts</a>
                <a href="contact.php">Contact</a>

                <?php if (is_member()): ?>
                    <a href="book.php">Book a Court</a>
                    <a href="my-bookings.php">My Bookings</a>
                    <a href="logout.php" class="nav-btn">Logout</a>
                <?php elseif (is_admin()): ?>
                    <a href="admin.php">Dashboard</a>
                    <a href="logout.php" class="nav-btn">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="nav-btn">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container page-main">
