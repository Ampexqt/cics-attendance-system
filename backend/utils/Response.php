<?php

/**
 * Response Utility Class
 * CICS Attendance System
 */

class Response
{
    public static function json($data, $statusCode = 200)
    {
        // Clear any previous output (e.g. PHP warnings/notices)
        if (ob_get_length()) ob_clean();

        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success($message, $data = null, $statusCode = 200)
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message, $errors = null, $statusCode = 400)
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    public static function unauthorized($message = 'Unauthorized access')
    {
        self::error($message, null, 401);
    }

    public static function forbidden($message = 'Forbidden access')
    {
        self::error($message, null, 403);
    }

    public static function notFound($message = 'Resource not found')
    {
        self::error($message, null, 404);
    }

    public static function validationError($errors, $message = 'Validation failed')
    {
        self::error($message, $errors, 422);
    }
}
