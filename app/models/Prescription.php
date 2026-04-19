<?php
class Prescription {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addPrescription($data) {
        try {
            $this->db->query('
                INSERT INTO prescriptions (
                    doctor_id, patient_id, drug_name, formulation, route, brand_substitution, prn, max_per_24h,
                    prn_indication, dose_amount, dose_unit, frequency, custom_frequency, time_of_day,
                    meal_relation, duration_value, duration_type, special_instructions, dispense_quantity,
                    unit_type, diagnosis, valid_until, pharmacy_note, doctor_notes
                ) VALUES (
                    :doctor_id, :patient_id, :drug_name, :formulation, :route, :brand_substitution, :prn, :max_per_24h,
                    :prn_indication, :dose_amount, :dose_unit, :frequency, :custom_frequency, :time_of_day,
                    :meal_relation, :duration_value, :duration_type, :special_instructions, :dispense_quantity,
                    :unit_type, :diagnosis, :valid_until, :pharmacy_note, :doctor_notes
                )
            ');

            foreach ($data as $key => $value) {
                $this->db->bind(':' . $key, $value);
            }

            return $this->db->execute();
        } catch (Throwable $e) {
            error_log('Prescription insert failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getPrescriptionsByDoctor($doctor_id) {
        $this->db->query('
            SELECT p.*, u.name AS patient_name
            FROM prescriptions p
            INNER JOIN Users u ON p.patient_id = u.id
            WHERE p.doctor_id = :doctor_id
            ORDER BY p.created_at DESC
        ');
        $this->db->bind(':doctor_id', $doctor_id);
        return $this->db->resultSet();
    }

    public function getPrescriptionsByPatient($patient_id) {
        $this->db->query('
            SELECT p.*, d.name AS doctor_name
            FROM prescriptions p
            INNER JOIN Users d ON p.doctor_id = d.id
            WHERE p.patient_id = :patient_id AND p.is_deleted = "not_deleted"
            ORDER BY p.created_at DESC
        ');
        $this->db->bind(':patient_id', $patient_id);
        return $this->db->resultSet();
    }

    public function getPrescriptionById($id) {
        $this->db->query('
            SELECT p.*, d.name AS doctor_name, u.name AS patient_name
            FROM prescriptions p
            INNER JOIN Users d ON p.doctor_id = d.id
            INNER JOIN Users u ON p.patient_id = u.id
            WHERE p.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getPrescriptionDetails($id) {
        $this->db->query('
            SELECT 
                p.*, 
                d.name AS doctor_name, 
                d.email AS doctor_email,
                d.slmc AS doctor_slmc,
                u.name AS patient_name,
                m.date_of_birth,
                m.blood_type,
                m.allergies
            FROM prescriptions p
            INNER JOIN Users d ON p.doctor_id = d.id
            INNER JOIN Users u ON p.patient_id = u.id
            LEFT JOIN patient_medical_info m ON p.patient_id = m.patient_id
            WHERE p.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

public function updatePrescription($data) {
    try {
        $this->db->query('
            UPDATE prescriptions SET
                patient_id = :patient_id,
                drug_name = :drug_name,
                formulation = :formulation,
                route = :route,
                brand_substitution = :brand_substitution,
                prn = :prn,
                max_per_24h = :max_per_24h,
                prn_indication = :prn_indication,
                dose_amount = :dose_amount,
                dose_unit = :dose_unit,
                frequency = :frequency,
                custom_frequency = :custom_frequency,
                time_of_day = :time_of_day,
                meal_relation = :meal_relation,
                duration_value = :duration_value,
                duration_type = :duration_type,
                special_instructions = :special_instructions,
                dispense_quantity = :dispense_quantity,
                unit_type = :unit_type,
                diagnosis = :diagnosis,
                valid_until = :valid_until,
                pharmacy_note = :pharmacy_note,
                doctor_notes = :doctor_notes,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':patient_id', $data['patient_id']);
        $this->db->bind(':drug_name', $data['drug_name']);
        $this->db->bind(':formulation', $data['formulation']);
        $this->db->bind(':route', $data['route']);
        $this->db->bind(':brand_substitution', $data['brand_substitution']);
        $this->db->bind(':prn', $data['prn']);
        $this->db->bind(':max_per_24h', $data['max_per_24h']);
        $this->db->bind(':prn_indication', $data['prn_indication']);
        $this->db->bind(':dose_amount', $data['dose_amount']);
        $this->db->bind(':dose_unit', $data['dose_unit']);
        $this->db->bind(':frequency', $data['frequency']);
        $this->db->bind(':custom_frequency', $data['custom_frequency']);
        $this->db->bind(':time_of_day', $data['time_of_day']);
        $this->db->bind(':meal_relation', $data['meal_relation']);
        $this->db->bind(':duration_value', $data['duration_value']);
        $this->db->bind(':duration_type', $data['duration_type']);
        $this->db->bind(':special_instructions', $data['special_instructions']);
        $this->db->bind(':dispense_quantity', $data['dispense_quantity']);
        $this->db->bind(':unit_type', $data['unit_type']);
        $this->db->bind(':diagnosis', $data['diagnosis']);
        $this->db->bind(':valid_until', $data['valid_until']);
        $this->db->bind(':pharmacy_note', $data['pharmacy_note']);
        $this->db->bind(':doctor_notes', $data['doctor_notes']);

        $result = $this->db->execute();
        
        if (!$result) {
            error_log('Prescription update failed: ' . print_r($data, true));
            error_log('SQL Error: ' . print_r($this->db->errorInfo(), true));
        }
        
        return $result;
    } catch (Throwable $e) {
        error_log('Prescription update error: ' . $e->getMessage());
        return false;
    }
}

public function softDeletePrescription($id, $doctor_id) {
    try {
        $this->db->query('
            UPDATE prescriptions 
            SET is_deleted = "deleted", updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND doctor_id = :doctor_id
        ');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':doctor_id', $doctor_id);
        
        return $this->db->execute();
    } catch (Throwable $e) {
        error_log('Prescription soft delete error: ' . $e->getMessage());
        return false;
    }
}
}
?>
