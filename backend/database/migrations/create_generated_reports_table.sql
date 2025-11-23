-- Create Generated Reports Table
-- This stores metadata about generated reports

CREATE TABLE IF NOT EXISTS `generated_reports` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `report_type` ENUM('attendance_summary', 'student_registration', 'class_attendance', 'daily_attendance') NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `file_format` ENUM('xlsx', 'csv', 'pdf') NOT NULL,
  `file_size` INT(11) UNSIGNED NOT NULL COMMENT 'File size in bytes',
  `date_from` DATE NULL,
  `date_to` DATE NULL,
  `filters` JSON NULL COMMENT 'Store filter parameters as JSON',
  `generated_by` INT(11) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`generated_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `report_type` (`report_type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
