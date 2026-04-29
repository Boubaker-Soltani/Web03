<?php
// ─────────────────────────────────────────────
//  index.php  —  Front Controller
// ─────────────────────────────────────────────

session_start();
require_once 'config.php';

$page = $_GET['page'] ?? 'login';

// Route
if ($page === 'login' || $page === 'logout') {
    require_once 'controllers/AuthController.php';
    $ctrl = new AuthController();
    if ($page === 'logout') $ctrl->logout();
    else $ctrl->login();

} elseif (str_starts_with($page, 'admin.')) {
    requireRole('admin');
    require_once 'controllers/AdminController.php';
    $ctrl = new AdminController();
    $action = substr($page, 6); // strip 'admin.'
    match ($action) {
        'dashboard'        => $ctrl->dashboard(),
        'semesters'        => $ctrl->semesters(),
        'save_semester'    => $ctrl->saveSemester(),
        'delete_semester'  => $ctrl->deleteSemester(),
        'toggle_semester'  => $ctrl->toggleSemester(),
        'courses'          => $ctrl->courses(),
        'save_course'      => $ctrl->saveCourse(),
        'delete_course'    => $ctrl->deleteCourse(),
        'professors'       => $ctrl->professors(),
        'save_professor'   => $ctrl->saveProfessor(),
        'delete_professor' => $ctrl->deleteProfessor(),
        'students'         => $ctrl->students(),
        'save_student'     => $ctrl->saveStudent(),
        'delete_student'   => $ctrl->deleteStudent(),
        'enrollments'      => $ctrl->enrollments(),
        'save_enrollments' => $ctrl->saveEnrollments(),
        'assignments'      => $ctrl->assignments(),
        'save_assignment'  => $ctrl->saveAssignment(),
        'delete_assignment'=> $ctrl->deleteAssignment(),
        default            => $ctrl->dashboard(),
    };

} elseif (str_starts_with($page, 'professor.')) {
    requireRole('professor');
    require_once 'controllers/ProfessorController.php';
    $ctrl = new ProfessorController();
    $action = substr($page, 10);
    match ($action) {
        'grades' => $ctrl->grades(),
        default  => $ctrl->grades(),
    };

} elseif (str_starts_with($page, 'student.')) {
    requireRole('student');
    require_once 'controllers/StudentController.php';
    $ctrl = new StudentController();
    $action = substr($page, 8);
    match ($action) {
        'dashboard' => $ctrl->dashboard(),
        'history'   => $ctrl->history(),
        default     => $ctrl->dashboard(),
    };

} else {
    header('Location: index.php?page=login');
    exit;
}
