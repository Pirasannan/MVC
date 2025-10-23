<?php
class Appointment {
    private $db;
    public function __construct(){ $this->db = new Database(); }

    public function create($data){
        $this->db->query("INSERT INTO appointments
            (patient_id, doctor_id, slot_id, starts_at, ends_at, status, reason, notes, video_room_token)
            VALUES (:patient_id, :doctor_id, :slot_id, :starts_at, :ends_at, 'pending', :reason, :notes, :token)");
        $this->db->bind(':patient_id', $data['patient_id']);
        $this->db->bind(':doctor_id', $data['doctor_id']);
        $this->db->bind(':slot_id', $data['slot_id']);
        $this->db->bind(':starts_at', $data['starts_at']);
        $this->db->bind(':ends_at', $data['ends_at']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':notes', $data['notes'] ?? null);
        $this->db->bind(':token', $data['token'] ?? null);
        return $this->db->execute();
    }

    public function updateStatus($id, $status){
        $allowed = ['pending','approved','rejected','cancelled','completed'];
        if(!in_array($status, $allowed, true)) return false;
        $this->db->query("UPDATE appointments SET status=:s WHERE id=:id");
        $this->db->bind(':s', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getByDoctor($doctor_id, $status=null){
        $sql = "SELECT a.*, u1.name AS patient_name, u2.name AS doctor_name
                FROM appointments a
                JOIN Users u1 ON u1.id = a.patient_id
                JOIN Users u2 ON u2.id = a.doctor_id
                WHERE a.doctor_id = :doctor_id";
        if($status){ $sql .= " AND a.status = :status"; }
        $sql .= " ORDER BY a.starts_at DESC";
        $this->db->query($sql);
        $this->db->bind(':doctor_id', $doctor_id);
        if($status){ $this->db->bind(':status', $status); }
        return $this->db->resultSet();
    }

    public function getByPatient($patient_id){
        $this->db->query("SELECT a.*, u2.name AS doctor_name
                          FROM appointments a
                          JOIN Users u2 ON u2.id = a.doctor_id
                          WHERE a.patient_id = :pid
                          ORDER BY a.starts_at DESC");
        $this->db->bind(':pid', $patient_id);
        return $this->db->resultSet();
    }

    public function find($id){
        $this->db->query("SELECT * FROM appointments WHERE id=:id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

public function request($data){
    $this->db->query("INSERT INTO appointments
        (patient_id, doctor_id, starts_at, ends_at, status, reason, notes, video_room_token)
        VALUES (:patient_id, :doctor_id, :starts_at, :ends_at, 'pending', :reason, :notes, :token)");
    $this->db->bind(':patient_id', $data['patient_id']);
    $this->db->bind(':doctor_id',  $data['doctor_id']);
    $this->db->bind(':starts_at',  $data['starts_at']);
    $this->db->bind(':ends_at',    $data['ends_at']);
    $this->db->bind(':reason',     $data['reason'] ?? null);
    $this->db->bind(':notes',      $data['notes']  ?? null);
    $this->db->bind(':token',      $data['token']  ?? null);
    return $this->db->execute();
}

public function approve($id){
    try{
        $this->db->query("UPDATE appointments SET status='approved' WHERE id=:id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    } catch (Exception $e){
        return false;
    }
}

public function setStatus($id, $status){
    $allowed = ['pending','approved','rejected','cancelled','completed'];
    if(!in_array($status, $allowed, true)) return false;
    $this->db->query("UPDATE appointments SET status=:s WHERE id=:id");
    $this->db->bind(':s', $status);
    $this->db->bind(':id', $id);
    return $this->db->execute();
}

public function searchDoctorsByName($term){
    $this->db->query("SELECT id, name
                      FROM Users
                      WHERE role = 'doctor' AND name LIKE :q
                      ORDER BY name ASC
                      LIMIT 10");
    $like = '%'.$term.'%';
    $this->db->bind(':q', $like);
    return $this->db->resultSet();
}

public function proposeRescheduleByDoctor(int $appointmentId, int $doctorId, string $newDatetime, ?string $message): bool {
    $sql = "UPDATE appointments
            SET proposed_datetime = :newdt,
                proposed_by = 'doctor',
                reschedule_status = 'pending_patient',
                reschedule_message = :msg,
                reschedule_expires_at = DATE_ADD(NOW(), INTERVAL 48 HOUR)
            WHERE id = :id AND doctor_id = :doc AND status IN ('pending')";
    
    $this->db->query($sql);
    $this->db->bind(':newdt', $newDatetime);
    $this->db->bind(':msg', $message);
    $this->db->bind(':id', $appointmentId);
    $this->db->bind(':doc', $doctorId);
    return $this->db->execute();
}

public function patientAcceptReschedule(int $appointmentId, int $patientId): bool {
    $sql = "UPDATE appointments
            SET scheduled_at = proposed_datetime,
                status = 'approved',
                proposed_datetime = NULL,
                proposed_by = NULL,
                reschedule_status = 'accepted',
                reschedule_message = NULL,
                reschedule_expires_at = NULL
            WHERE id = :id AND patient_id = :pid AND reschedule_status = 'pending_patient'";
    
    $this->db->query($sql);
    $this->db->bind(':id', $appointmentId);
    $this->db->bind(':pid', $patientId);
    return $this->db->execute();
}

public function patientDeclineReschedule(int $appointmentId, int $patientId): bool {
    $sql = "UPDATE appointments
            SET proposed_datetime = NULL,
                proposed_by = NULL,
                reschedule_status = 'declined',
                reschedule_message = NULL,
                reschedule_expires_at = NULL
            WHERE id = :id AND patient_id = :pid AND reschedule_status = 'pending_patient'";
    
    $this->db->query($sql);
    $this->db->bind(':id', $appointmentId);
    $this->db->bind(':pid', $patientId);
    return $this->db->execute();
}

public function expireStaleReschedules(): int {
    $sql = "UPDATE appointments
            SET proposed_datetime = NULL,
                proposed_by = NULL,
                reschedule_status = 'none',
                reschedule_message = NULL,
                reschedule_expires_at = NULL
            WHERE reschedule_status='pending_patient' AND reschedule_expires_at < NOW()";
    
    $this->db->query($sql);
    $this->db->execute();
    return $this->db->rowCount();
}


}
?>
