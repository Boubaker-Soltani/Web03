<?php
require_once __DIR__ . '/../config.php';

class Enrollment {
    public static function getSemesterIds(int $studentId): array {
        $st = getPDO()->prepare('SELECT semester_id FROM enrollments WHERE student_id=?');
        $st->execute([$studentId]);
        return array_column($st->fetchAll(), 'semester_id');
    }

    public static function getSemestersByStudent(int $studentId): array {
        $st = getPDO()->prepare(
            'SELECT s.* FROM semesters s
             JOIN enrollments e ON e.semester_id=s.id
             WHERE e.student_id=?
             ORDER BY s.academic_year, s.label'
        );
        $st->execute([$studentId]);
        return $st->fetchAll();
    }

    public static function getStudentsBySemester(int $semId): array {
        $st = getPDO()->prepare(
            'SELECT u.id, u.name FROM users u
             JOIN enrollments e ON e.student_id=u.id
             WHERE e.semester_id=? AND u.role="student"
             ORDER BY u.name'
        );
        $st->execute([$semId]);
        return $st->fetchAll();
    }

    public static function exists(int $studentId, int $semId): bool {
        $st = getPDO()->prepare(
            'SELECT id FROM enrollments WHERE student_id=? AND semester_id=?'
        );
        $st->execute([$studentId, $semId]);
        return (bool)$st->fetch();
    }

    public static function create(int $studentId, int $semId): void {
        $st = getPDO()->prepare(
            'INSERT IGNORE INTO enrollments (student_id,semester_id) VALUES (?,?)'
        );
        $st->execute([$studentId, $semId]);
    }

    public static function delete(int $studentId, int $semId): void {
        $st = getPDO()->prepare(
            'DELETE FROM enrollments WHERE student_id=? AND semester_id=?'
        );
        $st->execute([$studentId, $semId]);
    }

    public static function deleteByStudent(int $studentId): void {
        $st = getPDO()->prepare('DELETE FROM enrollments WHERE student_id=?');
        $st->execute([$studentId]);
    }
}
