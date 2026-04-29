<?php
$pageTitle = 'GPA History';
$extraJs   = ['public/js/student.js'];
include __DIR__ . '/../_layout.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <span>All Semesters</span>
  <a href="api/gpa.php?action=export" class="btn btn-outline-success btn-sm">Export CSV</a>
</div>

<div id="historyLoading" class="text-center py-4">
  <div class="spinner-border" role="status"></div>
</div>

<div id="historyContent" class="d-none">
  <div id="historyAccordion" class="accordion"></div>
</div>

<div id="historyError" class="d-none">
  <div class="alert alert-info"><span id="historyErrorMsg"></span></div>
</div>

<?php include __DIR__ . '/../_layout_end.php'; ?>
