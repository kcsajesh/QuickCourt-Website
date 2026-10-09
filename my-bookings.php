<?php
/* my-bookings.php - View / cancel own bookings (Page 7)
 * Members only. Joins bookings with courts so we can show court names. */
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_member();

$page_title = "My Bookings";
$notice = "";

// Handle a cancel request (only the owner can cancel their booking)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $cancel_id = (int)$_POST['cancel_id'];
    $member_id = $_SESSION['member_id'];
    $stmt = mysqli_prepare($conn,
        "UPDATE bookings SET status = 'Cancelled'
         WHERE booking_id = ? AND member_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $cancel_id, $member_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $notice = "Booking cancelled.";
}

// Fetch this member's bookings with the court details
$member_id = $_SESSION['member_id'];
$sql = "SELECT b.booking_id, b.booking_date, b.start_time, b.end_time, b.status,
               c.court_name, c.court_type
        FROM bookings b
        JOIN courts c ON b.court_id = c.court_id
        WHERE b.member_id = ?
        ORDER BY b.booking_date DESC, b.start_time DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $member_id);
mysqli_stmt_execute($stmt);
$bookings = mysqli_stmt_get_result($stmt);

include 'includes/header.php';
?>

<section class="page-head">
    <h1>My Bookings</h1>
    <p>Welcome back, <?php echo e($_SESSION['member_name']); ?>. Here are your bookings.</p>
</section>

<?php if ($notice): ?>
    <div class="alert alert-success"><?php echo e($notice); ?></div>
<?php endif; ?>

<div class="table-actions">
    <a href="book.php" class="btn btn-primary btn-sm">+ New booking</a>
</div>

<?php if ($bookings && mysqli_num_rows($bookings) > 0): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Court</th>
                <th>Sport</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($b = mysqli_fetch_assoc($bookings)): ?>
                <tr>
                    <td><?php echo e($b['court_name']); ?></td>
                    <td><?php echo e($b['court_type']); ?></td>
                    <td><?php echo e(date("d M Y", strtotime($b['booking_date']))); ?></td>
                    <td><?php echo e(date("g:i A", strtotime($b['start_time']))); ?>
                        &ndash; <?php echo e(date("g:i A", strtotime($b['end_time']))); ?></td>
                    <td>
                        <span class="badge badge-<?php echo strtolower($b['status']); ?>">
                            <?php echo e($b['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($b['status'] === 'Confirmed'): ?>
                            <form method="post" action="my-bookings.php"
                                  onsubmit="return confirm('Cancel this booking?');">
                                <input type="hidden" name="cancel_id" value="<?php echo (int)$b['booking_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-xs">Cancel</button>
                            </form>
                        <?php else: ?>
                            &mdash;
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="empty-state">
        <p>You have no bookings yet.</p>
        <a href="book.php" class="btn btn-primary">Book your first court</a>
    </div>
<?php endif; ?>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
include 'includes/footer.php';
?>
