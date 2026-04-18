<?php

class M_MedicalRecords {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all records for a patient
    public function getRecordsByPatient($patient_id) {
        $this->db->query('
            SELECT * FROM medical_records 
            WHERE patient_id = :patient_id 
            ORDER BY uploaded_at DESC
        ');
        $this->db->bind(':patient_id', $patient_id);
        return $this->db->resultSet();
    }

    // Get a single record (enforces ownership)
    public function getRecordById($id, $patient_id) {
        $this->db->query('
            SELECT * FROM medical_records 
            WHERE id = :id AND patient_id = :patient_id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':patient_id', $patient_id);
        return $this->db->single();
    }

    // Get a single record without ownership check (used for shared access)
    public function getRecordByIdOnly($id) {
        $this->db->query('SELECT * FROM medical_records WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Create a new record
    public function createRecord($data) {
        $this->db->query('
            INSERT INTO medical_records (patient_id, record_name, record_type, doctor_name, description, file_name, original_name, file_size, mime_type) 
            VALUES (:patient_id, :record_name, :record_type, :doctor_name, :description, :file_name, :original_name, :file_size, :mime_type)
        ');
        
        $this->db->bind(':patient_id', $data['patient_id']);
        $this->db->bind(':record_name', $data['record_name']);
        $this->db->bind(':record_type', $data['record_type']);
        $this->db->bind(':doctor_name', $data['doctor_name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':file_name', $data['file_name']);
        $this->db->bind(':original_name', $data['original_name']);
        $this->db->bind(':file_size', $data['file_size']);
        $this->db->bind(':mime_type', $data['mime_type']);

        return $this->db->execute();
    }

    // Update record metadata
    public function updateRecord($id, $patient_id, $data) {
        $this->db->query('
            UPDATE medical_records 
            SET record_name = :record_name, 
                record_type = :record_type, 
                doctor_name = :doctor_name, 
                description = :description 
            WHERE id = :id AND patient_id = :patient_id
        ');
        
        $this->db->bind(':record_name', $data['record_name']);
        $this->db->bind(':record_type', $data['record_type']);
        $this->db->bind(':doctor_name', $data['doctor_name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':id', $id);
        $this->db->bind(':patient_id', $patient_id);

        return $this->db->execute();
    }

    // Update record and its attached file
    public function updateRecordWithFile($id, $patient_id, $data) {
        $this->db->query('
            UPDATE medical_records 
            SET record_name = :record_name, 
                record_type = :record_type, 
                doctor_name = :doctor_name, 
                description = :description,
                file_name = :file_name,
                original_name = :original_name,
                file_size = :file_size,
                mime_type = :mime_type
            WHERE id = :id AND patient_id = :patient_id
        ');
        
        $this->db->bind(':record_name', $data['record_name']);
        $this->db->bind(':record_type', $data['record_type']);
        $this->db->bind(':doctor_name', $data['doctor_name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':file_name', $data['file_name']);
        $this->db->bind(':original_name', $data['original_name']);
        $this->db->bind(':file_size', $data['file_size']);
        $this->db->bind(':mime_type', $data['mime_type']);
        $this->db->bind(':id', $id);
        $this->db->bind(':patient_id', $patient_id);

        return $this->db->execute();
    }

    // Delete a record
    public function deleteRecord($id, $patient_id) {
        $this->db->query('
            DELETE FROM medical_records 
            WHERE id = :id AND patient_id = :patient_id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':patient_id', $patient_id);

        return $this->db->execute();
    }

    // Get statistics for the dashboard
    public function getStatsByPatient($patient_id) {
        $this->db->query('
            SELECT record_type, COUNT(*) as count 
            FROM medical_records 
            WHERE patient_id = :patient_id 
            GROUP BY record_type
        ');
        $this->db->bind(':patient_id', $patient_id);
        $results = $this->db->resultSet();

        // Format as key-value pairs
        $stats = [
            'lab' => 0,
            'scan' => 0,
            'prescription' => 0,
            'hospital' => 0,
            'vaccination' => 0,
            'total' => 0
        ];

        foreach ($results as $row) {
            if (array_key_exists($row->record_type, $stats)) {
                $stats[$row->record_type] = $row->count;
                $stats['total'] += $row->count;
            }
        }

        return $stats;
    }

    // --- SHARING LOGIC ---

    private function ensureSharedTable() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `shared_medical_records` (
                `id`            INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `record_id`     INT(10) UNSIGNED NOT NULL,
                `doctor_id`     INT(10) UNSIGNED NOT NULL,
                `shared_at`     DATETIME         DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_record_id` (`record_id`),
                INDEX `idx_doctor_id` (`doctor_id`),
                UNIQUE KEY `unique_share` (`record_id`, `doctor_id`),
                CONSTRAINT `fk_shared_medical_record`
                    FOREIGN KEY (`record_id`) REFERENCES `medical_records`(`id`) ON DELETE CASCADE,
                CONSTRAINT `fk_shared_doctor`
                    FOREIGN KEY (`doctor_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
        $this->db->execute();
    }

    public function shareRecord($recordId, $doctorId) {
        $this->ensureSharedTable();
        $this->db->query('
            INSERT IGNORE INTO shared_medical_records (record_id, doctor_id) 
            VALUES (:record_id, :doctor_id)
        ');
        $this->db->bind(':record_id', $recordId);
        $this->db->bind(':doctor_id', $doctorId);
        return $this->db->execute();
    }

    public function getSharedRecordsForDoctor($doctorId) {
        $this->ensureSharedTable();
        $this->db->query('
            SELECT mr.*, u.name as patient_name 
            FROM medical_records mr
            JOIN shared_medical_records smr ON mr.id = smr.record_id
            JOIN Users u ON mr.patient_id = u.id
            WHERE smr.doctor_id = :doctor_id
            ORDER BY smr.shared_at DESC
        ');
        $this->db->bind(':doctor_id', $doctorId);
        return $this->db->resultSet();
    }

    public function getActiveDoctors($searchTerm = '') {
        $sql = "SELECT id, name, email FROM Users WHERE role = 'doctor' AND status = 'active'";
        if (!empty($searchTerm)) {
            $sql .= " AND (name LIKE :search OR email LIKE :search)";
        }
        $sql .= " LIMIT 10";

        $this->db->query($sql);
        if (!empty($searchTerm)) {
            $this->db->bind(':search', '%' . $searchTerm . '%');
        }
        return $this->db->resultSet();
    }

    public function isRecordSharedWith($recordId, $doctorId) {
        $this->ensureSharedTable();
        $this->db->query('
            SELECT id FROM shared_medical_records 
            WHERE record_id = :record_id AND doctor_id = :doctor_id
        ');
        $this->db->bind(':record_id', $recordId);
        $this->db->bind(':doctor_id', $doctorId);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }
}
