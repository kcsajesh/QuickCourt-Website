<?php
/* book.php - Book a Court (Page 6)
 * Members only. Inserts into bookings after checking for a clash,
 * which is the core "no double-bookings" feature. */
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_member();  // guests are redirected to login

$page_title = "Book a Court";

$errors  = [];
$success = "";

// Pre-select a court if the user came from the courts page
$preselect = isset($_GET['court_id']) ? (int)$_GET['court_id'] : 0;

// Load courts for the drop-down
$courts = mysqli_query($conn, "SELECT court_id, court_name, court_type, hourly_rate
                               FROM courts ORDER BY court_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $court_id = (int)($_POST['court_id'] ?? 0);
    $date     = trim($_POST['booking_date'] ?? '');
    $start    = trim($_POST['start_time'] ?? '');
    $end      = trim($_POST['end_time'] ?? '');

    // --- Validation ---
    if ($court_id <= 0)       { $errors[] = "Please choose a court."; }
    if ($date === '')         { $errors[] = "Please choose a date."; }
    if ($start === '')        { $errors[] = "Please choose a start time."; }
    if ($end === '')          { $errors[] = "Please choose an end time."; }

    if ($date !== '' && $date < date("Y-m-d")) {
        $errors[] = "You cannot book a date in the past.";
    }
    if ($start !== '' && $end !== '' && $end <= $start) {
        $errors[] = "End time must be after the start time.";
    }

    // --- Double-booking check ---
    // A clash is any confirmed booking on the same court and date whose
    // time range overlaps the requested one (start < existing_end AND
    // end > existing_start).
    if (empty($errors)) {
        $clashSql = "SELECT booking_id FROM bookings
                     WHERE court_id = ? AND booking_date = ? AND status = 'Confirmed'
                       AND start_time < ? AND end_time > ?";
        $stmt = mysqli_prepare($conn, $clashSql);
        mysqli_stmt_bind_param($stmt, "isss", $court_id, $date, $end, $start);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "Sorry, that court is already booked for an overlapping time. Please pick another slot.";
        }
        mysqli_stmt_close($stmt);
    }

    // --- Insert the booking ---
    if (empty($errors)) {
        $member_id = $_SESSION['member_id'];
        $ins = mysqli_prepare($conn,
            "INSERT INTO bookings (member_id, court_id, booking_date, start_time, end_time, status)
             VALUES (?, ?, ?, ?, ?, 'Confirmed')");
        mysqli_stmt_bind_param($ins, "iisss", $member_id, $court_id, $date, $start, $end);
        if (mysqli_stmt_execute($ins)) {
            $success = "Your booking is confirmed! You can view it on your bookings page.";
        } else {
            $errors[] = "Could not save your booking. Please try again.";
        }
        mysqli_stmt_close($ins);
    }
}

include 'includes/header.php';
?>

<section class="page-head">
    <h1>Book a Court</h1>
    <p>Choose a court, date and time. We&rsquo;ll check it&rsquo;s free before confirming.</p>
</section>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?php echo e($success); ?>
        <a href="my-bookings.php">View my bookings &rarr;</a>
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?php echo e($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="content-block narrow">
    <form method="post" action="book.php" novalidate class="form" id="bookingForm">
        <label for="court_id">Court</label>
        <select id="court_id" name="court_id">
            <option value="">-- Select a court --</option>
            <?php while ($c = mysqli_fetch_assoc($courts)): ?>
                <?php
                    $selected = '';
                    $posted = $_POST['court_id'] ?? $preselect;
                    if ((int)$posted === (int)$c['court_id']) { $selected = 'selected'; }
                ?>
                <option value="<?php echo (int)$c['court_id']; ?>" <?php echo $selected; ?>>
                    <?php echo e($c['court_name'] . ' - ' . $c['court_type'] . ' ($' . number_format($c['hourly_rate'], 2) . '/hr)'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="booking_date">Date</label>
        <input type="date" id="booking_date" name="booking_date"
               min="<?php echo date('Y-m-d'); ?>"
               value="<?php echo e($_POST['booking_date'] ?? ''); ?>">

        <div class="form-row">
            <div>
                <label for="start_time">Start time</label>
                <input type="time" id="start_time" name="start_time" value="<?php echo e($_POST['start_time'] ?? ''); ?>">
            </div>
            <div>
                <label for="end_time">End time</label>
                <input type="time" id="end_time" name="end_time" value="<?php echo e($_POST['end_time'] ?? ''); ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Confirm booking</button>
    </form>
</section>

<?php
mysqli_close($conn);
include 'includes/footer.php';
?>
