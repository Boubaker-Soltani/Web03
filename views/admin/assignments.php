<?php
$pageTitle = 'Assignments';
include __DIR__ . '/../_layout.php';
?>
<div class="row">
  <div class="col-md-4">
    <div class="card p-3 mb-3">
      <h6>New Assignment</h6>
      <form method="POST" action="index.php?page=admin.save_assignment">
        <div class="mb-2">
          <label class="form-label">Professor</label>
          <select name="professor_id" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach ($professors as $p): ?>
            <option value="<?= $p['id'] ?>"><?= h($p['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Course</label>
          <select name="course_id" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach ($courses as $c): ?>
            <option value="<?= $c['id'] ?>"><?= h($c['name']) ?> (<?= h($c['label']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Semester</label>
          <select name="semester_id" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach ($semesters as $s): ?>
            <option value="<?= $s['id'] ?>"><?= h($s['label']) ?> (<?= h($s['academic_year']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Assign</button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <table class="table table-bordered table-sm">
      <thead class="table-light"><tr><th>Professor</th><th>Course</th><th>Semester</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach ($assignments as $a): ?>
      <tr>
        <td><?= h($a['prof_name']) ?></td>
        <td><?= h($a['course_name']) ?></td>
        <td><?= h($a['label']) ?> <?= h($a['academic_year']) ?></td>
        <td>
          <form method="POST" action="index.php?page=admin.delete_assignment" class="d-inline"
            onsubmit="return confirm('Remove?')">
            <input type="hidden" name="id" value="<?= $a['id'] ?>">
            <button class="btn btn-outline-danger btn-sm">Remove</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($assignments)): ?><tr><td colspan="4" class="text-center text-muted">No assignments yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../_layout_end.php'; ?>
