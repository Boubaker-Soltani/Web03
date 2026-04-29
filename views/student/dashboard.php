<?php
$pageTitle = 'My Grades';
$extraJs   = ['public/js/student.js'];
include __DIR__ . '/../_layout.php';
?>

<div id="loadingSpinner" class="text-center py-4">
  <div class="spinner-border" role="status"></div>
  <p class="text-muted mt-2">Loading...</p>
</div>

<div id="gradesContainer" class="d-none">
  <div class="row g-3">
    <div class="col-md-8">
      <div class="card p-3">
        <h6 id="semTitle">Current Semester</h6>
        <table class="table table-bordered table-sm">
          <thead class="table-light">
            <tr><th>Course</th><th>Credits</th><th>Grade</th><th>Points</th></tr>
          </thead>
          <tbody id="coursesTable"></tbody>
        </table>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 text-center">
        <p class="text-muted mb-1">Semester GPA</p>
        <h1 id="gpaValue"></h1>
        <span id="gpaBadge" class="badge fs-6"></span>
        <hr>
        <a href="index.php?page=student.history" class="btn btn-outline-primary btn-sm">View History</a>
      </div>
    </div>
  </div>
</div>

<div id="errorContainer" class="d-none">
  <div class="alert alert-info"><span id="errorMsg"></span></div>
</div>

<?php include __DIR__ . '/../_layout_end.php'; ?>
