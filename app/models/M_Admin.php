<?php
class M_Admin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    private function tableExists($tableName)
    {
        $this->db->query('SELECT COUNT(*) AS total FROM information_schema.tables WHERE table_schema = :db AND table_name = :table LIMIT 1');
        $this->db->bind(':db', DB_NAME);
        $this->db->bind(':table', $tableName);
        $result = $this->db->single();
        return isset($result->total) && (int)$result->total > 0;
    }

    // ==================== DASHBOARD STATS ====================

    public function getDashboardStats()
    {
        $stats = [];

        // Total Doctors (Users with role Doctor and active status)
        $this->db->query('SELECT COUNT(*) as total FROM Users WHERE role = :role AND status = :status');
        $this->db->bind(':role', 'Doctor');
        $this->db->bind(':status', 'active');
        $stats['total_doctors'] = $this->db->single()->total;

        // Total Patients (Users with role Patient and active status)
        $this->db->query('SELECT COUNT(*) as total FROM Users WHERE role = :role AND status = :status');
        $this->db->bind(':role', 'Patient');
        $this->db->bind(':status', 'active');
        $stats['total_patients'] = $this->db->single()->total;

        // Consultations Last Week (guard if table not present)
        if ($this->tableExists('consultations')) {
            $this->db->query('SELECT COUNT(*) as total FROM consultations 
							WHERE consultation_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
							AND status = :status');
            $this->db->bind(':status', 'completed');
            $stats['consultations_last_week'] = $this->db->single()->total;
        } else {
            $stats['consultations_last_week'] = 0;
        }

        return $stats;
    }

    // ==================== DASHBOARD - PENDING VERIFICATIONS ====================

    public function getPendingDoctorsForDashboard()
    {
        $this->db->query('SELECT id, name, created_at as submitted_date
						FROM Users 
						WHERE role = :role AND status = :status 
						ORDER BY created_at DESC 
						LIMIT 3');
        $this->db->bind(':role', 'Doctor');
        $this->db->bind(':status', 'inactive');
        return $this->db->resultSet();
    }

    public function getPendingPatientsForDashboard()
    {
        $this->db->query('SELECT id, name, email, created_at as submitted_date
						FROM Users 
						WHERE role = :role AND status = :status 
						ORDER BY created_at DESC 
						LIMIT 3');
        $this->db->bind(':role', 'Patient');
        $this->db->bind(':status', 'inactive');
        return $this->db->resultSet();
    }

    // ==================== DASHBOARD - RECENT ACTIVITY ====================

    public function getRecentActivityForDashboard()
    {
        // $this->db->query('SELECT * FROM activity_logs 
        //                  ORDER BY created_at DESC 
        //                  LIMIT 3');
        // return $this->db->resultSet();
    }

    // ==================== DOCTOR MANAGEMENT ====================

    public function getPendingDoctors()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status = :status ORDER BY created_at DESC');
        $this->db->bind(':role', 'Doctor');
        $this->db->bind(':status', 'inactive');
        return $this->db->resultSet();
    }

    public function getUnverifiedDoctors()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status = :status ORDER BY created_at DESC');
        $this->db->bind(':role', 'Doctor');
        $this->db->bind(':status', 'unverified');
        return $this->db->resultSet();
    }

    public function getVerifiedDoctors()
    {
        $this->db->query('SELECT u.*
                         FROM Users u
                         INNER JOIN doctor_verifications dv ON dv.user_id = u.id
                         WHERE u.role = :role
                         AND u.status = :status
                         AND dv.verification_status = :verification_status
                         ORDER BY u.updated_at DESC');
        $this->db->bind(':role', 'Doctor');
        $this->db->bind(':status', 'active');
        $this->db->bind(':verification_status', 'verified');
        return $this->db->resultSet();
    }

    public function getRejectedDoctors()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status = :status ORDER BY updated_at DESC');
        $this->db->bind(':role', 'Doctor');
        $this->db->bind(':status', 'suspended');
        return $this->db->resultSet();
    }

    public function getInactiveDoctors()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status IN (:inactive, :suspended) ORDER BY updated_at DESC');
        $this->db->bind(':role', 'Doctor');
        $this->db->bind(':inactive', 'inactive');
        $this->db->bind(':suspended', 'suspended');
        return $this->db->resultSet();
    }

    public function approveDoctor($doctor_id)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'active');
        $this->db->bind(':id', $doctor_id);
        return $this->db->execute();
    }

    public function rejectDoctor($doctor_id, $reason)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'suspended');
        $this->db->bind(':id', $doctor_id);
        return $this->db->execute();
    }

    public function suspendDoctor($doctor_id)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'suspended');
        $this->db->bind(':id', $doctor_id);
        return $this->db->execute();
    }

    public function deactivateDoctor($doctor_id)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'inactive');
        $this->db->bind(':id', $doctor_id);
        return $this->db->execute();
    }

    public function reactivateDoctor($doctor_id)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'active');
        $this->db->bind(':id', $doctor_id);
        return $this->db->execute();
    }

    // ==================== PATIENT MANAGEMENT ====================

    public function getPendingPatients()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status = :status ORDER BY created_at DESC');
        $this->db->bind(':role', 'Patient');
        $this->db->bind(':status', 'inactive');
        return $this->db->resultSet();
    }

    public function getVerifiedPatients()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status = :status ORDER BY updated_at DESC');
        $this->db->bind(':role', 'Patient');
        $this->db->bind(':status', 'active');
        return $this->db->resultSet();
    }

    public function getRejectedPatients()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status = :status ORDER BY updated_at DESC');
        $this->db->bind(':role', 'Patient');
        $this->db->bind(':status', 'suspended');
        return $this->db->resultSet();
    }

    public function getInactivePatients()
    {
        $this->db->query('SELECT * FROM Users WHERE role = :role AND status IN (:inactive, :suspended) ORDER BY updated_at DESC');
        $this->db->bind(':role', 'Patient');
        $this->db->bind(':inactive', 'inactive');
        $this->db->bind(':suspended', 'suspended');
        return $this->db->resultSet();
    }

    public function approvePatient($patient_id)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'active');
        $this->db->bind(':id', $patient_id);
        return $this->db->execute();
    }

    public function rejectPatient($patient_id, $reason)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'suspended');
        $this->db->bind(':id', $patient_id);
        return $this->db->execute();
    }

    public function deactivatePatient($patient_id)
    {
        $this->db->query('UPDATE Users SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', 'inactive');
        $this->db->bind(':id', $patient_id);
        return $this->db->execute();
    }

    // ==================== PROFILE & SECURITY MANAGEMENT ====================

    public function getAdminProfile($admin_id)
    {
        $this->db->query('SELECT * FROM Users WHERE id = :admin_id AND LOWER(role) = :role LIMIT 1');
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':role', 'admin');
        return $this->db->single();
    }

    public function getActiveSessions($admin_id)
    {
        // For now, return mock data since we don't have a sessions table
        return [
            (object)[
                'id' => 1,
                'device' => 'Chrome on Windows',
                'ip_address' => '192.168.1.100',
                'last_activity' => '2025-10-18 09:30:00',
                'is_current' => true
            ],
            (object)[
                'id' => 2,
                'device' => 'Safari on iPhone',
                'ip_address' => '192.168.1.101',
                'last_activity' => '2025-10-17 15:45:00',
                'is_current' => false
            ]
        ];
    }

    public function getSecurityOverview($admin_id)
    {
        // For now, return mock data
        return (object)[
            'last_password_change' => '2025-09-18',
            'two_factor_enabled' => false,
            'login_attempts' => 0,
            'last_login' => '2025-10-18 09:30:00',
            'ip_address' => '192.168.1.100'
        ];
    }

    public function getAdminActivityLog($admin_id)
    {
        if (!$this->tableExists('activity_logs')) {
            return [];
        }

        $this->db->query('SELECT id,
                                 action,
                                 COALESCE(description, "") AS details,
                                 created_at AS timestamp,
                                 "completed" AS status
                          FROM activity_logs
                          WHERE admin_id = :admin_id OR user_id = :admin_id
                          ORDER BY created_at DESC
                          LIMIT 10');
        $this->db->bind(':admin_id', $admin_id);

        return $this->db->resultSet();
    }

    public function getSystemActivityLog()
    {
        // For now, return mock data
        return [
            (object)[
                'id' => 1,
                'action' => 'New Clinic Registration',
                'details' => 'City Care Medical Center - Colombo 7',
                'timestamp' => '2025-10-18 07:00:00',
                'status' => 'pending'
            ],
            (object)[
                'id' => 2,
                'action' => 'Doctor Account Approved',
                'details' => 'Dr. Sunil Jayawardena verified and activated',
                'timestamp' => '2025-10-18 04:00:00',
                'status' => 'completed'
            ],
            (object)[
                'id' => 3,
                'action' => 'Patient Flagged Content',
                'details' => 'Inappropriate message reported in consultation chat',
                'timestamp' => '2025-10-17 00:00:00',
                'status' => 'under_review'
            ]
        ];
    }

    public function getLoginLogs($limit = 50)
    {
        if (!$this->tableExists('activity_logs')) {
            return [];
        }

        $limit = (int)$limit;
        $this->db->query('SELECT al.id, al.action, al.description, al.ip_address, al.user_agent, al.created_at,
                          u.name AS user_name, u.email AS user_email, u.role AS user_role
                          FROM activity_logs al
                          LEFT JOIN Users u ON al.user_id = u.id
                          WHERE al.action IN ("login_success", "login_failed")
                          ORDER BY al.created_at DESC
                          LIMIT ' . $limit);
        return $this->db->resultSet();
    }

    // ==================== NOTIFICATIONS & REPORTS ====================

    private function getReportsByTypeAndStatuses(array $types, array $statuses, $orderBy = 'r.created_at DESC')
    {
        if (!$this->tableExists('reports')) {
            return [];
        }

        $typePlaceholders = [];
        $statusPlaceholders = [];

        foreach ($types as $i => $type) {
            $key = ':type' . $i;
            $typePlaceholders[] = $key;
        }

        foreach ($statuses as $i => $status) {
            $key = ':status' . $i;
            $statusPlaceholders[] = $key;
        }

        $sql = 'SELECT r.*, 
                reporter.name as reporter_name,
                reported.name as reported_name,
                resolver.name as resolver_name
                FROM reports r
                LEFT JOIN Users reporter ON r.reporter_id = reporter.id
                LEFT JOIN Users reported ON r.reported_id = reported.id
                LEFT JOIN Users resolver ON r.resolved_by = resolver.id
                WHERE r.report_type IN (' . implode(', ', $typePlaceholders) . ')
                AND r.status IN (' . implode(', ', $statusPlaceholders) . ')
                ORDER BY ' . $orderBy;

        $this->db->query($sql);

        foreach ($types as $i => $type) {
            $this->db->bind(':type' . $i, $type);
        }

        foreach ($statuses as $i => $status) {
            $this->db->bind(':status' . $i, $status);
        }

        return $this->db->resultSet();
    }

    public function getReportedMessages()
    {
        return $this->getReportsByTypeAndStatuses(
            ['Inappropriate Content', 'Spam Messages', 'Harassment', 'Fraudulent Activity', 'Technical Issue'],
            ['pending', 'under_review'],
            'r.created_at DESC'
        );
    }

    public function getPendingCallReports()
    {
        return $this->getReportsByTypeAndStatuses(['Call Report'], ['pending', 'under_review'], 'r.created_at DESC');
    }

    public function getPendingUserReports()
    {
        return $this->getReportsByTypeAndStatuses(['User Report'], ['pending', 'under_review'], 'r.created_at DESC');
    }

    public function getResolvedReports()
    {
        if (!$this->tableExists('reports')) {
            return [];
        }

        $this->db->query('SELECT r.*, 
                         reporter.name as reporter_name,
                         reported.name as reported_name,
                         resolver.name as resolver_name
                         FROM reports r
                         LEFT JOIN Users reporter ON r.reporter_id = reporter.id
                         LEFT JOIN Users reported ON r.reported_id = reported.id
                         LEFT JOIN Users resolver ON r.resolved_by = resolver.id
                         WHERE r.status = :status
                         ORDER BY r.resolved_at DESC');
        $this->db->bind(':status', 'resolved');
        return $this->db->resultSet();
    }

    public function resolveReport($reportId, $adminId, $resolution)
    {
        if (!$this->tableExists('reports')) {
            return false;
        }

        $this->db->query('UPDATE reports
                         SET status = :status,
                             resolution = :resolution,
                             resolved_by = :resolved_by,
                             resolved_at = NOW()
                         WHERE id = :id
                         AND status IN (:pending, :under_review)');
        $this->db->bind(':status', 'resolved');
        $this->db->bind(':resolution', $resolution);
        $this->db->bind(':resolved_by', $adminId);
        $this->db->bind(':id', $reportId);
        $this->db->bind(':pending', 'pending');
        $this->db->bind(':under_review', 'under_review');

        $ok = $this->db->execute();
        if (!$ok) {
            return false;
        }

        return $this->db->rowCount() > 0;
    }

    public function sendSystemNotification($data)
    {
        $this->db->query('INSERT INTO notifications (recipient_type, title, message, created_at) 
                         VALUES (:recipient_type, :title, :message, NOW())');
        $this->db->bind(':recipient_type', $data['recipient_type']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);
        return $this->db->execute();
    }

    // ==================== MEDICAL RECORDS ====================

    public function getRecentConsultations()
    {
        if (!$this->tableExists('consultations')) {
            return [];
        }
        // Under current schema, related tables may not exist; return empty for safety
        return [];
    }

    public function getRecentPrescriptions()
    {
        if (!$this->tableExists('prescriptions')) {
            return [];
        }
        return [];
    }

    public function getTestReferrals()
    {
        if (!$this->tableExists('test_referrals')) {
            return [];
        }
        return [];
    }

    public function getPatientMedicalHistory($patient_id)
    {
        if (!$this->tableExists('medical_history')) {
            return [];
        }
        return [];
    }

    // ==================== ACTIVITY LOGS ====================

    public function getRecentSystemActivity()
    {
        if (!$this->tableExists('activity_logs')) {
            return [];
        }

        $this->db->query('SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10');
        return $this->db->resultSet();
    }

    public function logAdminAction($admin_id, $action, $description)
    {
        if (!$this->tableExists('activity_logs')) {
            return false;
        }

        $this->db->query('INSERT INTO activity_logs (admin_id, action, description, created_at) 
                         VALUES (:admin_id, :action, :description, NOW())');
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':action', $action);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    // ==================== ADMIN PROFILE ====================

    public function getAdminById($id)
    {
        $this->db->query('SELECT * FROM admins WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateAdminProfile($data)
    {
        $this->db->query('UPDATE admins SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function updateAdminPassword($admin_id, $hashed_password)
    {
        $this->db->query('UPDATE admins SET password = :password WHERE id = :id');
        $this->db->bind(':password', $hashed_password);
        $this->db->bind(':id', $admin_id);
        return $this->db->execute();
    }

    // ==================== NOTIFICATIONS ====================

    public function createNotification($data)
    {
        $this->db->query('INSERT INTO notifications (recipient_type, recipient_id, title, message, notification_type, status, created_at) 
                         VALUES (:recipient_type, :recipient_id, :title, :message, :notification_type, :status, NOW())');
        $this->db->bind(':recipient_type', $data['recipient_type']);
        $this->db->bind(':recipient_id', $data['recipient_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':notification_type', $data['notification_type'] ?? 'info');
        $this->db->bind(':status', 'sent');
        return $this->db->execute();
    }

    public function getRecentNotifications($limit = 10)
    {
        $this->db->query('SELECT * FROM notifications 
                         ORDER BY created_at DESC 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getAllNotifications()
    {
        $this->db->query('SELECT * FROM notifications 
                         ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getNotificationsByRecipient($recipient_type, $recipient_id = null)
    {
        if ($recipient_id) {
            $this->db->query('SELECT * FROM notifications 
                             WHERE recipient_type = :recipient_type AND recipient_id = :recipient_id
                             ORDER BY created_at DESC');
            $this->db->bind(':recipient_type', $recipient_type);
            $this->db->bind(':recipient_id', $recipient_id);
        } else {
            $this->db->query('SELECT * FROM notifications 
                             WHERE recipient_type = :recipient_type
                             ORDER BY created_at DESC');
            $this->db->bind(':recipient_type', $recipient_type);
        }
        return $this->db->resultSet();
    }

    public function getLatestNotificationForUser($recipientRole, $recipientId)
    {
        if (!$this->tableExists('notifications')) {
            return null;
        }

        $this->db->query('SELECT * FROM notifications
                         WHERE (recipient_type = :all_type AND recipient_id IS NULL)
                            OR (recipient_type = :recipient_type AND (recipient_id IS NULL OR recipient_id = :recipient_id))
                         ORDER BY created_at DESC, id DESC
                         LIMIT 1');
        $this->db->bind(':all_type', 'all');
        $this->db->bind(':recipient_type', $recipientRole);
        $this->db->bind(':recipient_id', (int)$recipientId);
        return $this->db->single();
    }
}
