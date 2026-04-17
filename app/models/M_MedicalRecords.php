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
}
