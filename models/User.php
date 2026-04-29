<?php
require_once __DIR__ . '/../config.php';

class User {
    public static function findByEmail(string $email): ?array {
        $st = getPDO()->prepare('SELECT * FROM users WHERE email = ?');
        $st->execute([$email]);
        return $st->fetch() ?: null;
    }

    public static function findById(int $id): ?array {
        $st = getPDO()->prepare('SELECT * FROM users WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function getAllByRole(string $role): array {
        $st = getPDO()->prepare('SELECT * FROM users WHERE role = ? ORDER BY name');
        $st->execute([$role]);
        return $st->fetchAll();
    }

    public static function emailExists(string $email, ?int $excludeId = null): bool {
        if ($excludeId) {
            $st = getPDO()->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $st->execute([$email, $excludeId]);
        } else {
            $st = getPDO()->prepare('SELECT id FROM users WHERE email = ?');
            $st->execute([$email]);
        }
        return (bool)$st->fetch();
    }

    public static function create(string $name, string $email, string $hash, string $role): void {
        $st = getPDO()->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
        $st->execute([$name, $email, $hash, $role]);
    }

    public static function update(int $id, string $name, string $email): void {
        $st = getPDO()->prepare('UPDATE users SET name=?,email=? WHERE id=?');
        $st->execute([$name, $email, $id]);
    }

    public static function updatePassword(int $id, string $hash): void {
        $st = getPDO()->prepare('UPDATE users SET password=? WHERE id=?');
        $st->execute([$hash, $id]);
    }

    public static function delete(int $id): void {
        $st = getPDO()->prepare('DELETE FROM users WHERE id=?');
        $st->execute([$id]);
    }

    public static function countByRole(string $role): int {
        $st = getPDO()->prepare('SELECT COUNT(*) FROM users WHERE role=?');
        $st->execute([$role]);
        return (int)$st->fetchColumn();
    }
}
