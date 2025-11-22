<?php

/**
 * Settings Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class Settings
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get a single setting value by key
     */
    public function get($key)
    {
        $sql = "SELECT value, type FROM settings WHERE `key` = :key LIMIT 1";
        $result = $this->db->fetchOne($sql, [':key' => $key]);

        if (!$result) {
            return null;
        }

        return $this->castValue($result['value'], $result['type']);
    }

    /**
     * Get multiple settings by keys
     */
    public function getMultiple($keys)
    {
        if (empty($keys)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $sql = "SELECT `key`, value, type FROM settings WHERE `key` IN ($placeholders)";
        
        $results = $this->db->fetchAll($sql, $keys);
        
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $this->castValue($row['value'], $row['type']);
        }

        return $settings;
    }

    /**
     * Get all settings
     */
    public function getAll()
    {
        $sql = "SELECT `key`, value, type FROM settings";
        $results = $this->db->fetchAll($sql);
        
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $this->castValue($row['value'], $row['type']);
        }

        return $settings;
    }

    /**
     * Set a single setting value
     */
    public function set($key, $value, $type = 'string')
    {
        $sql = "INSERT INTO settings (`key`, value, type) 
                VALUES (:key, :value, :type)
                ON DUPLICATE KEY UPDATE value = VALUES(value), type = VALUES(type)";

        $params = [
            ':key' => $key,
            ':value' => (string)$value,
            ':type' => $type
        ];

        $this->db->query($sql, $params);
        return true;
    }

    /**
     * Set multiple settings
     */
    public function setMultiple($data)
    {
        foreach ($data as $key => $info) {
            $value = $info['value'] ?? $info;
            $type = $info['type'] ?? 'string';
            $this->set($key, $value, $type);
        }
        return true;
    }

    /**
     * Cast value to appropriate type
     */
    private function castValue($value, $type)
    {
        switch ($type) {
            case 'integer':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'boolean':
                return (bool)$value;
            default:
                return $value;
        }
    }

    /**
     * Get campus GPS settings
     */
    public function getCampusSettings()
    {
        $keys = ['campus_latitude', 'campus_longitude', 'campus_radius'];
        return $this->getMultiple($keys);
    }

    /**
     * Update campus GPS settings
     */
    public function updateCampusSettings($latitude, $longitude, $radius)
    {
        $this->set('campus_latitude', $latitude, 'float');
        $this->set('campus_longitude', $longitude, 'float');
        $this->set('campus_radius', $radius, 'integer');
        return true;
    }
}
