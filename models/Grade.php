<?php
require_once __DIR__ . '/../config.php';

class Grade {
    public static function get(int $studentId, int $courseId, int $semId): ?float {
        $st = getPDO()->prepare(
            'SELECT grade FROM grades
             WHERE student_id=? AND course_id=? AND semester_id=?'
        );
        $st->execute([$studentId, $courseId, $semId]);
        $row = $st->fetch();
        return $row ? (float)$row['grade'] : null;
    }

    public static function upsert(int $studentId, int $courseId, int $semId, int $profId, float $grade): void {
        $st = getPDO()->prepare(
            'INSERT INTO grades (student_id,course_id,semester_id,professor_id,grade)
             VALUES (?,?,?,?,?)
             ON DUPLICATE KEY UPDATE grade=VALUES(grade), professor_id=VALUES(professor_id)'
        );
        $st->execute([$studentId, $courseId, $semId, $profId, $grade]);
    }

    public static function countByCourse(int $courseId): int {
        $st = getPDO()->prepare('SELECT COUNT(*) FROM grades WHERE course_id=?');
        $st->execute([$courseId]);
        return (int)$st->fetchColumn();
    }

    public static function countByStudentSemester(int $studentId, int $semId): int {
        $st = getPDO()->prepare(
            'SELECT COUNT(*) FROM grades WHERE student_id=? AND semester_id=?'
        );
        $st->execute([$studentId, $semId]);
        return (int)$st->fetchColumn();
    }

    public static function getAllWithCredits(int $studentId, int $semId): array {
        $st = getPDO()->prepare(
            'SELECT g.grade, c.credits FROM grades g
             JOIN courses c ON c.id=g.course_id
             WHERE g.student_id=? AND g.semester_id=?'
        );
        $st->execute([$studentId, $semId]);
        return $st->fetchAll();
    }

    public static function getAllWithDetailsByStudent(int $studentId): array {
        $st = getPDO()->prepare(
            'SELECT s.label AS semester_label, s.academic_year,
                    c.name AS course_name, c.credits,
                    g.grade
             FROM grades g
             JOIN courses c   ON c.id=g.course_id
             JOIN semesters s ON s.id=g.semester_id
             WHERE g.student_id=?
             ORDER BY s.academic_year, s.label, c.name'
        );
        $st->execute([$studentId]);
        return $st->fetchAll();
    }

    public static function deleteByStudent(int $studentId): void {
        $st = getPDO()->prepare('DELETE FROM grades WHERE student_id=?');
        $st->execute([$studentId]);
    }
}
