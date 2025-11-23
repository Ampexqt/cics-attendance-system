<?php
require_once __DIR__ . '/../database/Database.php';

class ReportsController {
    private $db;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * Get recent generated reports
     */
    public function getRecentReports($limit = 10) {
        try {
            // Check if table exists
            $checkTableQuery = "SHOW TABLES LIKE 'generated_reports'";
            $checkStmt = $this->db->query($checkTableQuery);
            
            if ($checkStmt->rowCount() === 0) {
                // Table doesn't exist yet - return empty array
                return [
                    'success' => true,
                    'reports' => []
                ];
            }
            
            $query = "SELECT 
                        gr.*,
                        u.email as generated_by_email
                      FROM generated_reports gr
                      LEFT JOIN users u ON gr.generated_by = u.id
                      ORDER BY gr.created_at DESC
                      LIMIT :limit";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'success' => true,
                'reports' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch reports: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get attendance summary data for report generation
     */
    public function getAttendanceSummaryData($filters) {
        try {
            $dateFrom = $filters['dateFrom'] ?? null;
            $dateTo = $filters['dateTo'] ?? null;
            $program = $filters['program'] ?? 'all';
            $yearLevel = $filters['yearLevel'] ?? 'all';
            $section = $filters['section'] ?? 'all';
            
            $query = "SELECT 
                        s.student_id,
                        s.first_name,
                        s.last_name,
                        s.program,
                        s.year_level,
                        s.section,
                        COUNT(ar.id) as total_records,
                        SUM(CASE WHEN ar.status = 'present' THEN 1 ELSE 0 END) as present_count,
                        SUM(CASE WHEN ar.status = 'late' THEN 1 ELSE 0 END) as late_count,
                        SUM(CASE WHEN ar.status = 'absent' THEN 1 ELSE 0 END) as absent_count
                      FROM students s
                      LEFT JOIN attendance_records ar ON s.id = ar.student_id
                      LEFT JOIN attendance_sessions asess ON ar.session_id = asess.id
                      WHERE 1=1";
            
            // Add filters
            $params = [];
            
            if ($dateFrom && $dateTo) {
                $query .= " AND asess.session_date BETWEEN :dateFrom AND :dateTo";
                $params[':dateFrom'] = $dateFrom;
                $params[':dateTo'] = $dateTo;
            }
            
            if ($program !== 'all') {
                $query .= " AND s.program = :program";
                $params[':program'] = $program;
            }
            
            if ($yearLevel !== 'all') {
                $query .= " AND s.year_level = :yearLevel";
                $params[':yearLevel'] = $yearLevel;
            }
            
            if ($section !== 'all') {
                $query .= " AND s.section = :section";
                $params[':section'] = $section;
            }
            
            $query .= " GROUP BY s.id ORDER BY s.last_name, s.first_name";
            
            $stmt = $this->db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch attendance data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get student registration data
     */
    public function getStudentRegistrationData($filters) {
        try {
            $dateFrom = $filters['dateFrom'] ?? null;
            $dateTo = $filters['dateTo'] ?? null;
            $program = $filters['program'] ?? 'all';
            $yearLevel = $filters['yearLevel'] ?? 'all';
            
            $query = "SELECT 
                        s.student_id,
                        s.first_name,
                        s.last_name,
                        s.program,
                        s.year_level,
                        s.section,
                        u.email,
                        u.status,
                        s.created_at as registration_date,
                        p.first_name as parent_first_name,
                        p.last_name as parent_last_name,
                        p.email as parent_email,
                        p.contact_number as parent_contact
                      FROM students s
                      LEFT JOIN users u ON s.user_id = u.id
                      LEFT JOIN parents p ON s.id = p.student_id
                      WHERE 1=1";
            
            $params = [];
            
            if ($dateFrom && $dateTo) {
                $query .= " AND DATE(s.created_at) BETWEEN :dateFrom AND :dateTo";
                $params[':dateFrom'] = $dateFrom;
                $params[':dateTo'] = $dateTo;
            }
            
            if ($program !== 'all') {
                $query .= " AND s.program = :program";
                $params[':program'] = $program;
            }
            
            if ($yearLevel !== 'all') {
                $query .= " AND s.year_level = :yearLevel";
                $params[':yearLevel'] = $yearLevel;
            }
            
            $query .= " ORDER BY s.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch student data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Save report metadata to database
     */
    public function saveReportMetadata($reportData, $userId) {
        try {
            $query = "INSERT INTO generated_reports 
                      (report_type, file_name, file_path, file_format, file_size, date_from, date_to, filters, generated_by)
                      VALUES 
                      (:report_type, :file_name, :file_path, :file_format, :file_size, :date_from, :date_to, :filters, :generated_by)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':report_type', $reportData['report_type']);
            $stmt->bindValue(':file_name', $reportData['file_name']);
            $stmt->bindValue(':file_path', $reportData['file_path']);
            $stmt->bindValue(':file_format', $reportData['file_format']);
            $stmt->bindValue(':file_size', $reportData['file_size']);
            $stmt->bindValue(':date_from', $reportData['date_from']);
            $stmt->bindValue(':date_to', $reportData['date_to']);
            $stmt->bindValue(':filters', json_encode($reportData['filters']));
            $stmt->bindValue(':generated_by', $userId);
            
            $stmt->execute();
            
            return [
                'success' => true,
                'report_id' => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to save report metadata: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get filter options for dropdowns
     */
    public function getFilterOptions() {
        try {
            // Get unique programs
            $programQuery = "SELECT DISTINCT program FROM students ORDER BY program";
            $programStmt = $this->db->query($programQuery);
            $programs = $programStmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get unique year levels
            $yearQuery = "SELECT DISTINCT year_level FROM students ORDER BY year_level";
            $yearStmt = $this->db->query($yearQuery);
            $years = $yearStmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get unique sections
            $sectionQuery = "SELECT DISTINCT section FROM students ORDER BY section";
            $sectionStmt = $this->db->query($sectionQuery);
            $sections = $sectionStmt->fetchAll(PDO::FETCH_COLUMN);
            
            return [
                'success' => true,
                'programs' => $programs,
                'year_levels' => $years,
                'sections' => $sections
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch filter options: ' . $e->getMessage()
            ];
        }
    }
}
