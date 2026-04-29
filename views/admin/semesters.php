<?php
$pageTitle = 'Semesters';
include __DIR__ . '/../_layout.php';
?>
<div class="row">
  <div class="col-md-4">
    <div class="card p-3 mb-3">
      <h6 id="formTitle">Add Semester</h6>
      <form method="POST" action="index.php?page=admin.save_semester" id="semForm">
        <input type="hidden" name="id" id="semId">
        <div class="mb-2">
          <label class="form-label">Label (S1, S2...)</label>
          <input type="text" name="label" id="semLabel" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Academic Year</label>
          <input type="text" name="academic_year" id="semYear" class="form-control" placeholder="2024/2025" required>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="resetForm()">Cancel</button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <table class="table table-bordered table-sm">
      <thead class="table-light">
        <tr><th>#</th><th>Label</th><th>Year</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
      <?php foreach ($semesters as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= h($s['label']) ?></td>
        <td><?= h($s['academic_year']) ?></td>
        <td>
          <?php if ($s['is_active']): ?>
            <span class="badge bg-success">Active</span>
          <?php else: ?>
            <form method="POST" action="index.php?page=admin.toggle_semester" class="d-inline">
              <input type="hidden" name="id" value="<?= $s['id'] ?>">
              <button class="btn btn-outline-success btn-sm">Set Active</button>
            </form>
          <?php endif; ?>
        </td>
        <td>
          <button class="btn btn-outline-primary btn-sm"
            onclick="editSem(<?= $s['id'] ?>,'<?= h(addslashes($s['label'])) ?>','<?= h($s['academic_year']) ?>')">Edit</button>
          <form method="POST" action="index.php?page=admin.delete_semester" class="d-inline"
            onsubmit="return confirm('Delete?')">
            <input type="hidden" name="id" value="<?= $s['id'] ?>">
            <button class="btn btn-outline-danger btn-sm">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($semesters)): ?>
      <tr><td colspan="5" class="text-center text-muted">No semesters yet.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function editSem(id, label, year) {
  document.getElementById('semId').value = id;
  document.getElementById('semLabel').value = label;
  document.getElementById('semYear').value = year;
  document.getElementById('formTitle').textContent = 'Edit Semester';
}
function resetForm() {
  document.getElementById('semForm').reset();
  document.getElementById('semId').value = '';
  document.getElementById('formTitle').textContent = 'Add Semester';
}
</script>
<?php include __DIR__ . '/../_layout_end.php'; ?>
