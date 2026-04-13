@section('nav_collaborators_active', 'active')
@section('query_placeholder', 'Search for a collaborator...')


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Collaborators - Projecta</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/planet_logo_32x32.png') }}" />
</head>

<body>
  <!-- NAVBAR PARTIAL START: layouts/partials/navbar.blade.php -->
  @include('layouts.partials.navbar-admin')
  <!-- NAVBAR PARTIAL END -->

  <!-- HEADER PARTIAL START: layouts/partials/header.blade.php -->
  @include('layouts.partials.header')
  <!-- HEADER PARTIAL END -->

  <main>
    <div class="page-header">
      <h1 id="titleboard">Collaborators</h1>

      <a href="{{ route('collaborators') }}" id="applyBtn" class="btn-secondary"
        style="text-decoration: none; margin-left: 400px; pointer-events: none; opacity: 0.5; cursor: not-allowed;">Apply
        Changes</a>
      <a href="#addCollabModal" class="btn-primary">+ Add Collaborator</a>
    </div>

    <section class="admin-clients-grid">
      @forelse ($collaborators ?? [] as $collaborator)
        @php
          $nameParts = preg_split('/\s+/', trim($collaborator->full_name));
          $initials = '';
          foreach ($nameParts as $part) {
            if ($part !== '') {
              $initials .= strtoupper(substr($part, 0, 1));
            }
          }
          $badgeClass = $collaborator->work_status === 'Available' ? 'active' : '';
          $rating = $collaborator->rating !== null
            ? number_format((float) $collaborator->rating, 1)
            : 'N/A';
        @endphp

        <div class="client-card">
          <div class="client-card-header">
            <div class="client-avatar" style="background: {{ $collaborator->avatar_color }}">
              {{ $initials }}
            </div>
            <div class="client-meta">
              <h2>{{ $collaborator->full_name }}</h2>
              <span>{{ $collaborator->position }}</span>
            </div>
            <div class="badge-status {{ $badgeClass }}">{{ $collaborator->work_status }}</div>
          </div>

          <div class="client-stats-row">
            <div class="stat-item">
              <span class="stat-label">Projects</span>
              <span class="stat-value">{{ $collaborator->projects_count }}</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Tickets</span>
              <span class="stat-value">{{ $collaborator->tickets_count }}</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Rating</span>
              <span class="stat-value">{{ $rating }}</span>
            </div>
          </div>

          <div class="client-card-footer">
            <div class="footer-actions">
              <div class="client-welcome">{{ $collaborator->email }}</div>
            </div>
            <div class="footer-settings">
              <a href="#settingsCollab{{ $collaborator->id_collab }}" class="btn-icon-settings">&#9881;</a>
            </div>
          </div>
        </div>

        <div id="settingsCollab{{ $collaborator->id_collab }}" class="modal-overlay">
          <div class="modal-content">
            <div class="modal-header">
              <h2>Manage Collaborator</h2>
              <a href="#" class="close-modal">&times;</a>
            </div>

            <form method="POST" action="{{ route('collaborators.update', $collaborator) }}">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label for="statusSelect-{{ $collaborator->id_collab }}">Work Status</label>
                <select id="statusSelect-{{ $collaborator->id_collab }}" name="work_status"
                  style="width: 100%; height: 45px">
                  <option value="Available" {{ $collaborator->work_status === 'Available' ? 'selected' : '' }}>Available
                  </option>
                  <option value="On project" {{ $collaborator->work_status === 'On project' ? 'selected' : '' }}>On Project
                  </option>
                  <option value="Vacation / away" {{ $collaborator->work_status === 'Vacation / away' ? 'selected' : '' }}>
                    Vacation / Away</option>
                </select>
              </div>

              <div class="form-group">
                <label for="editRole-{{ $collaborator->id_collab }}">Update Role</label>
                <input type="text" id="editRole-{{ $collaborator->id_collab }}" name="position"
                  value="{{ $collaborator->position }}" />
                <div id="collabEditRoleError" class="error-text titanic">
                  Role must be at least 4 characters
                </div>
              </div>

              <div class="modal-footer" style="justify-content: space-between; align-items: center; margin-top: 40px;">
                <button type="submit" form="delete-collab-{{ $collaborator->id_collab }}" class="btn-primary alert"
                  style="width: auto; padding: 10px 15px; font-size: 0.85rem">
                  Delete Collaborator
                </button>

                <div style="display: flex; gap: 10px">
                  <a href="#" class="btn-secondary">Cancel</a>
                  <button type="submit" class="btn-primary" style="width: auto">
                    Save Changes
                  </button>
                </div>
              </div>
            </form>

            <form id="delete-collab-{{ $collaborator->id_collab }}" method="POST"
              action="{{ route('collaborators.destroy', $collaborator) }}">
              @csrf
              @method('DELETE')
            </form>
          </div>
        </div>
      @empty
        <div class="client-card">
          <div class="client-card-header">
            <div class="client-meta">
              <h2>No collaborators yet</h2>
              <span>Add one to see it here.</span>
            </div>
          </div>
        </div>
      @endforelse
    </section>
  </main>

  <div id="addCollabModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h2>New Collaborator</h2>
        <a href="#" class="close-modal">&times;</a>
      </div>
      <form id="addCollabForm" method="POST" action="{{ route('api.collaborators.storeApi') }}" data-collab-api-form>
        @csrf
        <div class="form-group">
          <label for="collabName">Full Name</label>
          <input type="text" id="collabName" name="full_name" placeholder="e.g. Robert Fox" required />
          <div id="collabNameError" class="error-text titanic">
            Please enter at least 2 words
          </div>
        </div>
        <div class="form-group">
          <label for="collabRole">Role / Position</label>
          <input type="text" id="collabRole" name="position" placeholder="e.g. Backend Developer" required />
          <div id="collabRoleError" class="error-text titanic">
            Role must be at least 4 characters
          </div>
        </div>
        <div class="form-group">
          <label for="collabEmail">Email Address</label>
          <input type="email" id="collabEmail" name="email" placeholder="robert@projecta.com" required />
          <div id="collabEmailError" class="error-text titanic">
            Email should be as follow : johndoe@gmail.com
          </div>
        </div>

        <div class="form-group">
          <label for="couleur">Choisissez une couleur :</label>
          <input type="color" id="collabColor" name="avatar_color" value="#ff0000">
        </div>
        <div class="modal-footer">
          <a href="#" type="button" class="btn-secondary close-modal" style="width: auto">
            Cancel
          </a>
          <button type="submit" class="btn-primary" data-collab-submit-button style="width: auto" disabled>
            Add to Team
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('script.js') }}"></script>
</body>

</html>