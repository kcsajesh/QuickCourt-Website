<?php
/*
 * db.php
 * Single database connection used across the whole QuickCourt site.
 * Uses MySQLi (procedural) as chosen for the project.
 *
 * Import sql/quickcourt.sql into phpMyAdmin first, then adjust the
 * credentials below if your MySQL username/password differ.
 */

$db_host = "localhost";
$db_user = "root";      // default XAMPP username
$db_pass = "";          // default XAMPP password is empty
$db_name = "quickcourt";

// Open the connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Stop everything if the connection fails
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Use UTF-8 so names and symbols display correctly
mysqli_set_charset($conn, "utf8mb4");
?>
