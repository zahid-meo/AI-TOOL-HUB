<?php
/**
 * PDO connection (MySQL / MariaDB). Returns null if the database is not reachable,
 * so the public tools keep working even when the DB is down.
 */
declare(strict_types=1);

function db(): ?PDO
{
    static $pdo = null, $tried = false;
    if ($tried) {
        return $pdo;
    }
    $tried = true;
    try {
        if (DB_DSN_OVERRIDE !== '') {
            $pdo = new PDO(DB_DSN_OVERRIDE);
        } else {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        if (DB_DSN_OVERRIDE === '') {
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
    } catch (Throwable $e) {
        app_log('DB connection failed: ' . $e->getMessage());
        $pdo = null;
    }
    return $pdo;
}

function db_available(): bool
{
    return db() instanceof PDO;
}

/** Run a prepared statement; returns the statement or null when the DB is unavailable/fails. */
function db_run(string $sql, array $params = []): ?PDOStatement
{
    $pdo = db();
    if (!$pdo) {
        return null;
    }
    try {
        $st = $pdo->prepare($sql);
        $st->execute($params);
        return $st;
    } catch (Throwable $e) {
        app_log('DB error: ' . $e->getMessage());
        return null;
    }
}

function db_all(string $sql, array $params = []): array
{
    $st = db_run($sql, $params);
    return $st ? $st->fetchAll() : [];
}

function db_one(string $sql, array $params = []): ?array
{
    $st = db_run($sql, $params);
    $row = $st ? $st->fetch() : false;
    return $row ?: null;
}

function db_value(string $sql, array $params = [], $default = null)
{
    $row = db_one($sql, $params);
    return $row ? reset($row) : $default;
}
