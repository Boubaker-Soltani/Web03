<?php
$pageTitle = 'Courses';
include __DIR__ . '/../_layout.php';
?>
<div class="row">
  <div class="col-md-4">
    <div class="card p-3 mb-3">
      <h6 id="formTitle">Add Course</h6>
      <form method="POST" action="index.php?page=admin.save_course" id="courseForm">
        <input type="hidden" name="id" id="cId">
        <div class="mb-2">
          <label class="form-label">Course Name</label>
          <input type="text" name="name" id="cName" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Credits</label>
          <input type="number" name="credits" id="cCredits" class="form-control" min="1" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Semester</label>
          <select name="semester_id" id="cSem" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach ($semesters as $s): ?>
            <option value="<?= $s['id'] ?>"><?= h($s['label']) ?> (<?= h($s['academic_year']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="resetForm()">Cancel</button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <table class="table table-bordered table-sm">
      <thead class="table-light">
        <tr><th>Name</th><th>Credits</th><th>Semester</th><th>Actions</th></tr>
      </thead>
      <tbody>
      <?php foreach ($courses as $c): ?>
      <tr>
        <td><?= h($c['name']) ?></td>
        <td><?= $c['credits'] ?></td>
        <td><?= h($c['label']) ?> <?= h($c['academic_year']) ?></td>
        <td>
          <button class="btn btn-outline-primary btn-sm"
            onclick="editC(<?= $c['id'] ?>,'<?= h(addslashes($c['name'])) ?>',<?= $c['credits'] ?>,<?= $c['semester_id'] ?>)">Edit</button>
          <form method="POST" action="index.php?page=admin.delete_course" class="d-inline"
            onsubmit="return confirm('Delete?')">
            <input type="hidden" name="id" value="<?= $c['id'] ?>">
            <button class="btn btn-outline-danger btn-sm">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($courses)): ?><tr><td colspan="4" class="text-center text-muted">No courses yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function editC(id,name,credits,semId){
  document.getElementById('cId').value=id;
  document.getElementById('cName').value=name;
  document.getElementById('cCredits').value=credits;
  document.getElementById('cSem').value=semId;
  document.getElementById('formTitle').textContent='Edit Course';
}
function resetForm(){document.getElementById('courseForm').reset();document.getElementById('cId').value='';}
</script>
<?php include __DIR__ . '/../_layout_end.php'; ?>
