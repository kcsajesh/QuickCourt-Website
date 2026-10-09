<?php
/* courts.php - Courts & Facilities (Page 3)
 * Reads the list of courts from the database (SELECT). */
require_once 'includes/auth.php';
require_once 'includes/db.php';
$page_title = "Courts & Facilities";
include 'includes/header.php';

// Fetch all courts
$sql = "SELECT court_id, court_name, court_type, capacity, hourly_rate
        FROM courts ORDER BY court_name";
$result = mysqli_query($conn, $sql);
?>

<section class="page-head">
    <h1>Courts &amp; Facilities</h1>
    <p>Browse our courts, then log in to book the one you want.</p>
</section>

<section class="court-grid">
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($court = mysqli_fetch_assoc($result)): ?>
            <article class="court-card">
                <div class="court-tag"><?php echo e($court['court_type']); ?></div>
                <h3><?php echo e($court['court_name']); ?></h3>
                <ul class="court-meta">
                    <li><strong>Sport:</strong> <?php echo e($court['court_type']); ?></li>
                    <li><strong>Capacity:</strong> <?php echo e($court['capacity']); ?> players</li>
                    <li><strong>Rate:</strong> $<?php echo e(number_format($court['hourly_rate'], 2)); ?> / hour</li>
                </ul>
                <?php if (is_member()): ?>
                    <a href="book.php?court_id=<?php echo (int)$court['court_id']; ?>" class="btn btn-primary btn-sm">Book this court</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline btn-sm">Log in to book</a>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No courts are available at the moment. Please check back soon.</p>
    <?php endif; ?>
</section>

<?php
mysqli_close($conn);
include 'includes/footer.php';
?>
