<?php
// api/gpa.php  —  Student grades & GPA API
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Semester.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/GPA.php';

$action    = $_GET['action'] ?? '';
$studentId = (int)($_SESSION['user_id'] ?? 0);

// ── Export CSV (no JSON header) ──────────────────────────
if ($action === 'export') {
    requireRole('student');
    $rows = Grade::getAllWithDetailsByStudent($studentId);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="gpa_history.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Semester','Academic Year','Course','Credits','Grade','Grade Points']);
    foreach ($rows as $row) {
        fputcsv($out, [
            $row['semester_label'],
            $row['academic_year'],
            $row['course_name'],
            $row['credits'],
            $row['grade'],
            $row['grade'] * $row['credits'],
        ]);
    }
    fclose($out);
    exit;
}

header('Content-Type: application/json');
requireRole('student');

// ── GET: current semester grades + GPA ──────────────────
if ($action === 'current') {
    $semester = Semester::getActive();
    if (!$semester) {
        echo json_encode(['error' => 'No active semester']);
        exit;
    }
    if (!Enrollment::exists($studentId, $semester['id'])) {
        echo json_encode(['error' => 'Not enrolled in the active semester']);
        exit;
    }

    $courses = Course::getBySemester($semester['id']);
    foreach ($courses as &$c) {
        $c['grade']        = Grade::get($studentId, $c['id'], $semester['id']);
        $c['grade_points'] = ($c['grade'] ?? 0) * $c['credits'];
    }

    $gpaRec = GPA::get($studentId, $semester['id']);
    echo json_encode([
        'semester' => $semester,
        'courses'  => $courses,
        'gpa'      => $gpaRec ? $gpaRec['gpa'] : null,
    ]);
    exit;
}

// ── GET: full history ────────────────────────────────────
if ($action === 'history') {
    $semesters = Enrollment::getSemestersByStudent($studentId);
    foreach ($semesters as &$sem) {
        $sem['courses'] = Course::getBySemester($sem['id']);
        foreach ($sem['courses'] as &$c) {
            $c['grade']        = Grade::get($studentId, $c['id'], $sem['id']);
            $c['grade_points'] = ($c['grade'] ?? 0) * $c['credits'];
        }
        $gpaRec    = GPA::get($studentId, $sem['id']);
        $sem['gpa'] = $gpaRec ? $gpaRec['gpa'] : null;
    }
    echo json_encode($semesters);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
