<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Semester.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/GPA.php';

class AdminController {

    // ── Dashboard ──────────────────────────────────────────
    public function dashboard(): void {
        $data = [
            'students'   => User::countByRole('student'),
            'professors' => User::countByRole('professor'),
            'semesters'  => count(Semester::getAll()),
            'gpaStats'   => GPA::getAvgPerSemester(),
        ];
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    // ── Semesters ──────────────────────────────────────────
    public function semesters(): void {
        $semesters = Semester::getAll();
        include __DIR__ . '/../views/admin/semesters.php';
    }

    public function saveSemester(): void {
        $label = trim($_POST['label'] ?? '');
        $year  = trim($_POST['academic_year'] ?? '');
        $id    = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        if (!$label || !$year) {
            flash('danger', 'Label and academic year are required.');
        } else {
            if ($id) Semester::update($id, $label, $year);
            else     Semester::create($label, $year);
            flash('success', 'Semester saved.');
        }
        header('Location: index.php?page=admin.semesters'); exit;
    }

    public function deleteSemester(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (Course::countBySemester($id) > 0) {
            flash('danger', 'Cannot delete: semester has linked courses.');
        } else {
            Semester::delete($id);
            flash('success', 'Semester deleted.');
        }
        header('Location: index.php?page=admin.semesters'); exit;
    }

    public function toggleSemester(): void {
        $id = (int)($_POST['id'] ?? 0);
        Semester::setAllInactive();
        Semester::setActive($id);
        flash('success', 'Active semester updated.');
        header('Location: index.php?page=admin.semesters'); exit;
    }

    // ── Courses ────────────────────────────────────────────
    public function courses(): void {
        $courses   = Course::getAll();
        $semesters = Semester::getAll();
        include __DIR__ . '/../views/admin/courses.php';
    }

    public function saveCourse(): void {
        $name    = trim($_POST['name'] ?? '');
        $credits = (int)($_POST['credits'] ?? 0);
        $semId   = (int)($_POST['semester_id'] ?? 0);
        $id      = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        if ($credits <= 0) {
            flash('danger', 'Credits must be a positive integer.');
        } elseif (!$name || !$semId) {
            flash('danger', 'All fields are required.');
        } else {
            if ($id) Course::update($id, $name, $credits, $semId);
            else     Course::create($name, $credits, $semId);
            flash('success', 'Course saved.');
        }
        header('Location: index.php?page=admin.courses'); exit;
    }

    public function deleteCourse(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (Grade::countByCourse($id) > 0) {
            flash('danger', 'Cannot delete: grades exist for this course.');
        } else {
            Course::delete($id);
            flash('success', 'Course deleted.');
        }
        header('Location: index.php?page=admin.courses'); exit;
    }

    // ── Professors ─────────────────────────────────────────
    public function professors(): void {
        $professors = User::getAllByRole('professor');
        include __DIR__ . '/../views/admin/professors.php';
    }

    public function saveProfessor(): void {
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
        $id    = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        if (User::emailExists($email, $id)) {
            flash('danger', 'Email already in use.');
        } elseif (!$name || !$email) {
            flash('danger', 'Name and email are required.');
        } elseif (!$id && !$pass) {
            flash('danger', 'Password is required for new professors.');
        } else {
            if ($id) {
                User::update($id, $name, $email);
                if ($pass) User::updatePassword($id, password_hash($pass, PASSWORD_BCRYPT));
            } else {
                User::create($name, $email, password_hash($pass, PASSWORD_BCRYPT), 'professor');
            }
            flash('success', 'Professor saved.');
        }
        header('Location: index.php?page=admin.professors'); exit;
    }

    public function deleteProfessor(): void {
        $id = (int)($_POST['id'] ?? 0);
        User::delete($id);
        flash('success', 'Professor deleted.');
        header('Location: index.php?page=admin.professors'); exit;
    }

    // ── Students ───────────────────────────────────────────
    public function students(): void {
        $students = User::getAllByRole('student');
        include __DIR__ . '/../views/admin/students.php';
    }

    public function saveStudent(): void {
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
        $id    = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        if (User::emailExists($email, $id)) {
            flash('danger', 'Email already in use.');
        } elseif (!$name || !$email) {
            flash('danger', 'Name and email are required.');
        } elseif (!$id && !$pass) {
            flash('danger', 'Password is required for new students.');
        } else {
            if ($id) {
                User::update($id, $name, $email);
                if ($pass) User::updatePassword($id, password_hash($pass, PASSWORD_BCRYPT));
            } else {
                User::create($name, $email, password_hash($pass, PASSWORD_BCRYPT), 'student');
            }
            flash('success', 'Student saved.');
        }
        header('Location: index.php?page=admin.students'); exit;
    }

    public function deleteStudent(): void {
        $id = (int)($_POST['id'] ?? 0);
        GPA::deleteByStudent($id);
        Grade::deleteByStudent($id);
        Enrollment::deleteByStudent($id);
        User::delete($id);
        flash('success', 'Student deleted.');
        header('Location: index.php?page=admin.students'); exit;
    }

    // ── Enrollments ────────────────────────────────────────
    public function enrollments(): void {
        $students  = User::getAllByRole('student');
        $semesters = Semester::getAll();
        $studentId = (int)($_GET['student_id'] ?? ($students[0]['id'] ?? 0));
        $enrolled  = Enrollment::getSemesterIds($studentId);
        include __DIR__ . '/../views/admin/enrollments.php';
    }

    public function saveEnrollments(): void {
        $studentId = (int)($_POST['student_id'] ?? 0);
        $newIds    = array_map('intval', $_POST['semester_ids'] ?? []);
        $currentIds = Enrollment::getSemesterIds($studentId);

        $toAdd    = array_diff($newIds, $currentIds);
        $toRemove = array_diff($currentIds, $newIds);
        $warnings = [];

        foreach ($toAdd    as $sid) Enrollment::create($studentId, $sid);
        foreach ($toRemove as $sid) {
            if (Grade::countByStudentSemester($studentId, $sid) > 0) {
                $warnings[] = "Semester #$sid skipped (has grades).";
            } else {
                Enrollment::delete($studentId, $sid);
            }
        }

        $msg = 'Enrollments saved.';
        if ($warnings) $msg .= ' Warnings: ' . implode(' ', $warnings);
        flash(empty($warnings) ? 'success' : 'warning', $msg);
        header("Location: index.php?page=admin.enrollments&student_id=$studentId"); exit;
    }

    // ── Assignments ────────────────────────────────────────
    public function assignments(): void {
        $assignments = Assignment::getAll();
        $professors  = User::getAllByRole('professor');
        $courses     = Course::getAll();
        $semesters   = Semester::getAll();
        include __DIR__ . '/../views/admin/assignments.php';
    }

    public function saveAssignment(): void {
        $profId   = (int)($_POST['professor_id'] ?? 0);
        $courseId = (int)($_POST['course_id'] ?? 0);
        $semId    = (int)($_POST['semester_id'] ?? 0);

        if (Assignment::courseAlreadyAssigned($courseId, $semId)) {
            flash('danger', 'This course already has a professor for this semester.');
        } else {
            Assignment::create($profId, $courseId, $semId);
            flash('success', 'Assignment saved.');
        }
        header('Location: index.php?page=admin.assignments'); exit;
    }

    public function deleteAssignment(): void {
        $id = (int)($_POST['id'] ?? 0);
        Assignment::delete($id);
        flash('success', 'Assignment removed.');
        header('Location: index.php?page=admin.assignments'); exit;
    }
}
