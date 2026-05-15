-- ============================================================
-- FacultyLens — Neural Feedback System
-- MySQL Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS neural_feedback
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE neural_feedback;

-- ============================================================
-- 1. USERS TABLE  (students, faculty, admin, hod)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(120)  NOT NULL,
  email         VARCHAR(180)  NOT NULL UNIQUE,
  password      VARCHAR(255)  NOT NULL,          -- bcrypt hash
  role          ENUM('student','faculty','admin','hod') NOT NULL DEFAULT 'student',
  username      VARCHAR(80)   DEFAULT NULL,
  is_logged_in  TINYINT(1)    NOT NULL DEFAULT 0,
  last_login    DATETIME      DEFAULT NULL,
  created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_role  (role)
) ENGINE=InnoDB;

-- ============================================================
-- 2. FACULTY TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS faculty (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(120)  NOT NULL,
  designation   VARCHAR(120)  DEFAULT NULL,
  department    VARCHAR(120)  DEFAULT 'Data Science Engineering',
  subject       VARCHAR(180)  DEFAULT NULL,
  email         VARCHAR(180)  DEFAULT NULL,
  photo         VARCHAR(255)  DEFAULT NULL,
  qualification TEXT          DEFAULT NULL,
  experience    VARCHAR(60)   DEFAULT NULL,
  expertise     TEXT          DEFAULT NULL,
  created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 3. FEEDBACK TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS feedback (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT           DEFAULT NULL,
  faculty_id    INT           DEFAULT NULL,
  teacher       VARCHAR(120)  NOT NULL,
  subject       VARCHAR(180)  DEFAULT NULL,
  clarity       INT           NOT NULL CHECK (clarity BETWEEN 1 AND 10),
  knowledge     INT           NOT NULL CHECK (knowledge BETWEEN 1 AND 10),
  interaction   INT           NOT NULL CHECK (interaction BETWEEN 1 AND 10),
  comment       TEXT          DEFAULT NULL,
  sentiment     ENUM('positive','neutral','negative') DEFAULT NULL,
  submitted_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)    REFERENCES users(id)   ON DELETE SET NULL,
  FOREIGN KEY (faculty_id) REFERENCES faculty(id)  ON DELETE SET NULL,
  INDEX idx_teacher (teacher),
  INDEX idx_submitted (submitted_at)
) ENGINE=InnoDB;

-- ============================================================
-- 4. SEED FACULTY DATA
-- ============================================================
INSERT INTO faculty (name, designation, department, subject, email, photo) VALUES
('Dr. Adarsh M J',        'Head of Department',       'Data Science Engineering', 'Machine Learning, Deep Learning',                  'hod.dse@college.edu',      'hod-3d.png'),
('Prof. Gagana Deepa J',  'Branch Coordinator',       'Data Science Engineering', 'Data Analytics, Cloud Infrastructure (AWS)',        'gagana_deepa@college.edu', 'GAGANA 3d.png'),
('Prof. Srikanth A S',    'Mathematics Faculty',      'Data Science Engineering', 'Engineering Mathematics',                          'aiml@aitckm.in',          'shrikanth 3d.png'),
('Prof. Harshitha H D',   'Data Structures Faculty',  'Data Science Engineering', 'Algorithms & DS, Data Analyst',                    'harshithahd@gmail.com',   'harsita 3d.png'),
('Prof. Shalini I.S',     'DDCO Faculty',             'Data Science Engineering', 'Digital Electronics, Logic Design',                 'shalinis@gmail.com',      'SHALINI 3d.png'),
('Dr. Thayaba Nausheen A','Operating System Faculty',  'Data Science Engineering', 'System Design & OS',                               'thayaba.os@college.edu',  'os3d.png'),
('Prof. Ramya C K',       'Python Faculty',           'Data Science Engineering', 'Python, AI',                                       'ramyack.py@college.edu',  'ramya3d.png'),
('Prof. Pallavi C.S',     'PHP Faculty',              'Data Science Engineering', 'Web Development',                                  'pallavi.php@college.edu', 'PALLAVI 3d.png'),
('Prof. Seema B S',       'SCR Activity Faculty',     'Data Science Engineering', 'Social Connect and Responsibility',                'seema.scr@college.edu',   'seema3d.png');

-- ============================================================
-- 5. DEFAULT ADMIN USER  (password: admin123)
-- ============================================================
INSERT INTO users (name, email, password, role, username) VALUES
('Admin', 'admin@facultylens.edu',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'admin', 'admin');

-- ============================================================
-- 6. DEFAULT STUDENT USER  (password: student123)
-- ============================================================
INSERT INTO users (name, email, password, role, username) VALUES
('Demo Student', 'student@facultylens.edu',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'student', 'demo_student');
