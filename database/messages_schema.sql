-- Messages table for doctor-patient communication
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    sender_type ENUM('doctor', 'patient') NOT NULL,
    receiver_type ENUM('doctor', 'patient') NOT NULL,
    subject VARCHAR(255),
    message_text TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_sender (sender_id, sender_type),
    INDEX idx_receiver (receiver_id, receiver_type),
    INDEX idx_read_status (is_read),
    INDEX idx_created_at (created_at)
);

-- Insert sample messages for testing
INSERT INTO messages (sender_id, receiver_id, sender_type, receiver_type, subject, message_text, is_read, created_at) VALUES
(2, 1, 'patient', 'doctor', 'Appointment Follow-up', 'Hi Dr. Johnson, I wanted to follow up on my recent appointment. The medication you prescribed seems to be working well, but I have a few questions about the dosage.', FALSE, '2024-10-20 10:30:00'),
(1, 2, 'doctor', 'patient', 'RE: Appointment Follow-up', 'Hello! I am glad to hear the medication is working. Please feel free to ask any questions about the dosage. We can also schedule a follow-up if needed.', TRUE, '2024-10-20 14:15:00'),
(3, 1, 'patient', 'doctor', 'Lab Results Question', 'Doctor, I received my lab results and noticed some values that concern me. Could you please review them when you have a chance?', FALSE, '2024-10-21 09:45:00'),
(4, 1, 'patient', 'doctor', 'Prescription Refill', 'Hi, I need to refill my blood pressure medication. The current prescription expires next week.', FALSE, '2024-10-22 16:20:00'),
(1, 4, 'doctor', 'patient', 'RE: Prescription Refill', 'I have sent your prescription refill to your pharmacy. It should be ready for pickup tomorrow.', FALSE, '2024-10-22 17:30:00');