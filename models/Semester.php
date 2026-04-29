<?php
require_once __DIR__ . '/../config.php';

class Semester {
    public static function getAll(): array {
        return getPDO()->query('SELECT * FROM semesters ORDER BY academic_year, label')->fetchAll();
    }

    public static function getById(int $id): ?array {
        $st = getPDO()->prepare('SELECT * FROM semesters WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function getActive(): ?array {
        $st = getPDO()->query('SELECT * FROM semesters WHERE is_active=1 LIMIT 1');
        return $st->fetch() ?: null;
    }

    public static function create(string $label, string $year): void {
        $st = getPDO()->prepare('INSERT INTO semesters (label,academic_year) VALUES (?,?)');
        $st->execute([$label, $year]);
    }

    public static function update(int $id, string $label, string $year): void {
        $st = getPDO()->prepare('UPDATE semesters SET label=?,academic_year=? WHERE id=?');
        $st->execute([$label, $year, $id]);
    }

    public static function delete(int $id): void {
        $st = getPDO()->prepare('DELETE FROM semesters WHERE id=?');
        $st->execute([$id]);
    }

    public static function setAllInactive(): void {
        getPDO()->query('UPDATE semesters SET is_active=0');
    }

    public static function setActive(int $id): void {
        $st = getPDO()->prepare('UPDATE semesters SET is_active=1 WHERE id=?');
        $st->execute([$id]);
    }
}
