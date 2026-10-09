<?php
/*
 * auth.php
 * Session + access-control helpers used across the site.
 * Include this at the very top of any page that needs login info.
 */

// Start the session only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Is a member currently logged in?
function is_member() {
    return isset($_SESSION['member_id']);
}

// Is an admin currently logged in?
function is_admin() {
    return isset($_SESSION['admin_id']);
}

// Force member login: send guests to the login page
function require_member() {
    if (!is_member()) {
        header("Location: login.php");
        exit;
    }
}

// Force admin login: send non-admins to the login page
function require_admin() {
    if (!is_admin()) {
        header("Location: login.php");
        exit;
    }
}

/*
 * Escape output to prevent XSS when printing user data into HTML.
 * Use this everywhere you echo something that came from the database
 * or a form, e.g. echo e($row['full_name']);
 */
function e($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}
?>
