<?php
/* contact.php - Contact Us (Page 9)
 * A simple contact form. On submit it validates and shows a thank-you
 * message. (Messages are not stored in the database for this project.) */
require_once 'includes/auth.php';
$page_title = "Contact Us";

$errors = [];
$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '')    { $errors[] = "Please enter your name."; }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if ($message === '') { $errors[] = "Please enter a message."; }

    if (empty($errors)) {
        $sent = true;
    }
}

include 'includes/header.php';
?>

<section class="page-head">
    <h1>Contact Us</h1>
    <p>Questions about membership or bookings? Send us a message.</p>
</section>

<div class="two-col">
    <section class="content-block">
        <?php if ($sent): ?>
            <div class="alert alert-success">
                Thanks, <?php echo e($name); ?>! Your message has been received.
                We&rsquo;ll get back to you at <?php echo e($email); ?> soon.
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="contact.php" novalidate class="form" id="contactForm">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo e($_POST['name'] ?? ''); ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo e($_POST['email'] ?? ''); ?>">

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5"><?php echo e($_POST['message'] ?? ''); ?></textarea>

                <button type="submit" class="btn btn-primary">Send message</button>
            </form>
        <?php endif; ?>
    </section>

    <aside class="info-panel">
        <h3>Facility details</h3>
        <p><strong>Address:</strong><br>123 Community Drive, Sydney NSW</p>
        <p><strong>Phone:</strong><br>(02) 1234 5678</p>
        <p><strong>Email:</strong><br>info@quickcourt.com</p>
        <p><strong>Opening hours:</strong><br>Mon&ndash;Sun, 7:00am &ndash; 10:00pm</p>
    </aside>
</div>

<?php include 'includes/footer.php'; ?>
