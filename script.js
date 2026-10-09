/* =========================================================
   QuickCourt - Client-side form validation (JavaScript)
   Runs in the browser before the form is sent to PHP.
   PHP still validates on the server; this just gives users
   faster feedback.
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    // Small helper: show an inline error message under a field
    function showError(input, message) {
        clearError(input);
        input.classList.add("input-error");
        var msg = document.createElement("div");
        msg.className = "field-error";
        msg.style.color = "#c0392b";
        msg.style.fontSize = "0.85rem";
        msg.style.marginTop = "4px";
        msg.textContent = message;
        input.parentNode.insertBefore(msg, input.nextSibling);
    }

    function clearError(input) {
        input.classList.remove("input-error");
        var next = input.nextSibling;
        if (next && next.className === "field-error") {
            next.parentNode.removeChild(next);
        }
    }

    function isEmail(value) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }

    // ---- Registration form ----
    var registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            var ok = true;
            var name = document.getElementById("full_name");
            var email = document.getElementById("email");
            var pw = document.getElementById("password");
            var confirm = document.getElementById("confirm");

            [name, email, pw, confirm].forEach(clearError);

            if (name.value.trim() === "") { showError(name, "Please enter your name."); ok = false; }
            if (!isEmail(email.value.trim())) { showError(email, "Enter a valid email."); ok = false; }
            if (pw.value.length < 6) { showError(pw, "Password must be at least 6 characters."); ok = false; }
            if (pw.value !== confirm.value) { showError(confirm, "Passwords do not match."); ok = false; }

            if (!ok) { e.preventDefault(); }
        });
    }

    // ---- Login form ----
    var loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            var ok = true;
            var email = document.getElementById("email");
            var pw = document.getElementById("password");
            [email, pw].forEach(clearError);

            if (!isEmail(email.value.trim())) { showError(email, "Enter a valid email."); ok = false; }
            if (pw.value === "") { showError(pw, "Please enter your password."); ok = false; }

            if (!ok) { e.preventDefault(); }
        });
    }

    // ---- Contact form ----
    var contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            var ok = true;
            var name = document.getElementById("name");
            var email = document.getElementById("email");
            var message = document.getElementById("message");
            [name, email, message].forEach(clearError);

            if (name.value.trim() === "") { showError(name, "Please enter your name."); ok = false; }
            if (!isEmail(email.value.trim())) { showError(email, "Enter a valid email."); ok = false; }
            if (message.value.trim() === "") { showError(message, "Please enter a message."); ok = false; }

            if (!ok) { e.preventDefault(); }
        });
    }

    // ---- Booking form ----
    var bookingForm = document.getElementById("bookingForm");
    if (bookingForm) {
        bookingForm.addEventListener("submit", function (e) {
            var ok = true;
            var court = document.getElementById("court_id");
            var date = document.getElementById("booking_date");
            var start = document.getElementById("start_time");
            var end = document.getElementById("end_time");
            [court, date, start, end].forEach(clearError);

            if (court.value === "") { showError(court, "Please choose a court."); ok = false; }
            if (date.value === "") { showError(date, "Please choose a date."); ok = false; }
            if (start.value === "") { showError(start, "Choose a start time."); ok = false; }
            if (end.value === "") { showError(end, "Choose an end time."); ok = false; }
            if (start.value !== "" && end.value !== "" && end.value <= start.value) {
                showError(end, "End time must be after start time."); ok = false;
            }

            if (!ok) { e.preventDefault(); }
        });
    }

});
