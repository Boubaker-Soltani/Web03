<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user     = User::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['role']          = $user['role'];
                $_SESSION['name']          = $user['name'];
                $_SESSION['last_activity'] = time();

                $redirect = match ($user['role']) {
                    'admin'     => 'admin.dashboard',
                    'professor' => 'professor.grades',
                    'student'   => 'student.dashboard',
                };
                header("Location: index.php?page=$redirect");
                exit;
            } else {
                flash('danger', 'Invalid email or password.');
                header('Location: index.php?page=login');
                exit;
            }
        }
        include __DIR__ . '/../views/login.php';
    }

    public function logout(): void {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
