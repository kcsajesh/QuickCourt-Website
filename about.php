<?php
/* about.php - About Us (Page 2) */
require_once 'includes/auth.php';
$page_title = "About Us";
include 'includes/header.php';
?>

<section class="page-head">
    <h1>About QuickCourt</h1>
    <p>Making community sport easier to access.</p>
</section>

<section class="content-block">
    <h2>Who we are</h2>
    <p>QuickCourt is a community sports facility offering netball, futsal, tennis and
       basketball courts for players of all levels. For years our courts were booked
       by phone or on a paper diary at the front desk, which led to double-bookings,
       long waits, and no clear record of how our courts were being used.</p>

    <h2>What we do</h2>
    <p>This website is our answer to that problem. It gives members a single place to
       create an account, see which courts are available, and book or cancel a slot
       in just a few clicks. Our staff manage every court and booking from one
       dashboard, so the whole facility runs from a single, reliable source of truth.</p>

    <h2>Our mission</h2>
    <p>We want to remove the friction from community sport. By letting members book
       online at any time and freeing our staff from manual admin, we can spend more
       time supporting players and growing participation in local sport.</p>
</section>

<section class="stats-row">
    <div class="stat"><span class="stat-num">4</span><span class="stat-label">Courts available</span></div>
    <div class="stat"><span class="stat-num">24/7</span><span class="stat-label">Online booking</span></div>
    <div class="stat"><span class="stat-num">4</span><span class="stat-label">Sports offered</span></div>
</section>

<?php include 'includes/footer.php'; ?>
