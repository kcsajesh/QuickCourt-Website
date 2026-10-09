# QuickCourt — Community Sports Facility Booking System

A web-based booking system for a community sports facility (netball, futsal, tennis and basketball courts).

Members can register, log in, view courts, book a court (with automatic double-booking prevention), and manage their own bookings. Staff use an admin dashboard to manage courts, view all bookings, and manage members.

Built with **HTML5, CSS3, JavaScript, PHP and MySQL**.

---


## Features

| Area | What it does |
|------|--------------|
| Register / Login | Member sign-up and login, with passwords stored as secure hashes. Admins log in through the same page. |
| Courts | Public list of courts pulled live from the database. |
| Booking | Members book a court by date and time; the system rejects any slot that overlaps an existing booking. |
| My Bookings | Members view and cancel their own bookings. |
| Admin dashboard | Add/remove courts, view every booking, and view/remove members. |
| Contact | Contact form with validation. |
| Security | Prepared statements on every query, hashed passwords, session-based access control, and output escaping to prevent XSS. |

---

## Pages (9)

1. `index.php` — Home
2. `about.php` — About Us
3. `courts.php` — Courts & Facilities
4. `register.php` — Register
5. `login.php` — Login
6. `book.php` — Book a Court
7. `my-bookings.php` — My Bookings
8. `admin.php` — Admin Dashboard
9. `contact.php` — Contact Us

---

## Database (4 tables)

- **members** — member accounts
- **courts** — court details
- **bookings** — links members and courts (foreign keys)
- **admins** — staff accounts (kept separate from members)

---



## Test logins

| Role | Email | Password |
|------|-------|----------|
| Member | emma@example.com | password123 |
| Admin | admin@quickcourt.com | admin123 |

You can also register a brand-new member account from the Register page.

---

## Folder structure

```
quickcourt/
├── index.php, about.php, courts.php, register.php, login.php,
│   book.php, my-bookings.php, admin.php, contact.php, logout.php
├── includes/   db.php, header.php, footer.php, auth.php
├── css/        style.css
├── js/         script.js
├── sql/        quickcourt.sql
└── assets/images/
```
