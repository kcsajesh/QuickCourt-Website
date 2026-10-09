<?php
/* register.php - Member sign-up (Page 4)
 * Inserts a new row into the members table with a hashed password. */
require_once 'includes/auth.php';
require_once 'includes/db.php';
$page_title = "Register";

// Already logged in? No need to register.
if (is_member()) { header("Location: my-bookings.php"); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm'] ?? '';

    // --- Server-side validation ---
    if ($full_name === '') { $errors[] = "Please enter your full name."; }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check the email isn't already registered (prepared statement)
    if (empty($errors)) {
        $check = mysqli_prepare($conn, "SELECT member_id FROM members WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if (mysqli_stmt_num_rows($check) > 0) {
            $errors[] = "That email is already registered. Please log in instead.";
        }
        mysqli_stmt_close($check);
    }

    // --- Insert the new member ---
    if (empty($errors)) {
        $hash  = password_hash($password, PASSWORD_DEFAULT);
        $today = date("Y-m-d");

        $stmt = mysqli_prepare($conn,
            "INSERT INTO members (full_name, email, password_hash, phone, date_joined)
             VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $hash, $phone, $today);

        if (mysqli_stmt_execute($stmt)) {
            // Log the new member straight in
            $_SESSION['member_id']   = mysqli_insert_id($conn);
            $_SESSION['member_name'] = $full_name;
            mysqli_stmt_close($stmt);
            header("Location: my-bookings.php");
            exit;
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}

include 'includes/header.php';
?>

<section class="auth-wrap">
    <div class="auth-card">
        <h1>Create your account</h1>
        <p class="auth-sub">Join QuickCourt to start booking courts.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo e($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="register.php" novalidate class="form" id="registerForm">
            <label for="full_name">Full name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo e($_POST['full_name'] ?? ''); ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo e($_POST['email'] ?? ''); ?>">

            <label for="phone">Phone (optional)</label>
            <input type="text" id="phone" name="phone" value="<?php echo e($_POST['phone'] ?? ''); ?>">

            <label for="password">Password</label>
            <input type="password" id="password" name="password">

            <label for="confirm">Confirm password</label>
            <input type="password" id="confirm" name="confirm">

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p class="auth-alt">Already have an account? <a href="login.php">Log in</a></p>
    </div>
</section>

<?php
mysqli_close($conn);
include 'includes/footer.php';
?>
