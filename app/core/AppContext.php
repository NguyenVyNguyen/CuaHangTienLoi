<?php

class AppContext
{
    // ===== CONFIG =====
    private static array $config = [];

    public static function init(array $config)
    {
        self::$config = $config;
        
        // Đảm bảo session được bắt đầu nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ===== BASE URL =====
    public static function baseUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            ? "https"
            : "http";
        
        // Tránh trường hợp HTTP_HOST không tồn tại khi chạy CLI
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . "://" . $host . "/";
    }

    // ===== PATHS =====
    public static function wwwRoot(): string
    {
        // Sử dụng realpath để làm sạch đường dẫn (xóa bỏ /../)
        return realpath(__DIR__ . "/../../public");
    }

    public static function appRoot(): string
    {
        return realpath(__DIR__ . "/../../");
    }

    // ===== CONFIG VALUE =====
    public static function config(string $key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }

    public static function configSection(string $key): array
    {
        return self::$config[$key] ?? [];
    }

    // ===== PAGE SIZE =====
    public static function pageSize(): int
    {
        return (int)(self::$config['PageSize'] ?? 12); // Mặc định 20 cho thoải mái
    }

    // ===== SESSION (SERIALIZE) =====
    // Lưu ý: serialize giúp lưu giữ được kiểu dữ liệu Object (Class instance)
    public static function setSession(string $key, $value): void
    {
        $_SESSION[$key] = serialize($value);
    }

    public static function getSession(string $key)
    {
        if (!isset($_SESSION[$key]) || empty($_SESSION[$key])) return null;

        return unserialize($_SESSION[$key]);
    }

    // ===== JSON SESSION =====
    // Dùng cho mảng hoặc dữ liệu đơn giản, dễ đọc khi xem trong file session
    public static function setSessionJson(string $key, $value): void
    {
        $_SESSION[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public static function getSessionJson(string $key)
    {
        if (!isset($_SESSION[$key])) return null;

        return json_decode($_SESSION[$key], true);
    }

    /**
     * Hàm xóa session
     */
    public static function removeSession(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
}