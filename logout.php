<?php
/* logout.php - Ends the session for members and admins. */
require_once 'includes/auth.php';

// Clear all session data and destroy the session
$_SESSION = [];
session_destroy();

header("Location: index.php");
exit;
?>
