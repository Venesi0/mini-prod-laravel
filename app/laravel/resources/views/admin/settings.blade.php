@section('nav_settings_active', 'active')


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Admin Settings - Projecta</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/planet_logo_32x32.png') }}" />
</head>

<body>
  <!-- NAVBAR PARTIAL START: layouts/partials/navbar.blade.php -->
  @include('layouts.partials.navbar-admin')
  <!-- NAVBAR PARTIAL END -->

  <main>
    <div class="page-header">
      <h1 id="titleboard">Settings</h1>
    </div>

    <section class="details-grid">
      <div class="info-card">
        <h2>Security</h2>
        <form style="padding: 0; box-shadow: none" id="adminPasswordForm" method="POST" action="{{ route('admin.settings.password.update') }}">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label for="currentPassword">Current Password</label>
            <input type="password" id="currentPassword" name="current_password" required />
          </div>
          <div id="currentPasswordError"
            class="error-text {{ $errors->updatePassword->has('current_password') ? '' : 'titanic' }}">
            {{ $errors->updatePassword->first('current_password') ?: 'Please enter your current password' }}
          </div>
          <div class="form-group">
            <label for="newPassword">New Password</label>
            <input type="password" id="newPassword" name="password" required />
          </div>
          <div id="newPasswordError" class="error-text {{ $errors->updatePassword->has('password') ? '' : 'titanic' }}">
            {{ $errors->updatePassword->first('password') ?: 'Password must be at least 6 characters long and contain at least one letter and one number' }}
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="password_confirmation" required />
          </div>
          <div id="confirmPasswordError"
            class="error-text {{ ($errors->updatePassword->has('password_confirmation') || ($errors->updatePassword->has('password') && str_contains(strtolower($errors->updatePassword->first('password')), 'confirm'))) ? '' : 'titanic' }}">
            {{ $errors->updatePassword->first('password_confirmation') ?: 'Passwords do not match' }}
          </div>
          <div class="modal-footer" style="padding-top: 0">
            <button type="submit" class="btn-primary">Update Password</button>
          </div>
        </form>
      </div>

      <div class="info-card">
        <h2>Notifications</h2>
        <form style="padding: 0; box-shadow: none" id="adminNotifForm">
          <div class="form-group">
            <label>Email Notifications</label>
            <select class="custom-select-styled">
              <option selected>Enabled</option>
              <option>Disabled</option>
            </select>
          </div>
          <div class="modal-footer" style="padding-top: 0">
            <button type="submit" class="btn-primary">Save Preferences</button>
          </div>
        </form>
      </div>
    </section>
  </main>
  <script src="{{ asset('script.js') }}"></script>
  @if (session('status') === 'password-updated')
    <script>
      if (typeof showToast === 'function') showToast('Password updated.');
    </script>
  @endif
</body>

</html>
