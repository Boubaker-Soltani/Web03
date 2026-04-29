<?php
$pageTitle = 'Students';
include __DIR__ . '/../_layout.php';
?>
<div class="row">
  <div class="col-md-4">
    <div class="card p-3 mb-3">
      <h6 id="formTitle">Add Student</h6>
      <form method="POST" action="index.php?page=admin.save_student" id="studForm">
        <input type="hidden" name="id" id="sId">
        <div class="mb-2">
          <label class="form-label">Name</label>
          <input type="text" name="name" id="sName" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Email</label>
          <input type="email" name="email" id="sEmail" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Password <small class="text-muted">(leave blank to keep)</small></label>
          <input type="password" name="password" id="sPass" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="resetForm()">Cancel</button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <table class="table table-bordered table-sm">
      <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($students as $s): ?>
      <tr>
        <td><?= h($s['name']) ?></td>
        <td><?= h($s['email']) ?></td>
        <td>
          <a href="index.php?page=admin.enrollments&student_id=<?= $s['id'] ?>"
             class="btn btn-outline-info btn-sm">Enrollments</a>
          <button class="btn btn-outline-primary btn-sm"
            onclick="editS(<?= $s['id'] ?>,'<?= h(addslashes($s['name'])) ?>','<?= h($s['email']) ?>')">Edit</button>
          <form method="POST" action="index.php?page=admin.delete_student" class="d-inline"
            onsubmit="return confirm('Delete student and all their data?')">
            <input type="hidden" name="id" value="<?= $s['id'] ?>">
            <button class="btn btn-outline-danger btn-sm">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($students)): ?><tr><td colspan="3" class="text-center text-muted">No students yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function editS(id,name,email){
  document.getElementById('sId').value=id;
  document.getElementById('sName').value=name;
  document.getElementById('sEmail').value=email;
  document.getElementById('sPass').value='';
  document.getElementById('formTitle').textContent='Edit Student';
}
function resetForm(){document.getElementById('studForm').reset();document.getElementById('sId').value='';}
</script>
<?php include __DIR__ . '/../_layout_end.php'; ?>
