@section('nav_tickets_active', 'active')
@section('query_placeholder', 'Search for a tickets...')
@include('utils')


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Tickets Management - Projecta</title>
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
      <h1 id="titleboard">Tickets Pipeline</h1>
      <div class="header-actions" style="position: relative">
        <a href="#newTicketModal" class="btn-primary">+ Create Ticket</a>
      </div>
    </div>

    <section id="board">
      <div id="stats">
        <ul>
          <li class="statdiv">
            <h2>New</h2>
            <span id="blueTicket">{{ $stats['opened'] ?? 0 }}</span>
          </li>
          <li class="statdiv">
            <h2>In Progress</h2>
            <span id="orangeTicket">{{ $stats['in_progress'] ?? 0 }}</span>
          </li>
          <li class="statdiv">
            <h2>To Validate</h2>
            <span id="greenTicket">{{ $stats['to_validate'] ?? 0 }}</span>
          </li>
          <li class="statdiv">
            <h2>Closed</h2>
            <span style="color: #64748b; font-size: 1.75rem; font-weight: 700">{{ $stats['closed'] ?? 0 }}</span>
          </li>
        </ul>
      </div>
    </section>

    <section class="overview-card">
      <div class="card-header-flex">
        <h3>Recent Tickets</h3>
        <div class="filter-group">
          <form method="GET" action="{{ route('admin.tickets') }}">
            @if (!empty($returnProjectId))
              <input type="hidden" name="return_project_id" value="{{ $returnProjectId }}" />
            @endif
            <select id="projectSelect" name="project_id" onchange="this.form.submit()">
              <option value="" @selected(empty($selectedProjectId))>All Projects</option>
              @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected((string) $selectedProjectId === (string) $project->id)>
                  Project {{ $project->id }} - {{ $project->name }}
                </option>
              @endforeach
            </select>
          </form>
        </div>
      </div>

      <table class="tickets-table tickets-only">
        <thead>
          <tr>
            <th>Ticket</th>
            <th>
              <select name="status" class="ticket-status">
                <option selected>Status</option>
                <option>Opened</option>
                <option>In progress</option>
                <option>Wait for client</option>
                <option>To validate</option>
                <option>Closed</option>
              </select>
            </th>
            <th>
              <select name="priority" class="ticket-status">
                <option selected>Priority</option>
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
                <option>Urgent</option>
              </select>
            </th>
            <th>
              <select name="type" class="ticket-status">
                <option selected>Type</option>
                <option>Included</option>
                <option>Billable</option>
              </select>
            </th>
            <th>Assigned To</th>
            <th>Time (Est/Real)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tickets ?? [] as $ticket)
            @php
              $project = $projectsById[$ticket->project_id] ?? null;
              $clientName = $clientsById[$ticket->client_id] ?? ('Client #' . $ticket->client_id);
              $projectName = $project?->name ?? ('Project #' . $ticket->project_id);

              $statusLower = strtolower(trim((string) $ticket->status));
              $statusClass = 'pending';
              if ($statusLower === 'closed') {
                  $statusClass = 'done';
              } elseif ($statusLower === 'in progress' || $statusLower === 'in_progress' || $statusLower === 'in-progress') {
                  $statusClass = 'in-progress';
              }

              $priorityLower = strtolower(trim((string) $ticket->priority));
              $priorityClass = in_array($priorityLower, ['low', 'medium', 'high'], true) ? $priorityLower : 'high';

              $typeLower = strtolower(trim((string) $ticket->type));
              $isBillable = $typeLower === 'billable';
              $billingLabel = $isBillable ? 'Billable' : 'Included';
              $billingClass = $isBillable ? 'extra' : 'included';

              $estText = $ticket->time_est ?: '--';
              $realText = $ticket->time_real ?: '--';

              $assignedCollabs = $project ? ($projectCollaboratorsByProjectId[$project->id] ?? []) : [];
            @endphp

            <tr class="{{ $isBillable ? 'billable-row' : '' }}">
              <td>
                <div class="ticket-info-cell">
                  <strong>{{ $ticket->code }} - {{ $ticket->title }}</strong>
                  <span>Client: {{ $clientName }} | Project {{ $ticket->project_id }}: {{ $projectName }}</span>
                </div>
              </td>
              <td><span class="status-tag {{ $statusClass }}">{{ $ticket->status }}</span></td>
              <td><span class="badge-priority {{ $priorityClass }}">{{ $ticket->priority }}</span></td>
              <td class="type">
                <span class="billing-tag {{ $billingClass }}">{{ $billingLabel }}</span>
              </td>
              <td>
                <div class="avatar-group">
                  @forelse ($assignedCollabs as $collaborator)
                    <div class="avatar" title="{{ $collaborator->full_name }}"
                      style="background: {{ $collaborator->avatar_color }}">
                      {{ getInitials($collaborator->full_name) }}
                    </div>
                  @empty
                    <div class="avatar" title="Unassigned" style="background: #94a3b8">--</div>
                  @endforelse
                </div>
              </td>
              <td>{{ $estText }} / <strong>{{ $realText }}</strong></td>
              <td>
                <a href="#viewTicketModal{{ $ticket->id_ticket }}" class="btn-icon-settings">&#128065;</a>
              </td>
            </tr>
          @empty
            <tr class="noFilter">
              <td colspan="7">No tickets yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </section>
  </main>

  @if ($errors->any())
    <div
      style="max-width: 1100px; margin: 0 auto 20px; background: #fff7ed; border: 1px solid #fed7aa; padding: 14px 16px; border-radius: 12px;">
      <strong style="display:block; margin-bottom: 6px;">There are errors in your form:</strong>
      <ul style="margin: 0; padding-left: 18px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @foreach ($tickets ?? [] as $ticket)
    @php
      $project = $projectsById[$ticket->project_id] ?? null;
      $clientName = $clientsById[$ticket->client_id] ?? ('Client #' . $ticket->client_id);
      $projectName = $project?->name ?? ('Project #' . $ticket->project_id);
      $assignedCollabs = $project ? ($projectCollaboratorsByProjectId[$project->id] ?? []) : [];
    @endphp

    <div id="viewTicketModal{{ $ticket->id_ticket }}" class="modal-overlay">
      <div class="modal-content" style="max-width: 1100px">
        <div class="modal-header">
          <h2>Ticket {{ $ticket->code }}</h2>
          <a href="{{ !empty($returnProjectId) ? route('admin.projects.show', (int) $returnProjectId) : '#' }}" class="close-modal">&times;</a>
        </div>

        <section class="details-grid" style="margin-top: 0">
          <div class="info-card">
            <h3>Details</h3>
            <div style="margin-top: 10px">
              <strong style="display:block; font-size: 1.05rem">{{ $ticket->title }}</strong>
              <span style="display:block; margin-top: 6px; color:#64748b">Client: {{ $clientName }}</span>
              <span style="display:block; color:#64748b">Project: {{ $projectName }}</span>
              <span style="display:block; color:#64748b">Created: {{ $ticket->created_at ?: '--' }}</span>
              <span style="display:block; margin-top: 10px; color:#0f172a">
                Time: {{ $ticket->time_est ?: '--' }} / <strong>{{ $ticket->time_real ?: '--' }}</strong>
              </span>

              <div style="margin-top: 14px">
                <span style="display:block; font-weight:700; margin-bottom: 6px;">Description</span>
                <div style="color:#334155; line-height: 1.35;">
                  {{ $ticket->description ?: '--' }}
                </div>
              </div>
            </div>

            <div style="margin-top: 18px">
              <strong>Assigned To</strong>
                <div class="avatar-group" style="margin-top: 10px;">
                @forelse ($assignedCollabs as $collaborator)
                  <div class="avatar" title="{{ $collaborator->full_name }}"
                    style="background: {{ $collaborator->avatar_color }}">
                    {{ getInitials($collaborator->full_name) }}
                  </div>
                @empty
                  <div class="avatar" title="Unassigned" style="background: #94a3b8">--</div>
                @endforelse
              </div>
            </div>
          </div>

          <div class="info-card">
            <h3>Management</h3>
            <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}"
              style="padding: 0; box-shadow: none">
              @csrf
              @method('PUT')

              @if (!empty($returnProjectId))
                <input type="hidden" name="return_project_id" value="{{ $returnProjectId }}" />
              @endif

              <input type="hidden" name="title" value="{{ old('title', $ticket->title) }}" />
              <input type="hidden" name="client_id" value="{{ old('client_id', $ticket->client_id) }}" />
              <input type="hidden" name="project_id" value="{{ old('project_id', $ticket->project_id) }}" />

              <div class="form-group">
                <label>Status Cycle</label>
                @php $statusValue = old('status', $ticket->status); @endphp
                <select name="status" style="width: 100%" class="custom-select-styled">
                  <option value="Opened" @selected($statusValue === 'Opened')>Opened</option>
                  <option value="In progress" @selected($statusValue === 'In progress')>In Progress</option>
                  <option value="Wait for client" @selected($statusValue === 'Wait for client')>Wait for Client</option>
                  <option value="To validate" @selected($statusValue === 'To validate')>To Validate (Client)</option>
                  <option value="Closed" @selected($statusValue === 'Closed')>Closed</option>
                </select>
              </div>

              <div class="form-row">
                <div class="form-group" style="width: 80%;">
                  <label>Priority </label>
                  @php $priorityValue = old('priority', $ticket->priority); @endphp
                  <select class="custom-select-styled" name="priority">
                    <option value="Low" @selected($priorityValue === 'Low')>Low</option>
                    <option value="Medium" @selected($priorityValue === 'Medium')>Medium</option>
                    <option value="High" @selected($priorityValue === 'High')>High</option>
                    <option value="Urgent" @selected($priorityValue === 'Urgent')>Urgent</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Hours Spent</label>
                  <input type="number" name="time_real" value="{{ old('time_real', $ticket->time_real) }}" step="0.5" />
                </div>
              </div>

              <button type="submit" class="btn-primary" style="margin-top: 10px; width: 100%">Update Ticket</button>
            </form>

            <form id="delete-ticket-{{ $ticket->id_ticket }}" method="POST"
              action="{{ route('admin.tickets.destroy', $ticket) }}" style="display:none">
              @csrf
              @method('DELETE')
              @if (!empty($returnProjectId))
                <input type="hidden" name="return_project_id" value="{{ $returnProjectId }}" />
              @endif
            </form>
          </div>
        </section>

        <div class="modal-footer" style="margin-top: 16px;">
          <button class="btn-reject" style="margin-right: auto" form="delete-ticket-{{ $ticket->id_ticket }}"
            type="submit" onclick="return confirm('Delete this ticket?')">
            Archive Ticket
          </button>
          <a href="{{ !empty($returnProjectId) ? route('admin.projects.show', (int) $returnProjectId) : '#' }}" class="btn-secondary">Close</a>
        </div>
      </div>
    </div>
  @endforeach
  <div id="newTicketModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 900px">
      <div class="modal-header">
        <h2>Create Ticket</h2>
        <a href="#" class="close-modal">&times;</a>
      </div>

      <form method="POST" action="{{ route('admin.tickets.store') }}"
        style="padding: 0; box-shadow: none; max-width: 100%" id="ticketForm">
        @csrf
        <input type="hidden" name="status" value="Opened" />

        @error('project_id')
          <div style="margin-bottom: 14px; background: #fff1f2; border: 1px solid #fecdd3; padding: 12px 14px; border-radius: 12px; color: #9f1239;">
            {{ $message }}
          </div>
        @enderror
        <div class="form-row" style="
              background: #f8fafc;
              padding: 15px;
              border-radius: 12px;
              margin-bottom: 25px;
              border: 1px solid #e2e8f0;
            ">
          <div class="form-group" style="margin-bottom: 0">
            <label>Ticket Number</label>
            <input type="text" name="code" value="{{ old('code', $nextCode) }}" readonly class="readonly-input" />
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <label style="color: #6366f1; font-weight: 700">Client</label>
            <select class="custom-select-styled" style="border-color: #6366f1; background: #fff" name="client_id"
              required>
              @foreach ($clientsById as $id => $name)
                <option value="{{ $id }}" @selected((int) old('client_id') === (int) $id)>{{ $name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div style="gap: 30px">
          <div class="form-section">
            <div class="form-group">
              <label>Ticket Title</label>
              <input type="text" id="ticketTitle" name="title" value="{{ old('title') }}" placeholder="Enter title..."
                required />
            </div>
            <div id="titleError" class="error-text titanic">
              Title should be at least 15 characters long.
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea id="ticketDesc" name="description" placeholder="Technical details and requirements..."
                style="min-height: 120px">{{ old('description') }}</textarea>
            </div>
            <div id="descError" class="error-text titanic">
              Description should be at least 30 characters long.
            </div>
          </div>

          <div class="form-section">
            <div class="form-row">
              <div class="form-group">
                <label>Project</label>
                <select class="custom-select-styled" name="project_id" required>
                  @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) old('project_id') === (int) $project->id)>Project
                      {{ $project->id }} - {{ $project->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Priority</label>
                <select class="custom-select-styled" name="priority">
                  @php $priorityValue = old('priority', 'Medium'); @endphp
                  <option value="Low" @selected($priorityValue === 'Low')>Low</option>
                  <option value="Medium" @selected($priorityValue === 'Medium')>Medium</option>
                  <option value="High" @selected($priorityValue === 'High')>High</option>
                  <option value="Urgent" @selected($priorityValue === 'Urgent')>Urgent</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Type</label>
                <select class="custom-select-styled" name="type">
                  @php $typeValue = old('type', 'Included'); @endphp
                  <option value="Included" @selected($typeValue === 'Included')>Included</option>
                  <option value="Billable" @selected($typeValue === 'Billable')>Billable</option>
                </select>
              </div>
              <div class="form-group">
                <label>Est. Hours</label>
                <input type="number" name="time_est" value="{{ old('time_est') }}" placeholder="0" id="ticketHours" />
              </div>
              <div id="hourError" class="error-text titanic">
                Should be at least 3h.
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer" style="
              margin-top: 30px;
              border-top: 1px solid #f1f5f9;
              padding-top: 20px;
            ">
          <a href="#" class="btn-secondary">Cancel</a>
          <button type="submit" class="btn-primary">Generate Ticket</button>
        </div>
      </form>
    </div>
  </div>
  <script src="{{ asset('script.js') }}"></script>
  @if (session('open_ticket_modal'))
    <script>
      window.location.hash = @json(session('open_ticket_modal') === 'new'
        ? '#newTicketModal'
      : ('#viewTicketModal' + session('open_ticket_modal')));
    </script>
  @elseif ($errors->any())
    <script>
      window.location.hash = "#newTicketModal";
    </script>
  @endif
</body>

</html>

