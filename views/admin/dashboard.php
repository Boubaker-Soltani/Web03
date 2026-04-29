<?php
$pageTitle = 'Dashboard';
include __DIR__ . '/../_layout.php';
?>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card p-3 text-center"  style="background-color: #1d4ed8">
      <h5>Students</h5>
      <h2><?= $data['students'] ?></h2>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 text-center"  style="background-color: #1d4ed8">
      <h5>Professors</h5>
      <h2><?= $data['professors'] ?></h2>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 text-center"  style="background-color: #1d4ed8">
      <h5>Semesters</h5>
      <h2><?= $data['semesters'] ?></h2>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../_layout_end.php'; ?>
