-- CICS Attendance System Database Schema
-- MySQL/MariaDB

CREATE DATABASE IF NOT EXISTS `cics_attendance` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cics_attendance`;

-- Users Table (for all user types: admin, instructor, student)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'instructor', 'student') NOT NULL,
  `status` ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
  `device_fingerprint` VARCHAR(255) NULL,
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Students Table
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `student_id` VARCHAR(50) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `program` VARCHAR(50) NOT NULL,
  `year_level` INT(1) NOT NULL,
  `section` VARCHAR(10) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `program` (`program`),
  KEY `year_level` (`year_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Parent/Guardian Information
CREATE TABLE IF NOT EXISTS `parents` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `contact_number` VARCHAR(20) NOT NULL,
  `relationship` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Instructors Table
CREATE TABLE IF NOT EXISTS `instructors` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  `employee_id` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `department` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subjects/Courses Table
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(20) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `instructor_id` INT(11) UNSIGNED NOT NULL,
  `program` VARCHAR(50) NOT NULL,
  `year_level` INT(1) NOT NULL,
  `section` VARCHAR(10) NOT NULL,
  `schedule` TEXT NOT NULL,
  `room` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`instructor_id`) REFERENCES `instructors`(`id`) ON DELETE CASCADE,
  KEY `code` (`code`),
  KEY `program` (`program`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Attendance Sessions (when instructor starts/ends a class)
CREATE TABLE IF NOT EXISTS `attendance_sessions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` INT(11) UNSIGNED NOT NULL,
  `instructor_id` INT(11) UNSIGNED NOT NULL,
  `session_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NULL,
  `status` ENUM('active', 'ended') DEFAULT 'active',
  `gps_latitude` DECIMAL(10, 8) NULL,
  `gps_longitude` DECIMAL(11, 8) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`instructor_id`) REFERENCES `instructors`(`id`) ON DELETE CASCADE,
  KEY `session_date` (`session_date`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Attendance Records
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` INT(11) UNSIGNED NOT NULL,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `time_in` DATETIME NOT NULL,
  `time_out` DATETIME NULL,
  `status` ENUM('present', 'late', 'absent') NOT NULL,
  `gps_latitude` DECIMAL(10, 8) NULL,
  `gps_longitude` DECIMAL(11, 8) NULL,
  `device_fingerprint` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`session_id`) REFERENCES `attendance_sessions`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  KEY `time_in` (`time_in`),
  KEY `status` (`status`),
  UNIQUE KEY `session_student` (`session_id`, `student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Correction Requests
CREATE TABLE IF NOT EXISTS `correction_requests` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_id` INT(11) UNSIGNED NOT NULL,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `reason` TEXT NOT NULL,
  `requested_status` ENUM('present', 'late', 'absent') NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `admin_id` INT(11) UNSIGNED NULL,
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`attendance_id`) REFERENCES `attendance_records`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`admin_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email Notifications Log
CREATE TABLE IF NOT EXISTS `email_notifications` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` INT(11) UNSIGNED NOT NULL,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `type` ENUM('daily_summary', 'absence_alert', 'late_alert') NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
  `sent_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`parent_id`) REFERENCES `parents`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System Settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(100) NOT NULL,
  `value` TEXT NULL,
  `type` VARCHAR(50) DEFAULT 'string',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - CHANGE IN PRODUCTION!)
INSERT INTO `users` (`email`, `password`, `role`, `status`) VALUES
('admin@zppsu.edu', '$2a$10$AI0eMVzvxhKxi2SIyHCfmut46N9oH789cmhVfUHNXaf9j7sJ9ulvC', 'admin', 'active');

-- Insert default settings
INSERT INTO `settings` (`key`, `value`, `type`) VALUES
('late_threshold', '15', 'integer'),
('absent_threshold', '30', 'integer'),
('allow_override', '1', 'boolean'),
('require_approval', '1', 'boolean'),
('send_email_notifications', '1', 'boolean'),
('campus_latitude', '7.1117', 'float'),
('campus_longitude', '122.0735', 'float'),
('campus_radius', '100', 'integer');

