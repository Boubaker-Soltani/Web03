<?php
$pageTitle = 'Enrollments';
include __DIR__ . '/../_layout.php';
?>
<div class="row">
  <div class="col-md-3">
    <div class="card p-2 mb-3">
      <h6>Select Student</h6>
      <div class="list-group list-group-flush">
      <?php foreach ($students as $s): ?>
        <a href="index.php?page=admin.enrollments&student_id=<?= $s['id'] ?>"
           class="list-group-item list-group-item-action <?= $s['id']==$studentId?'active':'' ?>">
          <?= h($s['name']) ?>
        </a>
      <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <div class="card p-3">
      <h6>Semester Enrollments</h6>
      <?php if ($studentId): ?>
      <form method="POST" action="index.php?page=admin.save_enrollments">
        <input type="hidden" name="student_id" value="<?= $studentId ?>">
        <?php foreach ($semesters as $s): ?>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="semester_ids[]"
                 value="<?= $s['id'] ?>" id="sem<?= $s['id'] ?>"
                 <?= in_array($s['id'],$enrolled)?'checked':'' ?>>
          <label class="form-check-label" for="sem<?= $s['id'] ?>">
            <?= h($s['label']) ?> (<?= h($s['academic_year']) ?>)
            <?php if ($s['is_active']): ?><span class="badge bg-success">Active</span><?php endif; ?>
          </label>
        </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary btn-sm mt-3">Save Enrollments</button>
      </form>
      <?php else: ?>
      <p class="text-muted">Select a student.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../_layout_end.php'; ?>
