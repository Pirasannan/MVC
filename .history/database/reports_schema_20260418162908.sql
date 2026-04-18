-- Optional setup for report feature.
-- Run this only if your database does not already have the reports table.

CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_type ENUM('Patient', 'Doctor', 'Admin') NOT NULL,
    reporter_id INT NOT NULL,
    reported_type ENUM('Patient', 'Doctor', 'Admin', 'System') NOT NULL,
    reported_id INT NULL,
    report_type VARCHAR(100) NOT NULL,
    reason TEXT NOT NULL,
    description TEXT NULL,
    status ENUM('pending', 'under_review', 'resolved', 'dismissed') DEFAULT 'pending',
    resolution TEXT NULL,
    resolved_by INT NULL,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_reporter (reporter_type, reporter_id),
    INDEX idx_reported (reported_type, reported_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),

    CONSTRAINT fk_reports_reporter
        FOREIGN KEY (reporter_id) REFERENCES Users(id) ON DELETE CASCADE,
    CONSTRAINT fk_reports_reported
        FOREIGN KEY (reported_id) REFERENCES Users(id) ON DELETE SET NULL,
    CONSTRAINT fk_reports_resolved_by
        FOREIGN KEY (resolved_by) REFERENCES Users(id) ON DELETE SET NULL
);
