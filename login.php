<?php
/* login.php - Member & admin login (Page 5)
 * Checks the members table first, then the admins table.
 * Uses password_verify() against the stored hash. */
require_once 'includes/auth.php';
require_once 'includes/db.php';
$page_title = "Login";

// Already logged in? Send them somewhere useful.
if (is_member()) { header("Location: my-bookings.php"); exit; }
if (is_admin())  { header("Location: admin.php"); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = "Please enter your email and password.";
    }

    if (empty($errors)) {
        // 1) Try to log in as a member
        $stmt = mysqli_prepare($conn,
            "SELECT member_id, full_name, password_hash FROM members WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $member = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($member && password_verify($password, $member['password_hash'])) {
            $_SESSION['member_id']   = $member['member_id'];
            $_SESSION['member_name'] = $member['full_name'];
            header("Location: my-bookings.php");
            exit;
        }

        // 2) Otherwise try to log in as an admin
        $stmt = mysqli_prepare($conn,
            "SELECT admin_id, full_name, password_hash FROM admins WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id']   = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header("Location: admin.php");
            exit;
        }

        // Neither matched
        $errors[] = "Incorrect email or password.";
    }
}

include 'includes/header.php';
?>

<section class="auth-wrap">
    <div class="auth-card">
        <h1>Log in</h1>
        <p class="auth-sub">Members and staff log in here.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo e($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php" novalidate class="form" id="loginForm">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo e($_POST['email'] ?? ''); ?>">

            <label for="password">Password</label>
            <input type="password" id="password" name="password">

            <button type="submit" class="btn btn-primary">Log in</button>
        </form>

        <p class="auth-alt">New here? <a href="register.php">Create an account</a></p>

        <div class="demo-note">
            <strong>Demo logins:</strong><br>
            Member &ndash; emma@example.com / password123<br>
            Admin &ndash; admin@quickcourt.com / admin123
        </div>
    </div>
</section>

<?php
mysqli_close($conn);
include 'includes/footer.php';
?>
