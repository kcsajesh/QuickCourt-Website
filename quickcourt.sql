-- ============================================================
-- QuickCourt - Community Sports Facility Booking System
-- Database: quickcourt
-- ============================================================

CREATE DATABASE IF NOT EXISTS quickcourt
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE quickcourt;

-- Drop existing tables so the script can be re-run cleanly.
-- Order matters because of the foreign keys in `bookings`.
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS courts;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS admins;

-- ------------------------------------------------------------
-- Table: members
-- ------------------------------------------------------------
CREATE TABLE members (
    member_id     INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(100)  NOT NULL,
    email         VARCHAR(100)  NOT NULL UNIQUE,
    password_hash VARCHAR(255)  NOT NULL,
    phone         VARCHAR(20),
    date_joined   DATE          NOT NULL
);

-- ------------------------------------------------------------
-- Table: courts
-- ------------------------------------------------------------
CREATE TABLE courts (
    court_id    INT AUTO_INCREMENT PRIMARY KEY,
    court_name  VARCHAR(50)    NOT NULL,
    court_type  VARCHAR(30)    NOT NULL,
    capacity    INT            NOT NULL,
    hourly_rate DECIMAL(6,2)   NOT NULL
);

-- ------------------------------------------------------------
-- Table: admins
-- ------------------------------------------------------------
CREATE TABLE admins (
    admin_id      INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(100) NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          VARCHAR(20)  NOT NULL DEFAULT 'staff'
);

-- ------------------------------------------------------------
-- Table: bookings
-- Links members and courts through foreign keys.
-- ------------------------------------------------------------
CREATE TABLE bookings (
    booking_id   INT AUTO_INCREMENT PRIMARY KEY,
    member_id    INT          NOT NULL,
    court_id     INT          NOT NULL,
    booking_date DATE         NOT NULL,
    start_time   TIME         NOT NULL,
    end_time     TIME         NOT NULL,
    status       VARCHAR(20)  NOT NULL DEFAULT 'Confirmed',
    CONSTRAINT fk_booking_member
        FOREIGN KEY (member_id) REFERENCES members(member_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_booking_court
        FOREIGN KEY (court_id) REFERENCES courts(court_id)
        ON DELETE CASCADE
);

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Members
-- Login for testing: emma@example.com  /  password123
INSERT INTO members (full_name, email, password_hash, phone, date_joined) VALUES
('Emma Wilson', 'emma@example.com', '$2y$10$rp7aW2qZ/4K7OwkgL5SpU.v428GQzQrBHUbg4vilLmbXbv0acMFKi', '0412 345 678', '2026-01-15'),
('Liam Chen',   'liam@example.com', '$2y$10$rp7aW2qZ/4K7OwkgL5SpU.v428GQzQrBHUbg4vilLmbXbv0acMFKi', '0423 456 789', '2026-02-02');

-- Courts
INSERT INTO courts (court_name, court_type, capacity, hourly_rate) VALUES
('Court 1', 'Netball', 14, 30.00),
('Court 2', 'Futsal',  10, 40.00),
('Court 3', 'Tennis',   4, 25.00),
('Court 4', 'Basketball', 10, 35.00);

-- Admins
-- Login for testing: admin@quickcourt.com  /  admin123
INSERT INTO admins (full_name, email, password_hash, role) VALUES
('Facility Manager', 'admin@quickcourt.com', '$2y$10$U3/AaSR5oKEJmfnPNLRIQO3/Fhrmas7PpjxbPom6fMkQS0WHgymTm', 'manager');

-- Bookings (sample)
INSERT INTO bookings (member_id, court_id, booking_date, start_time, end_time, status) VALUES
(1, 1, '2026-10-10', '18:00:00', '19:00:00', 'Confirmed'),
(2, 3, '2026-10-11', '09:00:00', '10:00:00', 'Confirmed');
