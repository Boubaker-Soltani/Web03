<?php
$pageTitle = 'Professors';
include __DIR__ . '/../_layout.php';
?>
<div class="row">
  <div class="col-md-4">
    <div class="card p-3 mb-3">
      <h6 id="formTitle">Add Professor</h6>
      <form method="POST" action="index.php?page=admin.save_professor" id="profForm">
        <input type="hidden" name="id" id="pId">
        <div class="mb-2">
          <label class="form-label">Name</label>
          <input type="text" name="name" id="pName" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Email</label>
          <input type="email" name="email" id="pEmail" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Password <small class="text-muted">(leave blank to keep)</small></label>
          <input type="password" name="password" id="pPass" class="form-control">
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
      <?php foreach ($professors as $p): ?>
      <tr>
        <td><?= h($p['name']) ?></td>
        <td><?= h($p['email']) ?></td>
        <td>
          <button class="btn btn-outline-primary btn-sm"
            onclick="editP(<?= $p['id'] ?>,'<?= h(addslashes($p['name'])) ?>','<?= h($p['email']) ?>')">Edit</button>
          <form method="POST" action="index.php?page=admin.delete_professor" class="d-inline"
            onsubmit="return confirm('Delete?')">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button class="btn btn-outline-danger btn-sm">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($professors)): ?><tr><td colspan="3" class="text-center text-muted">No professors yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function editP(id,name,email){
  document.getElementById('pId').value=id;
  document.getElementById('pName').value=name;
  document.getElementById('pEmail').value=email;
  document.getElementById('pPass').value='';
  document.getElementById('formTitle').textContent='Edit Professor';
}
function resetForm(){document.getElementById('profForm').reset();document.getElementById('pId').value='';}
</script>
<?php include __DIR__ . '/../_layout_end.php'; ?>
