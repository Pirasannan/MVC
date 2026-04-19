<?php
class Appointment {
    private $db;
    public function __construct(){ $this->db = new Database(); }

    private function tableExists($tableName){
        $this->db->query('SELECT COUNT(*) AS total FROM information_schema.tables WHERE table_schema = :db AND table_name = :table LIMIT 1');
        $this->db->bind(':db', DB_NAME);
        $this->db->bind(':table', $tableName);
        $result = $this->db->single();
        return isset($result->total) && (int)$result->total > 0;
    }

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
                      WHERE role = 'doctor' AND status = 'active' AND name LIKE :q
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
            SET starts_at = proposed_datetime,
                ends_at = DATE_ADD(proposed_datetime, INTERVAL 15 MINUTE),
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

public function findWithNames(int $id){
    $this->db->query(
        "SELECT a.*,
                u1.name AS patient_name,
                u2.name AS doctor_name
         FROM appointments a
         JOIN Users u1 ON u1.id = a.patient_id
         JOIN Users u2 ON u2.id = a.doctor_id
         WHERE a.id = :id
         LIMIT 1"
    );
    $this->db->bind(':id', $id);
    return $this->db->single();
}

public function getApprovedBetweenForDoctor(int $doctorId, string $startLocal, string $endLocal){
    $sql = "SELECT 
                a.id, a.patient_id, a.doctor_id, a.status, a.starts_at,
                u.name AS patient_name 
            FROM appointments a
            LEFT JOIN users u ON u.id = a.patient_id
            WHERE a.doctor_id = :doc
              AND a.status = 'approved'
              AND a.starts_at BETWEEN :start AND :end
            ORDER BY a.starts_at ASC";
    $this->db->query($sql);
    $this->db->bind(':doc',  $doctorId);
    $this->db->bind(':start', $startLocal);
    $this->db->bind(':end',   $endLocal);
    return $this->db->resultSet();
}

public function createReport(array $data): bool {
    if (!$this->tableExists('reports')) {
        return false;
    }

    $this->db->query("INSERT INTO reports
        (reporter_type, reporter_id, reported_type, reported_id, report_type, reason, description, status)
        VALUES
        (:reporter_type, :reporter_id, :reported_type, :reported_id, :report_type, :reason, :description, :status)");

    $this->db->bind(':reporter_type', $data['reporter_type']);
    $this->db->bind(':reporter_id', (int)$data['reporter_id']);
    $this->db->bind(':reported_type', $data['reported_type']);
    $this->db->bind(':reported_id', isset($data['reported_id']) ? (int)$data['reported_id'] : null);
    $this->db->bind(':report_type', $data['report_type']);
    $this->db->bind(':reason', $data['reason']);
    $this->db->bind(':description', $data['description'] ?? null);
    $this->db->bind(':status', $data['status'] ?? 'pending');

    return $this->db->execute();
}


}
?>
