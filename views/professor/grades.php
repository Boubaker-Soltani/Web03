<?php
$pageTitle = 'Grade Entry';
$extraJs   = ['public/js/professor.js'];
include __DIR__ . '/../_layout.php';
?>

<div class="card p-3">
  <div class="row g-2 mb-3">
    <div class="col-md-4">
      <label class="form-label">Semester</label>
      <select id="semesterSelect" class="form-select">
        <option value="">-- Select Semester --</option>
        <?php foreach ($semesters as $s): ?>
        <option value="<?= $s['id'] ?>"><?= h($s['label']) ?> — <?= h($s['academic_year']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Course</label>
      <select id="courseSelect" class="form-select" disabled>
        <option value="">-- Select Course --</option>
      </select>
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button id="saveBtn" class="btn btn-success w-100" disabled>Save Grades</button>
    </div>
  </div>

  <div id="feedback"></div>

  <div id="gradeTable" class="d-none">
    <table class="table table-bordered table-sm">
      <thead class="table-light">
        <tr><th>Student</th><th>ID</th><th>Grade</th></tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <div id="emptyMsg" class="text-muted d-none">No students enrolled in this course.</div>
</div>

<?php include __DIR__ . '/../_layout_end.php'; ?>
