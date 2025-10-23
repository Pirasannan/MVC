<?php 
class M_Messages {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    
    // Get all messages for a doctor
    public function getMessagesForDoctor($doctorId) {
        $this->db->query('
            SELECT m.*, 
                   u.name as sender_name,
                   u.email as sender_email
            FROM messages m 
            JOIN users u ON m.sender_id = u.id 
            WHERE (m.receiver_id = :doctor_id AND m.receiver_type = "doctor") 
               OR (m.sender_id = :doctor_id AND m.sender_type = "doctor")
            ORDER BY m.created_at DESC
        ');
        $this->db->bind(':doctor_id', $doctorId);
        return $this->db->resultSet();
    }
    
    // Get conversation between doctor and patient
    public function getConversation($doctorId, $patientId) {
        $this->db->query('
            SELECT m.*, 
                   u.name as sender_name,
                   u.email as sender_email
            FROM messages m 
            JOIN users u ON m.sender_id = u.id 
            WHERE (m.sender_id = :doctor_id AND m.receiver_id = :patient_id) 
               OR (m.sender_id = :patient_id AND m.receiver_id = :doctor_id)
            ORDER BY m.created_at ASC
        ');
        $this->db->bind(':doctor_id', $doctorId);
        $this->db->bind(':patient_id', $patientId);
        return $this->db->resultSet();
    }
    
    // Get unread message count for doctor
    public function getUnreadCount($doctorId) {
        $this->db->query('
            SELECT COUNT(*) as unread_count 
            FROM messages 
            WHERE receiver_id = :doctor_id 
              AND receiver_type = "doctor" 
              AND is_read = FALSE
        ');
        $this->db->bind(':doctor_id', $doctorId);
        $result = $this->db->single();
        return $result ? $result->unread_count : 0;
    }
    
    // Get recent conversations (grouped by patient)
    public function getRecentConversations($doctorId) {
        $this->db->query('
            SELECT 
                CASE 
                    WHEN m.sender_id = :doctor_id THEN m.receiver_id 
                    ELSE m.sender_id 
                END as patient_id,
                u.name as patient_name,
                u.email as patient_email,
                m.message_text as last_message,
                m.created_at as last_message_time,
                m.is_read,
                m.sender_type,
                COUNT(CASE WHEN m.receiver_id = :doctor_id AND m.is_read = FALSE THEN 1 END) as unread_count
            FROM messages m
            JOIN users u ON (
                CASE 
                    WHEN m.sender_id = :doctor_id THEN m.receiver_id = u.id
                    ELSE m.sender_id = u.id
                END
            )
            WHERE (m.sender_id = :doctor_id OR m.receiver_id = :doctor_id)
              AND u.role = "patient"
            GROUP BY patient_id, u.name, u.email
            HAVING MAX(m.created_at)
            ORDER BY MAX(m.created_at) DESC
            LIMIT 10
        ');
        $this->db->bind(':doctor_id', $doctorId);
        return $this->db->resultSet();
    }
    
    // Send a new message
    public function sendMessage($data) {
        $this->db->query('
            INSERT INTO messages (sender_id, receiver_id, sender_type, receiver_type, subject, message_text) 
            VALUES (:sender_id, :receiver_id, :sender_type, :receiver_type, :subject, :message_text)
        ');
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':sender_type', $data['sender_type']);
        $this->db->bind(':receiver_type', $data['receiver_type']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message_text', $data['message_text']);
        
        return $this->db->execute();
    }
    
    // Mark message as read
    public function markAsRead($messageId, $userId) {
        $this->db->query('
            UPDATE messages 
            SET is_read = TRUE 
            WHERE id = :message_id AND receiver_id = :user_id
        ');
        $this->db->bind(':message_id', $messageId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Mark all messages from a patient as read
    public function markConversationAsRead($doctorId, $patientId) {
        $this->db->query('
            UPDATE messages 
            SET is_read = TRUE 
            WHERE receiver_id = :doctor_id 
              AND sender_id = :patient_id 
              AND is_read = FALSE
        ');
        $this->db->bind(':doctor_id', $doctorId);
        $this->db->bind(':patient_id', $patientId);
        
        return $this->db->execute();
    }
    
    // Get all patients for doctor to send messages to
    public function getAllPatients() {
        $this->db->query('SELECT id, name, email FROM users WHERE role = "patient" ORDER BY name');
        return $this->db->resultSet();
    }
}
?>