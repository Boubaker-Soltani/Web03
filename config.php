<?php
// ─────────────────────────────────────────────
//  config.php  —  DB connection + session guard
// ─────────────────────────────────────────────

define('DB_HOST', 'localhost');
define('DB_NAME', 'gpa_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SESSION_TIMEOUT', 1800); // 30 minutes

function getPDO(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

function requireRole(string $expected): void {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (
        empty($_SESSION['role']) ||
        (time() - ($_SESSION['last_activity'] ?? 0)) > SESSION_TIMEOUT
    ) {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SESSION['role'] !== $expected) {
        http_response_code(403);
        echo '<!DOCTYPE html><html><body style="font-family:sans-serif;text-align:center;padding:60px">
              <h2>403 — Access Denied</h2><p>You do not have permission to view this page.</p>
              <a href="index.php">Go back</a></body></html>';
        exit;
    }

    $_SESSION['last_activity'] = time();
}

function flash(string $type, string $msg): void {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash(): ?array {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
