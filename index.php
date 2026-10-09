<?php
/* index.php - Home / landing page (Page 1) */
require_once 'includes/auth.php';
$page_title = "Home";
include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-text">
        <h1>Book your court in seconds</h1>
        <p>QuickCourt lets members of our community sports facility check availability
           and book netball, futsal, tennis and basketball courts online &ndash; any time, day or night.</p>
        <div class="hero-actions">
            <?php if (is_member()): ?>
                <a href="book.php" class="btn btn-primary">Book a Court</a>
                <a href="my-bookings.php" class="btn btn-outline">My Bookings</a>
            <?php else: ?>
                <a href="register.php" class="btn btn-primary">Get Started</a>
                <a href="courts.php" class="btn btn-outline">View Courts</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="features">
    <div class="feature-card">
        <h3>No more double-bookings</h3>
        <p>Every booking is stored in one shared system, so a court and time slot can only be taken once.</p>
    </div>
    <div class="feature-card">
        <h3>24/7 self-service</h3>
        <p>Check what&rsquo;s free and manage your bookings whenever it suits you &ndash; no phone calls needed.</p>
    </div>
    <div class="feature-card">
        <h3>Simple for everyone</h3>
        <p>Register in a minute, book in a few clicks, and cancel just as easily from your bookings page.</p>
    </div>
</section>

<section class="cta-band">
    <h2>Ready to play?</h2>
    <p>Join QuickCourt today and secure your spot on the court.</p>
    <?php if (!is_member()): ?>
        <a href="register.php" class="btn btn-primary">Create an account</a>
    <?php else: ?>
        <a href="book.php" class="btn btn-primary">Make a booking</a>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
