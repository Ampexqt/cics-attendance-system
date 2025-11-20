<?php
/**
 * User Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $sql = "INSERT INTO users (email, password, role, status, device_fingerprint) 
                VALUES (:email, :password, :role, :status, :device_fingerprint)";
        
        $params = [
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'],
            ':status' => $data['status'] ?? 'pending',
            ':device_fingerprint' => $data['device_fingerprint'] ?? null
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        return $this->db->fetchOne($sql, [':email' => $email]);
    }
    
    public function findByEmailOrStudentId($emailOrStudentId) {
        // First try to find by email
        $user = $this->findByEmail($emailOrStudentId);
        if ($user) {
            return $user;
        }
        
        // If not found by email, try to find by student ID
        $sql = "SELECT u.* FROM users u 
                JOIN students s ON u.id = s.user_id 
                WHERE s.student_id = :student_id LIMIT 1";
        return $this->db->fetchOne($sql, [':student_id' => $emailOrStudentId]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $this->db->query($sql, $params);
        return true;
    }
    
    public function updateLastLogin($id) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
        $this->db->query($sql, [':id' => $id]);
    }
    
    public function updateDeviceFingerprint($id, $fingerprint) {
        $sql = "UPDATE users SET device_fingerprint = :fingerprint WHERE id = :id";
        $this->db->query($sql, [
            ':fingerprint' => $fingerprint,
            ':id' => $id
        ]);
    }
    
    public function getAll($role = null, $status = null) {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        
        if ($role) {
            $sql .= " AND role = :role";
            $params[':role'] = $role;
        }
        
        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
}

