<?php
$role = $_SESSION['role'] ?? 'admin';
$name = $_SESSION['name'] ?? 'User';

$navLinks = [
    'admin' => [
        ['page'=>'admin.dashboard',   'label'=>'Dashboard'],
        ['page'=>'admin.semesters',   'label'=>'Semesters'],
        ['page'=>'admin.courses',     'label'=>'Courses'],
        ['page'=>'admin.professors',  'label'=>'Professors'],
        ['page'=>'admin.students',    'label'=>'Students'],
        ['page'=>'admin.enrollments', 'label'=>'Enrollments'],
        ['page'=>'admin.assignments', 'label'=>'Assignments'],
    ],
    'professor' => [
        ['page'=>'professor.grades', 'label'=>'Grade Entry'],
    ],
    'student' => [
        ['page'=>'student.dashboard', 'label'=>'My Grades'],
        ['page'=>'student.history',   'label'=>'History'],
    ],
];

$navIcons = [
    'admin.dashboard' => 'speedometer2',
    'admin.semesters' => 'calendar3',
    'admin.courses' => 'book',
    'admin.professors' => 'person-badge',
    'admin.students' => 'people',
    'admin.enrollments' => 'journal-check',
    'admin.assignments' => 'clipboard-data',
    'professor.grades' => 'pencil-square',
    'student.dashboard' => 'bar-chart',
    'student.history' => 'clock-history',
];

$currentPage = $_GET['page'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= h($pageTitle ?? 'GPA System') ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<div class="sidebar d-flex flex-column">
  <div class="brand mb-3">GPA System</div>
  <div class="sidebar-profile d-flex align-items-center gap-3 mb-4">
    <div class="avatar"><?= strtoupper(substr($name, 0, 1)) ?></div>
    <div>
      <div class="fw-semibold text-white"><?= h($name) ?></div>
      <small class="text-secondary text-uppercase"><?= h($role) ?></small>
    </div>
  </div>
  <div class="nav-title mb-2 text-uppercase text-secondary">Navigation</div>
  <ul class="nav flex-column flex-grow-1">
    <?php foreach ($navLinks[$role] ?? [] as $link): ?>
    <li class="nav-item">
      <a class="nav-link <?= $currentPage===$link['page']?'active':'' ?>"
         href="index.php?page=<?= $link['page'] ?>">
        <i class="bi bi-<?= $navIcons[$link['page']] ?? 'circle' ?> nav-icon"></i>
        <?= $link['label'] ?>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
  <div class="sidebar-footer">
    <a href="index.php?page=logout" class="btn btn-outline-secondary logout-btn w-100">Logout</a>
  </div>
</div>

<div class="main-content">
  <div class="topbar">
    <div class="page-header">
      <div>
        <h2 class="mb-1"><?= h($pageTitle ?? '') ?></h2>
        <p class="subtitle" style="color: white">Manage assignments, grades and semester data from a single dashboard.</p>
      </div>
      <div class="text-end text-muted">
        Logged in as <strong><?= h($name) ?></strong>
      </div>
    </div>
  </div>

  <?php $flash = getFlash(); if ($flash): ?>
  <div class="alert alert-<?= h($flash['type']) ?> alert-dismissible fade show mt-2" role="alert">
    <?= h($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php endif; ?>

  <div class="mt-3">
