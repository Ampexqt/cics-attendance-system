<?php
/**
 * Instructor Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class Instructor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO instructors (user_id, first_name, last_name, department, employee_id)
                VALUES (:user_id, :first_name, :last_name, :department, :employee_id)";

        $params = [
            ':user_id' => $data['user_id'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':department' => $data['department'],
            ':employee_id' => $data['employee_id'] ?? null
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $sql = "SELECT i.*, u.email, u.status as user_status
                FROM instructors i
                JOIN users u ON i.user_id = u.id
                WHERE i.id = :id
                LIMIT 1";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    public function getAll($filters = []) {
        $sql = "SELECT i.*, u.email, u.status as user_status
                FROM instructors i
                JOIN users u ON i.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['department'])) {
            $sql .= " AND i.department = :department";
            $params[':department'] = $filters['department'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND u.status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY i.last_name, i.first_name";

        return $this->db->fetchAll($sql, $params);
    }
}


