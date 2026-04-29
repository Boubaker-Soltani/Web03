<?php
require_once __DIR__ . '/../config.php';

class Course {
    public static function getAll(): array {
        return getPDO()->query(
            'SELECT c.*, s.label, s.academic_year
             FROM courses c JOIN semesters s ON c.semester_id=s.id
             ORDER BY s.academic_year, s.label, c.name'
        )->fetchAll();
    }

    public static function getById(int $id): ?array {
        $st = getPDO()->prepare('SELECT * FROM courses WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function getBySemester(int $semId): array {
        $st = getPDO()->prepare('SELECT * FROM courses WHERE semester_id=? ORDER BY name');
        $st->execute([$semId]);
        return $st->fetchAll();
    }

    public static function countBySemester(int $semId): int {
        $st = getPDO()->prepare('SELECT COUNT(*) FROM courses WHERE semester_id=?');
        $st->execute([$semId]);
        return (int)$st->fetchColumn();
    }

    public static function create(string $name, int $credits, int $semId): void {
        $st = getPDO()->prepare('INSERT INTO courses (name,credits,semester_id) VALUES (?,?,?)');
        $st->execute([$name, $credits, $semId]);
    }

    public static function update(int $id, string $name, int $credits, int $semId): void {
        $st = getPDO()->prepare('UPDATE courses SET name=?,credits=?,semester_id=? WHERE id=?');
        $st->execute([$name, $credits, $semId, $id]);
    }

    public static function delete(int $id): void {
        $st = getPDO()->prepare('DELETE FROM courses WHERE id=?');
        $st->execute([$id]);
    }

    public static function getByProfessorSemester(int $profId, int $semId): array {
        $st = getPDO()->prepare(
            'SELECT c.* FROM courses c
             JOIN assignments a ON a.course_id=c.id
             WHERE a.professor_id=? AND a.semester_id=?
             ORDER BY c.name'
        );
        $st->execute([$profId, $semId]);
        return $st->fetchAll();
    }
}
