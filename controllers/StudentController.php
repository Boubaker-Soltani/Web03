<?php
require_once __DIR__ . '/../config.php';

class StudentController {
    public function dashboard(): void {
        include __DIR__ . '/../views/student/dashboard.php';
    }

    public function history(): void {
        include __DIR__ . '/../views/student/history.php';
    }
}
