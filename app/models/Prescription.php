<?php
    class Prescription{
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function addPrescription($data){
            try {
            $this->db->query ('INSERT INTO prescriptions 
            (doctor_id, 
            drug_name, 
            formulation, 
            route, 
            brand_substitution, 
            prn, 
            max_per_24h, 
            prn_indication,
            dose_amount, 
            dose_unit, 
            frequency, 
            custom_frequency, 
            time_of_day, 
            meal_relation,
            duration_value, 
            duration_type, 
            special_instructions, 
            dispense_quantity, 
            unit_type,
            diagnosis, 
            valid_until, 
            pharmacy_note, 
            doctor_notes)

            VALUES 
            (:doctor_id, 
            :drug_name, 
            :formulation, 
            :route, 
            :brand_substitution, 
            :prn, 
            :max_per_24h, 
            :prn_indication,
            :dose_amount, 
            :dose_unit, 
            :frequency, 
            :custom_frequency, 
            :time_of_day, 
            :meal_relation,
            :duration_value, 
            :duration_type, 
            :special_instructions, 
            :dispense_quantity, 
            :unit_type,
            :diagnosis, 
            :valid_until, 
            :pharmacy_note, 
            :doctor_notes)');



            foreach ($data as $key => $value) {
                $this->db->bind(':' . $key, $value);
            }

            return $this->db->execute();
            } catch (Throwable $e) {
                error_log('Prescription insert failed: ' . $e->getMessage());
                return false;
            }

        }

    }
?>