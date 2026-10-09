<?php
/* admin.php - Admin Dashboard (Page 8)
 * Admins only. Lets staff add/remove courts, see every booking,
 * and view/remove members. Demonstrates INSERT, DELETE and SELECT
 * across all tables from one controlled page. */
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_admin();

$page_title = "Admin Dashboard";
$notice = "";
$errors = [];

// ---- Handle actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Add a new court
    if ($action === 'add_court') {
        $name = trim($_POST['court_name'] ?? '');
        $type = trim($_POST['court_type'] ?? '');
        $cap  = (int)($_POST['capacity'] ?? 0);
        $rate = (float)($_POST['hourly_rate'] ?? 0);

        if ($name === '' || $type === '' || $cap <= 0 || $rate < 0) {
            $errors[] = "Please fill in all court fields with valid values.";
        } else {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO courts (court_name, court_type, capacity, hourly_rate)
                 VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssid", $name, $type, $cap, $rate);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $notice = "Court added.";
        }
    }

    // Delete a court (its bookings cascade-delete via the foreign key)
    if ($action === 'delete_court') {
        $id = (int)$_POST['court_id'];
        $stmt = mysqli_prepare($conn, "DELETE FROM courts WHERE court_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $notice = "Court removed.";
    }

    // Delete a member (their bookings cascade-delete via the foreign key)
    if ($action === 'delete_member') {
        $id = (int)$_POST['member_id'];
        $stmt = mysqli_prepare($conn, "DELETE FROM members WHERE member_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $notice = "Member removed.";
    }
}

// ---- Load data for the dashboard ----
$courts   = mysqli_query($conn, "SELECT * FROM courts ORDER BY court_name");
$members  = mysqli_query($conn, "SELECT * FROM members ORDER BY date_joined DESC");
$bookings = mysqli_query($conn,
    "SELECT b.booking_id, b.booking_date, b.start_time, b.end_time, b.status,
            c.court_name, m.full_name
     FROM bookings b
     JOIN courts c  ON b.court_id  = c.court_id
     JOIN members m ON b.member_id = m.member_id
     ORDER BY b.booking_date DESC, b.start_time DESC");

// Quick counts for the summary tiles
$countCourts   = mysqli_num_rows($courts);
$countMembers  = mysqli_num_rows($members);
$countBookings = mysqli_num_rows($bookings);
mysqli_data_seek($courts, 0);
mysqli_data_seek($members, 0);

include 'includes/header.php';
?>

<section class="page-head">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo e($_SESSION['admin_name']); ?>. Manage courts, bookings and members here.</p>
</section>

<?php if ($notice): ?>
    <div class="alert alert-success"><?php echo e($notice); ?></div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul><?php foreach ($errors as $err): ?><li><?php echo e($err); ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<section class="stats-row">
    <div class="stat"><span class="stat-num"><?php echo $countCourts; ?></span><span class="stat-label">Courts</span></div>
    <div class="stat"><span class="stat-num"><?php echo $countMembers; ?></span><span class="stat-label">Members</span></div>
    <div class="stat"><span class="stat-num"><?php echo $countBookings; ?></span><span class="stat-label">Bookings</span></div>
</section>

<!-- ===== Manage courts ===== -->
<section class="admin-section">
    <h2>Courts</h2>
    <table class="data-table">
        <thead><tr><th>Name</th><th>Sport</th><th>Capacity</th><th>Rate/hr</th><th></th></tr></thead>
        <tbody>
            <?php while ($c = mysqli_fetch_assoc($courts)): ?>
                <tr>
                    <td><?php echo e($c['court_name']); ?></td>
                    <td><?php echo e($c['court_type']); ?></td>
                    <td><?php echo e($c['capacity']); ?></td>
                    <td>$<?php echo e(number_format($c['hourly_rate'], 2)); ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Delete this court and its bookings?');">
                            <input type="hidden" name="action" value="delete_court">
                            <input type="hidden" name="court_id" value="<?php echo (int)$c['court_id']; ?>">
                            <button class="btn btn-danger btn-xs">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Add a court</h3>
    <form method="post" class="form inline-form">
        <input type="hidden" name="action" value="add_court">
        <input type="text" name="court_name" placeholder="Court name (e.g. Court 5)">
        <input type="text" name="court_type" placeholder="Sport (e.g. Tennis)">
        <input type="number" name="capacity" placeholder="Capacity" min="1">
        <input type="number" name="hourly_rate" placeholder="Rate" min="0" step="0.01">
        <button class="btn btn-primary btn-sm">Add court</button>
    </form>
</section>

<!-- ===== All bookings ===== -->
<section class="admin-section">
    <h2>All Bookings</h2>
    <?php if ($countBookings > 0): ?>
        <table class="data-table">
            <thead><tr><th>Member</th><th>Court</th><th>Date</th><th>Time</th><th>Status</th></tr></thead>
            <tbody>
                <?php while ($b = mysqli_fetch_assoc($bookings)): ?>
                    <tr>
                        <td><?php echo e($b['full_name']); ?></td>
                        <td><?php echo e($b['court_name']); ?></td>
                        <td><?php echo e(date("d M Y", strtotime($b['booking_date']))); ?></td>
                        <td><?php echo e(date("g:i A", strtotime($b['start_time']))); ?>
                            &ndash; <?php echo e(date("g:i A", strtotime($b['end_time']))); ?></td>
                        <td><span class="badge badge-<?php echo strtolower($b['status']); ?>"><?php echo e($b['status']); ?></span></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bookings yet.</p>
    <?php endif; ?>
</section>

<!-- ===== Manage members ===== -->
<section class="admin-section">
    <h2>Members</h2>
    <table class="data-table">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th><th></th></tr></thead>
        <tbody>
            <?php while ($m = mysqli_fetch_assoc($members)): ?>
                <tr>
                    <td><?php echo e($m['full_name']); ?></td>
                    <td><?php echo e($m['email']); ?></td>
                    <td><?php echo e($m['phone']); ?></td>
                    <td><?php echo e(date("d M Y", strtotime($m['date_joined']))); ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Remove this member and their bookings?');">
                            <input type="hidden" name="action" value="delete_member">
                            <input type="hidden" name="member_id" value="<?php echo (int)$m['member_id']; ?>">
                            <button class="btn btn-danger btn-xs">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<?php
mysqli_close($conn);
include 'includes/footer.php';
?>
