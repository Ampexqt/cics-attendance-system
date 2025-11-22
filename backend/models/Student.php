<?php
/**
 * Student Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class Student {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $sql = "INSERT INTO students (user_id, student_id, first_name, last_name, program, year_level, section) 
                VALUES (:user_id, :student_id, :first_name, :last_name, :program, :year_level, :section)";
        
        $params = [
            ':user_id' => $data['user_id'],
            ':student_id' => $data['student_id'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':program' => $data['program'],
            ':year_level' => $data['year_level'],
            ':section' => $data['section']
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function findByUserId($userId) {
        $sql = "SELECT s.*, u.email, u.status as user_status 
                FROM students s 
                JOIN users u ON s.user_id = u.id 
                WHERE s.user_id = :user_id LIMIT 1";
        return $this->db->fetchOne($sql, [':user_id' => $userId]);
    }
    
    public function findByStudentId($studentId) {
        $sql = "SELECT s.*, u.email, u.status as user_status 
                FROM students s 
                JOIN users u ON s.user_id = u.id 
                WHERE s.student_id = :student_id LIMIT 1";
        return $this->db->fetchOne($sql, [':student_id' => $studentId]);
    }
    
    public function findById($id) {
        $sql = "SELECT s.*, u.email, u.status as user_status 
                FROM students s 
                JOIN users u ON s.user_id = u.id 
                WHERE s.id = :id LIMIT 1";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT s.*, u.email, u.status as user_status 
                FROM students s 
                JOIN users u ON s.user_id = u.id 
                WHERE u.status != 'inactive'";
        $params = [];
        
        if (!empty($filters['program'])) {
            $sql .= " AND s.program = :program";
            $params[':program'] = $filters['program'];
        }
        
        if (!empty($filters['year_level'])) {
            $sql .= " AND s.year_level = :year_level";
            $params[':year_level'] = $filters['year_level'];
        }
        
        if (!empty($filters['section'])) {
            $sql .= " AND s.section = :section";
            $params[':section'] = $filters['section'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND u.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY s.last_name, s.first_name";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $sql = "UPDATE students SET " . implode(', ', $fields) . " WHERE id = :id";
        $this->db->query($sql, $params);
        return true;
    }
}

