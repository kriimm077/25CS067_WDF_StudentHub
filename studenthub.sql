-- StudentHub Database Schema & Seed Data
-- Practical 8–15 | ITUE203 Web Development Frameworks
-- Import into phpMyAdmin or: mysql -u root < studenthub.sql

CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub;

-- Users table (auth)
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- Students table (profile / management)
CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mobile VARCHAR(15),
    course VARCHAR(80) NOT NULL,
    year INT NOT NULL DEFAULT 1,
    gender ENUM('Male','Female','Other') DEFAULT 'Other',
    enrollment_no VARCHAR(30) UNIQUE,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Events table
CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    location VARCHAR(150),
    category VARCHAR(50) DEFAULT 'General',
    poster VARCHAR(255) NULL,
    status ENUM('open','closed','cancelled') DEFAULT 'open',
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Event registrations
CREATE TABLE registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_reg (event_id, student_id),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Audit log
CREATE TABLE audit_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ===================== SEED DATA =====================

-- Admin: admin@studenthub.local / Admin@123
-- Student: student@studenthub.local / Student@123
INSERT INTO users (name, email, password, role) VALUES
('System Admin', 'admin@studenthub.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Demo Student', 'student@studenthub.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Rahul Sharma', 'rahul@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Priya Patel', 'priya@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Amit Kumar', 'amit@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

-- Note: The hash above is for password "password". For production use password_hash().
-- Real demo passwords for testing (re-hash recommended):
-- We will document clear passwords in README and provide a setup note.

INSERT INTO students (user_id, full_name, email, mobile, course, year, gender, enrollment_no) VALUES
(2, 'Demo Student', 'student@studenthub.local', '9876543210', 'Computer Engineering', 3, 'Male', 'CE2023001'),
(3, 'Rahul Sharma', 'rahul@example.com', '9876543211', 'Information Technology', 2, 'Male', 'IT2024001'),
(4, 'Priya Patel', 'priya@example.com', '9876543212', 'Computer Engineering', 3, 'Female', 'CE2023002'),
(5, 'Amit Kumar', 'amit@example.com', '9876543213', 'CSE', 1, 'Male', 'CSE2025001'),
(NULL, 'Sneha Desai', 'sneha@example.com', '9876543214', 'IT', 2, 'Female', 'IT2024002'),
(NULL, 'Karan Mehta', 'karan@example.com', '9876543215', 'CE', 4, 'Male', 'CE2022001'),
(NULL, 'Ananya Shah', 'ananya@example.com', '9876543216', 'AIML', 2, 'Female', 'AIML2024001'),
(NULL, 'Vikram Joshi', 'vikram@example.com', '9876543217', 'CE', 3, 'Male', 'CE2023003'),
(NULL, 'Neha Gupta', 'neha@example.com', '9876543218', 'IT', 1, 'Female', 'IT2025001'),
(NULL, 'Rohan Verma', 'rohan@example.com', '9876543219', 'CSE', 2, 'Male', 'CSE2024001'),
(NULL, 'Ishita Rao', 'ishita@example.com', '9876543220', 'CE', 3, 'Female', 'CE2023004'),
(NULL, 'Siddharth Nair', 'sid@example.com', '9876543221', 'IT', 4, 'Male', 'IT2022001'),
(NULL, 'Meera Iyer', 'meera@example.com', '9876543222', 'AIML', 1, 'Female', 'AIML2025001'),
(NULL, 'Arjun Reddy', 'arjun@example.com', '9876543223', 'CSE', 3, 'Male', 'CSE2023001'),
(NULL, 'Pooja Singh', 'pooja@example.com', '9876543224', 'CE', 2, 'Female', 'CE2024001'),
(NULL, 'Harsh Patel', 'harsh@example.com', '9876543225', 'IT', 3, 'Male', 'IT2023001'),
(NULL, 'Divya Sharma', 'divya@example.com', '9876543226', 'CSE', 1, 'Female', 'CSE2025002'),
(NULL, 'Nikhil Jain', 'nikhil@example.com', '9876543227', 'AIML', 2, 'Male', 'AIML2024002'),
(NULL, 'Kavya Menon', 'kavya@example.com', '9876543228', 'CE', 4, 'Female', 'CE2022002'),
(NULL, 'Yash Thakur', 'yash@example.com', '9876543229', 'IT', 2, 'Male', 'IT2024003');

INSERT INTO events (title, description, event_date, location, category, status, created_by) VALUES
('TechFest 2026', 'Annual technical festival with coding contests, robotics and workshops.', '2026-11-15', 'Main Auditorium', 'Technical', 'open', 1),
('Cultural Night', 'Music, dance and drama performances by students.', '2026-12-05', 'Open Air Theatre', 'Cultural', 'open', 1),
('Hackathon 24hrs', 'Build innovative solutions in 24 hours. Prizes worth 50k.', '2026-11-22', 'Innovation Lab', 'Technical', 'open', 1),
('Career Fair', 'Meet recruiters from top companies. Bring your resume.', '2026-12-12', 'Convention Hall', 'Career', 'open', 1),
('Sports Day', 'Inter-department sports competitions.', '2026-11-30', 'Sports Complex', 'Sports', 'open', 1),
('AI Workshop', 'Hands-on workshop on Generative AI and LLMs.', '2026-11-10', 'Seminar Hall B', 'Workshop', 'open', 1),
('Alumni Meet', 'Connect with alumni and network.', '2026-12-20', 'Main Auditorium', 'Networking', 'open', 1),
('Blood Donation Camp', 'Donate blood, save lives. Organized with Red Cross.', '2026-11-08', 'Medical Center', 'Social', 'open', 1);

INSERT INTO registrations (event_id, student_id) VALUES
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 4),
(3, 2), (3, 5), (3, 6),
(4, 1), (4, 7),
(5, 3), (5, 8);

INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES
(1, 'LOGIN', 'Admin logged in', '127.0.0.1'),
(1, 'EVENT_CREATE', 'Created event TechFest 2026', '127.0.0.1');
