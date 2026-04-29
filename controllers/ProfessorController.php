<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Semester.php';

class ProfessorController {
    public function grades(): void {
        $profId    = $_SESSION['user_id'];
        $semesters = Assignment::getSemestersByProfessor($profId);
        include __DIR__ . '/../views/professor/grades.php';
    }
}
