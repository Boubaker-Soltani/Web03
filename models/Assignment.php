<?php
require_once __DIR__ . '/../config.php';

class Assignment {
    public static function getAll(): array {
        return getPDO()->query(
            'SELECT a.*, u.name AS prof_name, c.name AS course_name,
                    s.label, s.academic_year
             FROM assignments a
             JOIN users u ON u.id=a.professor_id
             JOIN courses c ON c.id=a.course_id
             JOIN semesters s ON s.id=a.semester_id
             ORDER BY s.academic_year, s.label, u.name'
        )->fetchAll();
    }

    public static function exists(int $profId, int $courseId, int $semId): bool {
        $st = getPDO()->prepare(
            'SELECT id FROM assignments
             WHERE professor_id=? AND course_id=? AND semester_id=?'
        );
        $st->execute([$profId, $courseId, $semId]);
        return (bool)$st->fetch();
    }

    public static function courseAlreadyAssigned(int $courseId, int $semId): bool {
        $st = getPDO()->prepare(
            'SELECT id FROM assignments WHERE course_id=? AND semester_id=?'
        );
        $st->execute([$courseId, $semId]);
        return (bool)$st->fetch();
    }

    public static function create(int $profId, int $courseId, int $semId): void {
        $st = getPDO()->prepare(
            'INSERT INTO assignments (professor_id,course_id,semester_id) VALUES (?,?,?)'
        );
        $st->execute([$profId, $courseId, $semId]);
    }

    public static function delete(int $id): void {
        $st = getPDO()->prepare('DELETE FROM assignments WHERE id=?');
        $st->execute([$id]);
    }

    public static function getSemestersByProfessor(int $profId): array {
        $st = getPDO()->prepare(
            'SELECT DISTINCT s.* FROM semesters s
             JOIN assignments a ON a.semester_id=s.id
             WHERE a.professor_id=?
             ORDER BY s.academic_year, s.label'
        );
        $st->execute([$profId]);
        return $st->fetchAll();
    }
}
