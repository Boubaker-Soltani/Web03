<?php
// api/grades.php  —  Professor grade entry API
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/GPA.php';

header('Content-Type: application/json');
requireRole('professor');

$action  = $_REQUEST['action'] ?? '';
$profId  = (int)$_SESSION['user_id'];

// ── GET: professor's courses for a semester ──────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'courses') {
    $semId = (int)($_GET['semester_id'] ?? 0);
    echo json_encode(Course::getByProfessorSemester($profId, $semId));
    exit;
}

// ── GET: enrolled students + existing grades ─────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'students') {
    $semId    = (int)($_GET['semester_id'] ?? 0);
    $courseId = (int)($_GET['course_id']   ?? 0);

    if (!Assignment::exists($profId, $courseId, $semId)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $students = Enrollment::getStudentsBySemester($semId);
    foreach ($students as &$s) {
        $s['grade'] = Grade::get($s['id'], $courseId, $semId);
    }
    echo json_encode($students);
    exit;
}

// ── POST: save grade batch ───────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {
    $semId    = (int)($_POST['semester_id'] ?? 0);
    $courseId = (int)($_POST['course_id']   ?? 0);
    $entries  = $_POST['grades'] ?? [];

    if (!Assignment::exists($profId, $courseId, $semId)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $valid  = [0.0, 1.0, 2.0, 3.0, 4.0];
    $saved  = 0;

    foreach ($entries as $entry) {
        $studentId = (int)($entry['student_id'] ?? 0);
        $grade     = isset($entry['grade']) ? (float)$entry['grade'] : -1;
        if (!in_array($grade, $valid, true)) continue;
        Grade::upsert($studentId, $courseId, $semId, $profId, $grade);
        GPA::recompute($studentId, $semId);
        $saved++;
    }

    echo json_encode(['success' => true, 'saved' => $saved]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
