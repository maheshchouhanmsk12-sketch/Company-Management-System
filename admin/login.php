<?php require_once('../config.php'); ?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<?php require_once('inc/header.php'); ?>
<body class="hold-transition" style="min-height: 100vh; background: #0f172a url('<?php echo get_banner_url(); ?>') no-repeat center center fixed; background-size: cover; position: relative; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; display: flex; align-items: center; justify-content: center; margin: 0; padding: 20px;">

  <!-- Dark Backdrop Overlay -->
  <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(30, 41, 59, 0.78) 50%, rgba(15, 23, 42, 0.92) 100%); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 1;"></div>

  <div class="login-box" style="position: relative; z-index: 2; width: 100%; max-width: 440px; margin: auto;">
    
    <!-- Glassmorphic Card -->
    <div class="card shadow-lg" style="background: rgba(30, 41, 59, 0.88); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 20px; overflow: hidden; backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55);">
      
      <!-- Card Header -->
      <div class="card-header text-center border-0 pt-4 pb-2" style="background: transparent;">
        <div class="mb-3">
          <img src="<?php echo get_logo_url(); ?>" alt="Logo" class="rounded-circle shadow" style="width: 75px; height: 75px; object-fit: cover; border: 3px solid #00bcd4; padding: 2px; background: #fff;">
        </div>
        <h3 class="font-weight-bold text-white mb-1" style="letter-spacing: -0.5px;">
          <?php echo htmlspecialchars($_settings->info('name') ?: 'Company Portal'); ?>
        </h3>
        <span class="badge px-3 py-1 font-weight-normal text-uppercase" style="background: rgba(0, 188, 212, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 20px; font-size: 11px; letter-spacing: 1px;">
          <i class="fas fa-shield-alt mr-1"></i> Administrator Portal
        </span>
      </div>

      <!-- Card Body -->
      <div class="card-body px-4 py-3">
        <p class="text-center text-muted small mb-4">Sign in with your administrative credentials to manage system content.</p>

        <form id="login-frm" action="" method="post">
          
          <!-- Username Input -->
          <div class="form-group mb-3">
            <label class="text-white small font-weight-bold mb-1">Username</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" style="background: rgba(15, 23, 42, 0.8); border: 1px solid #475569; border-right: none; color: #94a3b8; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                  <i class="fas fa-user"></i>
                </span>
              </div>
              <input type="text" class="form-control" name="username" id="username_input" placeholder="Enter username" style="background: rgba(15, 23, 42, 0.8); border: 1px solid #475569; color: #fff; border-top-right-radius: 10px; border-bottom-right-radius: 10px; height: 46px;" required value="admin">
            </div>
          </div>

          <!-- Password Input with Toggle Eye -->
          <div class="form-group mb-3">
            <label class="text-white small font-weight-bold mb-1">Password</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" style="background: rgba(15, 23, 42, 0.8); border: 1px solid #475569; border-right: none; color: #94a3b8; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                  <i class="fas fa-lock"></i>
                </span>
              </div>
              <input type="password" class="form-control" name="password" id="password_input" placeholder="Enter password" style="background: rgba(15, 23, 42, 0.8); border: 1px solid #475569; border-right: none; color: #fff; height: 46px;" required value="admin123">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" id="toggle_password" style="background: rgba(15, 23, 42, 0.8); border: 1px solid #475569; border-left: none; color: #94a3b8; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                  <i class="fas fa-eye" id="pw_icon"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Quick Auto-Fill Demo Credentials Hint -->
          <div class="p-2 mb-3 rounded" style="background: rgba(15, 23, 42, 0.6); border: 1px dashed #334155;">
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted">
                <i class="fas fa-key text-warning mr-1"></i> Default: <code>admin</code> / <code>admin123</code>
              </small>
              <button type="button" id="autofill_btn" class="btn btn-xs btn-outline-info py-0 px-2" style="border-radius: 12px; font-size: 11px;">
                <i class="fas fa-magic mr-1"></i> Fill Form
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="mb-3">
            <button type="submit" class="btn btn-block btn-primary font-weight-bold" style="background: linear-gradient(135deg, #00bcd4 0%, #0284c7 100%); border: none; height: 48px; border-radius: 10px; font-size: 16px; box-shadow: 0 4px 15px rgba(0, 188, 212, 0.35); transition: all 0.3s ease;">
              <i class="fas fa-sign-in-alt mr-2"></i> Sign In to Dashboard
            </button>
          </div>

        </form>

        <!-- Auxiliary Links -->
        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary mt-3">
          <a href="<?php echo base_url; ?>index.php" class="text-muted small">
            <i class="fas fa-arrow-left mr-1"></i> Back to Website
          </a>
          <a href="<?php echo base_url; ?>db_setup.php" class="text-info small" title="Database Health Check">
            <i class="fas fa-database mr-1"></i> DB Health Check
          </a>
        </div>

      </div>

    </div>

    <!-- Small Footer Note -->
    <p class="text-center text-muted small mt-3">
      &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($_settings->info('name') ?: 'Company Management System'); ?>. All rights reserved.
    </p>

  </div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function(){
    end_loader();

    // Toggle password visibility
    $('#toggle_password').click(function(){
      var pwInput = $('#password_input');
      var icon = $('#pw_icon');
      if (pwInput.attr('type') === 'password') {
        pwInput.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
      } else {
        pwInput.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
      }
    });

    // 1-Click Auto Fill button
    $('#autofill_btn').click(function(){
      $('#username_input').val('admin');
      $('#password_input').val('admin123');
      alert_toast("Admin credentials filled!", "success");
    });
  });
</script>
</body>
</html>
