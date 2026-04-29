-- ============================================================
--  Academic GPA Management System — database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS gpa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gpa_db;

-- ─────────────────────────────────────────────
--  TABLES
-- ─────────────────────────────────────────────

CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('admin','professor','student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE semesters (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    label         VARCHAR(20)  NOT NULL,
    academic_year VARCHAR(20)  NOT NULL,
    is_active     BOOLEAN DEFAULT FALSE,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    semester_id INT NOT NULL,
    name        VARCHAR(150) NOT NULL,
    credits     INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (semester_id) REFERENCES semesters(id)
);

CREATE TABLE enrollments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    student_id  INT NOT NULL,
    semester_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_enroll (student_id, semester_id),
    FOREIGN KEY (student_id)  REFERENCES users(id),
    FOREIGN KEY (semester_id) REFERENCES semesters(id)
);

CREATE TABLE assignments (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    course_id    INT NOT NULL,
    semester_id  INT NOT NULL,
    assigned_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_assign (professor_id, course_id, semester_id),
    FOREIGN KEY (professor_id) REFERENCES users(id),
    FOREIGN KEY (course_id)    REFERENCES courses(id),
    FOREIGN KEY (semester_id)  REFERENCES semesters(id)
);

CREATE TABLE grades (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    student_id   INT NOT NULL,
    course_id    INT NOT NULL,
    semester_id  INT NOT NULL,
    professor_id INT NOT NULL,
    grade        DECIMAL(3,1) NOT NULL,
    entered_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_grade (student_id, course_id, semester_id),
    FOREIGN KEY (student_id)   REFERENCES users(id),
    FOREIGN KEY (course_id)    REFERENCES courses(id),
    FOREIGN KEY (semester_id)  REFERENCES semesters(id),
    FOREIGN KEY (professor_id) REFERENCES users(id)
);

CREATE TABLE gpa_records (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    student_id  INT NOT NULL,
    semester_id INT NOT NULL,
    gpa         DECIMAL(4,2) NOT NULL,
    computed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_gpa (student_id, semester_id),
    FOREIGN KEY (student_id)  REFERENCES users(id),
    FOREIGN KEY (semester_id) REFERENCES semesters(id)
);

-- ─────────────────────────────────────────────
--  SEED DATA
-- ─────────────────────────────────────────────

-- Passwords: all are "password" hashed with PASSWORD_BCRYPT
INSERT INTO users (name, email, password, role) VALUES
('Admin',          'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Prof. Benali',   'prof@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'professor'),
('Test Student',   'stud@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Sara Amrani',    'sara@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Karim Meziani',  'karim@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

INSERT INTO semesters (label, academic_year, is_active) VALUES
('S1', '2024/2025', TRUE),
('S2', '2024/2025', FALSE);

INSERT INTO courses (semester_id, name, credits) VALUES
(1, 'Web Development',       3),
(1, 'Computer Networks',     3),
(1, 'Database Theory',       3),
(2, 'Operating Systems',     3),
(2, 'Software Engineering',  3);

INSERT INTO enrollments (student_id, semester_id) VALUES
(3, 1),(3, 2),
(4, 1),(4, 2),
(5, 1);

INSERT INTO assignments (professor_id, course_id, semester_id) VALUES
(2, 1, 1),(2, 2, 1),(2, 3, 1),
(2, 4, 2),(2, 5, 2);

INSERT INTO grades (student_id, course_id, semester_id, professor_id, grade) VALUES
(3, 1, 1, 2, 3.7),
(3, 2, 1, 2, 3.3),
(3, 3, 1, 2, 4.0),
(4, 1, 1, 2, 2.7),
(4, 2, 1, 2, 3.0);

INSERT INTO gpa_records (student_id, semester_id, gpa) VALUES
(3, 1, 3.67),
(4, 1, 2.85);
