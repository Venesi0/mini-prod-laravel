@section('nav_projects_active', 'active')
@section('query_placeholder', 'Search for a project...')
@include('utils')


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Projects - Projecta</title>
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
      <h1 id="titleboard">Projects</h1>
      @php
        $filterClientId = request('client_id');
        $filterClientLabel = null;
        if (!empty($filterClientId)) {
            $filterClientLabel = $clientsById[(int) $filterClientId] ?? ('Client #' . (string) $filterClientId);
        }
      @endphp
      @if (!empty($filterClientLabel))
        <div style="display: flex; align-items: center; gap: 12px;">
          <span class="client-name">Filtered: {{ $filterClientLabel }}</span>
          <a href="{{ route('admin.projects') }}" class="btn-outline">Show all</a>
        </div>
      @endif
      <a href="#newProjectModal" class="btn-primary">+ New Project</a>
    </div>

    <section class="projects-grid">
      @forelse($projects ?? [] as $project)
        @php
          $clientName = $clientsById[$project->client_id] ?? ('Client #' . $project->client_id);

          $statusRaw = trim((string) $project->status);
          $statusLower = strtolower($statusRaw);
          $statusClass = '';
          if ($statusLower === 'ready') {
            $statusClass = 'ready';
          } elseif ($statusLower === 'archived') {
            $statusClass = 'archived';
          } elseif ($statusLower === 'waiting' || $statusLower === 'wait') {
            $statusClass = 'wait';
          }

          $contractHours = (int) $project->contractHours;
          $usedHours = (int) $project->usedHours;
          $percent = getProjectProgressPercent($contractHours, $usedHours);
          $over = $contractHours > 0 && $usedHours > $contractHours;
          $progressBarClass = $over ? 'alert' : '';
          $progressFillClass = $over ? 'warning' : '';
        @endphp

        <div class="project-card">
          <div class="project-status-tag {{ $statusClass }}">{{ $statusRaw }}</div>
          <div class="project-info">
            <h2>{{ $project->name }}</h2>
            <p class="client-name">Client: {{ $clientName }}</p>
          </div>
          <div class="project-stats">
            <div class="progress-container">
              <div class="progress-labels">
                <span>Contract Hours</span>
                <span>{{ $usedHours }}/{{ $contractHours }}h</span>
              </div>
              <div class="progress-bar {{ $progressBarClass }}">
                <div class="progress-fill {{ $progressFillClass }}" style="width: {{ $percent }}%"></div>
              </div>
            </div>
            <div class="card-footer">
              <span>{{ $project->openTickets }} Open Tickets</span>
              <a href="{{ route('admin.projects.show', $project->id) }}" class="btn-outline">View Details</a>
            </div>
          </div>
        </div>
      @empty
        <div class="project-card">
          <div class="project-info">
            <h2>No projects yet</h2>
            <p class="client-name">Create one to see it here.</p>
          </div>
        </div>
      @endforelse
    </section>
  </main>

  <div id="newProjectModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Create new project</h2>
        <a href="#" class="close-modal">&times;</a>
      </div>
      <form id="createProjectForm" method="POST" action="{{ route('admin.projects.store') }}">
        @csrf
        @if ($errors->any())
          <div class="error-text" style="margin-bottom: 10px;">
            {{ $errors->first() }}
          </div>
        @endif
        <div class="form-group">
          <label for="projectName">Project name</label>
          <input type="text" id="projectName" name="name" value="{{ old('name') }}" placeholder="Ex: Design refactoring"
            required />
        </div>
        <div id="nameError" class="error-text titanic">
          Project Name must be at least 6 characters long
        </div>

        <div class="form-group">
          <label for="projectClient">Client</label>
          <input type="text" id="projectClient" name="client" value="{{ old('client') }}" placeholder="e.g ACME Corp"
            required />
          @error('client')
            <div class="error-text" style="margin-top: 6px;">{{ $message }}</div>
          @enderror
        </div>
        <div id="clientError" class="error-text titanic">
          Client Name must be at least 4 characters long
        </div>

        <div class="form-group">
          <label for="projectStatus">Status</label>
          <select id="projectStatus" name="status">
            <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Ready" {{ old('status') === 'Ready' ? 'selected' : '' }}>Ready</option>
            <option value="Archived" {{ old('status') === 'Archived' ? 'selected' : '' }}>Archived</option>
            <option value="Wait" {{ old('status') === 'Wait' ? 'selected' : '' }}>Wait</option>
          </select>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="contractHours">Total Hours</label>
            <input type="number" id="contractHours" name="contractHours" value="{{ old('contractHours') }}"
              placeholder="50" required />
            @error('contractHours')
              <div class="error-text" style="margin-top: 6px;">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div id="hoursError" class="error-text titanic">
          Number of hours should be greater than 10h
        </div>

        <div class="modal-footer">
          <a href="#" type="button" class="btn-secondary close-modal">Cancel</a>
          <button type="submit" class="btn-primary">Create Project</button>
        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('script.js') }}"></script>
  @if ($errors->any())
    <script>
      window.location.hash = "#newProjectModal";
    </script>
  @endif
</body>

</html>
