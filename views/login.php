<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>GPA System — Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="login-bg">
<div class="login-page">
  <span class="shape shape-1"></span>
  <span class="shape shape-2"></span>
  <span class="shape shape-3"></span>
  <div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-6 col-lg-4">
        <div class="card login-card p-5 shadow-lg">
          <div class="text-center mb-4">
            <div class="login-icon mb-3"><i class="bi bi-shield-lock-fill"></i></div>
            <h4 class="mb-1">Welcome back</h4>
            <p class="text-muted small">Sign in to manage grades, courses, and semesters.</p>
          </div>

          <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['msg']) ?></div>
        <?php endif; ?>

     
          
          <form method="POST" action="index.php?page=login">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input id="passwordInput" type="password" name="password" class="form-control" required>
                <button id="togglePassword" type="button" class="btn btn-outline-secondary" aria-label="Show or hide password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
        <hr>
        <small class="text-muted">
          admin@test.com / prof@test.com / stud@test.com<br>
          password: <code>password</code>
        </small>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');
    const toggleIcon = togglePassword.querySelector('i');

    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function() {
        const isVisible = passwordInput.type === 'text';
        passwordInput.type = isVisible ? 'password' : 'text';
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
      });
    }
  });
</script>
</body>
</html>
