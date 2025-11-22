<?php
/**
 * Subject Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class Subject {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO subjects (code, name, instructor_id, program, year_level, section, schedule, room)
                VALUES (:code, :name, :instructor_id, :program, :year_level, :section, :schedule, :room)";

        $params = [
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':instructor_id' => $data['instructor_id'],
            ':program' => $data['program'],
            ':year_level' => $data['year_level'],
            ':section' => $data['section'],
            ':schedule' => $data['schedule'],
            ':room' => $data['room'] ?? null
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $sql = "SELECT s.*, 
                i.first_name as instructor_first_name, 
                i.last_name as instructor_last_name,
                CONCAT(i.first_name, ' ', i.last_name) as instructor_name
                FROM subjects s
                JOIN instructors i ON s.instructor_id = i.id
                WHERE s.id = :id
                LIMIT 1";
        $result = $this->db->fetchOne($sql, [':id' => $id]);
        
        // Schedule is now plain text, no JSON decoding needed
        
        return $result;
    }

    public function findByCode($code) {
        $sql = "SELECT * FROM subjects WHERE code = :code LIMIT 1";
        return $this->db->fetchOne($sql, [':code' => $code]);
    }

    public function getAll($filters = []) {
        $sql = "SELECT s.*, 
                i.first_name as instructor_first_name, 
                i.last_name as instructor_last_name,
                CONCAT(i.first_name, ' ', i.last_name) as instructor_name
                FROM subjects s
                JOIN instructors i ON s.instructor_id = i.id
                WHERE 1=1";
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

        if (!empty($filters['instructor_id'])) {
            $sql .= " AND s.instructor_id = :instructor_id";
            $params[':instructor_id'] = $filters['instructor_id'];
        }

        $sql .= " ORDER BY s.code, s.program, s.year_level, s.section";

        $results = $this->db->fetchAll($sql, $params);
        
        // Schedule is now plain text, no JSON decoding needed
        
        return $results;
    }

    public function update($id, $data) {
        $sql = "UPDATE subjects SET 
                code = :code,
                name = :name,
                instructor_id = :instructor_id,
                program = :program,
                year_level = :year_level,
                section = :section,
                schedule = :schedule,
                room = :room
                WHERE id = :id";

        $params = [
            ':id' => $id,
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':instructor_id' => $data['instructor_id'],
            ':program' => $data['program'],
            ':year_level' => $data['year_level'],
            ':section' => $data['section'],
            ':schedule' => $data['schedule'],
            ':room' => $data['room'] ?? null
        ];

        $this->db->query($sql, $params);
        return true;
    }

    public function delete($id) {
        $sql = "DELETE FROM subjects WHERE id = :id";
        $this->db->query($sql, [':id' => $id]);
        return true;
    }
}

