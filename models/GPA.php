<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Grade.php';

class GPA {
    public static function recompute(int $studentId, int $semId): void {
        $rows = Grade::getAllWithCredits($studentId, $semId);
        $totalPoints  = 0;
        $totalCredits = 0;
        foreach ($rows as $row) {
            $totalPoints  += $row['grade'] * $row['credits'];
            $totalCredits += $row['credits'];
        }
        if ($totalCredits > 0) {
            $gpa = round($totalPoints / $totalCredits, 2);
            $st = getPDO()->prepare(
                'INSERT INTO gpa_records (student_id,semester_id,gpa)
                 VALUES (?,?,?)
                 ON DUPLICATE KEY UPDATE gpa=VALUES(gpa)'
            );
            $st->execute([$studentId, $semId, $gpa]);
        }
    }

    public static function get(int $studentId, int $semId): ?array {
        $st = getPDO()->prepare(
            'SELECT * FROM gpa_records WHERE student_id=? AND semester_id=?'
        );
        $st->execute([$studentId, $semId]);
        return $st->fetch() ?: null;
    }

    public static function deleteByStudent(int $studentId): void {
        $st = getPDO()->prepare('DELETE FROM gpa_records WHERE student_id=?');
        $st->execute([$studentId]);
    }

    public static function getAvgPerSemester(): array {
        return getPDO()->query(
            'SELECT s.label, s.academic_year, ROUND(AVG(g.gpa),2) AS avg_gpa
             FROM gpa_records g JOIN semesters s ON s.id=g.semester_id
             GROUP BY g.semester_id
             ORDER BY s.academic_year, s.label'
        )->fetchAll();
    }
}
