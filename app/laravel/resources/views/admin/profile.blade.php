@section('nav_profile_active', 'active')
@include('utils')


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Admin Profile - Projecta</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/planet_logo_32x32.png') }}" />
</head>

<body>
  <!-- NAVBAR PARTIAL START: layouts/partials/navbar.blade.php -->
  @include('layouts.partials.navbar-admin')
  <!-- NAVBAR PARTIAL END -->

  <main>
    <div class="page-header">
      <h1 id="titleboard">My Profile</h1>
      <a href="#profileModal" class="btn-primary">Edit Profile</a>
    </div>

    <section class="details-grid">
      <div class="info-card">
        <div class="client-card-header" style="align-items: center">
          @php
            $userName = auth()->user()?->name ?? 'Admin';
            $initials = getInitials($userName) ?: 'AD';
          @endphp
          <div class="client-avatar" style="font-size: 1.1rem">{{ $initials }}</div>
          <div class="client-meta">
            <h2>{{ $userName }}</h2>
            <span>Administrator</span>
          </div>
          <span class="badge-status active">Active</span>
        </div>

        <div class="contract-data" style="margin-top: 18px">
          <div class="data-item" style="display: block">
            <span>Email</span>
            <span class="value" style="font-size: 1.05rem">{{ auth()->user()?->email ?? 'â€”' }}</span>
          </div>
          <div class="data-item">
            <span>Office</span>
            <span class="value" style="display: block; font-size: 1.05rem">Paris HQ</span>
          </div>
        </div>
      </div>

      <div class="info-card">
        <h2>Quick Stats</h2>
        <div class="client-stats-row" style="margin-top: 10px">
          <div class="stat-item">
            <span class="stat-label">Projects</span>
            <span class="stat-value">{{ $stats['projects'] ?? 0 }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Open Tickets</span>
            <span class="stat-value">{{ $stats['open_tickets'] ?? 0 }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Clients</span>
            <span class="stat-value">{{ $stats['clients'] ?? 0 }}</span>
          </div>
        </div>
      </div>
    </section>

    <section class="overview-card" style="margin-top: 24px">
      <div class="card-header-flex">
        <h3>Recent Activity</h3>
        <span class="badge-alert">Last 7 days</span>
      </div>
      <table class="tickets-table">
        <thead>
          <tr>
            <th>Action</th>
            <th>Project</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse (($recentTickets ?? []) as $ticket)
            @php
              $projectName = $projectsById[$ticket->project_id] ?? ('Project #' . $ticket->project_id);

              $statusLower = strtolower(trim((string) $ticket->status));
              $statusClass = 'pending';
              if ($statusLower === 'completed' || $statusLower === 'closed') {
                $statusClass = 'done';
              } elseif ($statusLower === 'in progress' || $statusLower === 'in_progress' || $statusLower === 'in-progress') {
                $statusClass = 'in-progress';
              }
            @endphp
            <tr>
              <td>Created ticket {{ $ticket->code }}</td>
              <td>{{ $projectName }}</td>
              <td>{{ $ticket->created_at ? $ticket->created_at->format('M j, Y') : 'â€”' }}</td>
              <td><span class="status-tag {{ $statusClass }}">{{ $ticket->status ?: 'â€”' }}</span></td>
            </tr>
          @empty
            <tr class="noFilter">
              <td colspan="4">No recent activity.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </section>
  </main>

  <div id="profileModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Profile</h2>
        <a href="#" class="close-modal">&times;</a>
      </div>
      <form style="padding: 0; box-shadow: none" id="adminProfileForm" method="POST"
        action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="adminName">Full Name</label>
          <input type="text" id="adminName" name="name" value="{{ old('name', auth()->user()?->name) }}" required />
        </div>
        <div id="adminNameError" class="error-text {{ $errors->has('name') ? '' : 'titanic' }}">
          {{ $errors->first('name') ?: 'Please enter your name' }}
        </div>

        <div class="form-group">
          <label for="adminEmail">Email</label>
          <input type="email" id="adminEmail" name="email" value="{{ old('email', auth()->user()?->email) }}" />
        </div>
        <div id="adminEmailError" class="error-text {{ $errors->has('email') ? '' : 'titanic' }}">
          {{ $errors->first('email') ?: 'Please enter a valid email' }}
        </div>

        <div class="modal-footer">
          <a href="#" class="btn-secondary close-modal">Cancel</a>
          <button type="submit" class="btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
  <script src="{{ asset('script.js') }}"></script>
  @if (session('status') === 'profile-updated')
    <script>
      if (typeof showToast === 'function') showToast('Profile updated.');
    </script>
  @endif
</body>

</html>
