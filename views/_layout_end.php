  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<?php if (!empty($extraJs)): foreach ($extraJs as $js): ?>
<script src="<?= h($js) ?>"></script>
<?php endforeach; endif; ?>
</body>
</html>
