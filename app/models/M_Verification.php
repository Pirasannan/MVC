<?php 
/**
 * M_Verification Model
 * Handles all database operations for doctor profile verification
 */
class M_Verification {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    
    /**
     * Get verification record by user ID
     * @param int $user_id
     * @return object|false
     */
    public function getVerificationByUserId($user_id){
        $this->db->query('SELECT * FROM doctor_verifications WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $row = $this->db->single();
        
        if($this->db->rowCount() > 0){
            return $row;
        }
        return false;
    }
    
    /**
     * Create new verification record
     * @param array $data
     * @return bool
     */
    public function createVerification($data){
        $this->db->query('INSERT INTO doctor_verifications (user_id, email, photo_path, verification_status) 
                         VALUES (:user_id, :email, :photo_path, :verification_status)');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':photo_path', $data['photo_path']);
        $this->db->bind(':verification_status', $data['verification_status'] ?? 'pending');
        
        if($this->db->execute()){
            return true;
        }
        return false;
    }
    
    /**
     * Update verification record
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateVerification($id, $data){
        $sql = 'UPDATE doctor_verifications SET ';
        $fields = [];
        $binds = [];
        
        // Build dynamic update query based on provided data
        if(isset($data['photo_path'])){
            $fields[] = 'photo_path = :photo_path';
            $binds[':photo_path'] = $data['photo_path'];
        }
        
        if(isset($data['verification_status'])){
            $fields[] = 'verification_status = :verification_status';
            $binds[':verification_status'] = $data['verification_status'];
        }
        
        if(isset($data['rejection_reason'])){
            $fields[] = 'rejection_reason = :rejection_reason';
            $binds[':rejection_reason'] = $data['rejection_reason'];
        }
        
        if(isset($data['verified_at'])){
            $fields[] = 'verified_at = :verified_at';
            $binds[':verified_at'] = $data['verified_at'];
        }
        
        if(empty($fields)){
            return false; // No fields to update
        }
        
        $sql .= implode(', ', $fields) . ' WHERE id = :id';
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        
        foreach($binds as $param => $value){
            $this->db->bind($param, $value);
        }
        
        return $this->db->execute();
    }
    
    /**
     * Delete verification record
     * @param int $id
     * @return bool
     */
    public function deleteVerification($id){
        $this->db->query('DELETE FROM doctor_verifications WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Delete verification by user ID
     * @param int $user_id
     * @return bool
     */
    public function deleteVerificationByUserId($user_id){
        $this->db->query('DELETE FROM doctor_verifications WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->execute();
    }
    
    /**
     * Update verification status
     * @param int $id
     * @param string $status
     * @param string|null $reason
     * @return bool
     */
    public function updateStatus($id, $status, $reason = null){
        $data = [
            'verification_status' => $status
        ];
        
        if($status === 'verified' || $status === 'rejected'){
            $data['verified_at'] = date('Y-m-d H:i:s');
        }
        
        if($reason !== null){
            $data['rejection_reason'] = $reason;
        }
        
        return $this->updateVerification($id, $data);
    }
    
    /**
     * Check if user has verification record
     * @param int $user_id
     * @return bool
     */
    public function hasVerification($user_id){
        $this->db->query('SELECT id FROM doctor_verifications WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $this->db->single();
        
        return $this->db->rowCount() > 0;
    }
    
    /**
     * Get verification status by user ID
     * @param int $user_id
     * @return string|false
     */
    public function getVerificationStatus($user_id){
        $this->db->query('SELECT verification_status FROM doctor_verifications WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $row = $this->db->single();
        
        if($row){
            return $row->verification_status;
        }
        return false;
    }
    
    /**
     * Get all verifications (for admin use)
     * @param string|null $status Filter by status
     * @return array
     */
    public function getAllVerifications($status = null){
        if($status){
            $this->db->query('SELECT dv.*, u.name as user_name 
                             FROM doctor_verifications dv 
                             JOIN Users u ON dv.user_id = u.id 
                             WHERE dv.verification_status = :status 
                             ORDER BY dv.uploaded_at DESC');
            $this->db->bind(':status', $status);
        } else {
            $this->db->query('SELECT dv.*, u.name as user_name 
                             FROM doctor_verifications dv 
                             JOIN Users u ON dv.user_id = u.id 
                             ORDER BY dv.uploaded_at DESC');
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get pending doctor verifications for admin review.
     * Returns only doctors who have uploaded a document and are pending review.
     * @return array
     */
    public function getPendingDoctorVerificationsForAdmin(){
        $this->db->query('SELECT dv.*, u.name as user_name, u.email as user_email, u.status as account_status
                         FROM doctor_verifications dv
                         INNER JOIN Users u ON dv.user_id = u.id
                         WHERE dv.verification_status = :status
                         AND LOWER(u.role) = :role
                         ORDER BY dv.uploaded_at DESC');
        $this->db->bind(':status', 'pending');
        $this->db->bind(':role', 'doctor');

        return $this->db->resultSet();
    }
}
?>