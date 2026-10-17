-- =====================================================================
-- StudentHub | Practical 8 : MySQL Schema Design + PDO Connectivity
-- Database : studenthub_p8 (utf8mb4)
-- How to use : import this file in phpMyAdmin (XAMPP) -> Import tab.
-- Tables   : users, students, events, registrations, audit_logs
-- Extra    : stored procedure GetEventRegistrationCount()
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `studenthub_p8`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `studenthub_p8`;

SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- Table: users (login accounts for admin and students)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','student') NOT NULL DEFAULT 'student',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` DATETIME NULL DEFAULT NULL,
  `remember_token_hash` VARCHAR(255) NULL DEFAULT NULL,
  `remember_expires` DATETIME NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: students (student profiles, kept separate from login accounts)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NULL DEFAULT NULL,
  `student_id` VARCHAR(20) NOT NULL,
  `course` VARCHAR(100) NOT NULL,
  `year` INT NOT NULL,
  `gender` VARCHAR(20) NULL DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_students_email` (`email`),
  UNIQUE KEY `uq_students_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: events (college events students can register for)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Technical',
  `event_date` DATE NOT NULL,
  `venue` VARCHAR(150) NULL DEFAULT NULL,
  `status` ENUM('open','closed') NOT NULL DEFAULT 'open',
  `poster` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: registrations (links students to events, many-to-many)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `student_id` INT NOT NULL,
  `event_id` INT NOT NULL,
  `registered_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_reg` (`student_id`, `event_id`),
  CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_id`)
    REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`)
    REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: audit_logs (record of important actions, e.g. logins)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `details` TEXT NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- Seed data
-- =====================================================================

-- Users (password hashes are real bcrypt hashes, cost 10 - do not edit)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`) VALUES
('admin', 'admin@charusat.edu.in', '$2b$10$IxL73cChhqbhMu8GgUBcQOw97GGxgivtR9dx0qAs6W4vsJ110Bg0e', 'admin'),
('student', 'student@charusat.edu.in', '$2b$10$g57ksW2ZDcxFPn9wV7GqbenmxjZ3MSXTRmo29G6vs03cq7Levmtyy', 'student');

-- Students (6 seed records)
INSERT INTO `students` (`full_name`, `email`, `phone`, `student_id`, `course`, `year`, `gender`) VALUES
('Aarav Patel', 'aarav.patel@charusat.edu.in', '9876543210', 'STU001', 'Computer Engineering', 2024, 'Male'),
('Diya Shah', 'diya.shah@charusat.edu.in', '9876543211', 'STU002', 'Information Technology', 2024, 'Female'),
('Kabir Mehta', 'kabir.mehta@charusat.edu.in', '9876543212', 'STU003', 'Electronics Engineering', 2023, 'Male'),
('Anaya Desai', 'anaya.desai@charusat.edu.in', '9876543213', 'STU004', 'Mechanical Engineering', 2025, 'Female'),
('Vihaan Rao', 'vihaan.rao@charusat.edu.in', '9876543214', 'STU005', 'Civil Engineering', 2024, 'Male'),
('Ishita Nair', 'ishita.nair@charusat.edu.in', '9876543215', 'STU006', 'Computer Engineering', 2023, 'Female');

-- Events (4 seed records)
INSERT INTO `events` (`title`, `description`, `category`, `event_date`, `venue`, `status`) VALUES
('TechFest 2026', 'Annual technical festival with coding contests and project expo.', 'Technical', '2026-02-15', 'Main Auditorium', 'open'),
('Robotics Workshop', 'Hands-on workshop on Arduino and basic robotics.', 'Technical', '2026-03-05', 'Lab Block B', 'open'),
('Cultural Night', 'Music, dance and drama performances by student clubs.', 'Cultural', '2026-04-10', 'Open Air Theatre', 'open'),
('Placement Drive', 'Campus placement drive for final-year students.', 'Career', '2026-01-20', 'Placement Cell', 'closed');

-- Registrations (3 seed records: Aarav + Diya -> TechFest, Kabir -> Robotics)
INSERT INTO `registrations` (`student_id`, `event_id`) VALUES
(1, 1),
(2, 1),
(3, 2);

-- Audit log (1 seed record)
INSERT INTO `audit_logs` (`user_id`, `action`, `details`, `ip_address`) VALUES
(1, 'login', 'Admin logged in successfully.', '127.0.0.1');

-- =====================================================================
-- Stored procedure (Advanced): registration count for one event
-- Uses JOIN + COUNT + GROUP BY.
-- Test it with: CALL GetEventRegistrationCount(1);
-- =====================================================================
DROP PROCEDURE IF EXISTS `GetEventRegistrationCount`;

DELIMITER $$

CREATE PROCEDURE `GetEventRegistrationCount` (IN p_event_id INT)
BEGIN
  SELECT e.`id` AS event_id,
         e.`title` AS event_title,
         COUNT(r.`id`) AS registration_count
  FROM `events` AS e
  LEFT JOIN `registrations` AS r ON r.`event_id` = e.`id`
  WHERE e.`id` = p_event_id
  GROUP BY e.`id`, e.`title`;
END$$

DELIMITER ;
