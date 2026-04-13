@section('nav_projects_active', 'active')
@section('query_placeholder', 'Search for a ticket in this project...')
@include('utils')


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Project Details - Projecta</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/planet_logo_32x32.png') }}" />
</head>

<body>
  <!-- NAVBAR PARTIAL START: layouts/partials/navbar.blade.php -->
  @include('layouts.partials.navbar-admin')
  <!-- NAVBAR PARTIAL END -->

  <header class="header">
    <div class="breadcrumb">
      <a href="{{ route('admin.projects') }}">Projects</a> / <span>Project {{ $project->id }} Details</span>
    </div>
    <input id="globalSearch" type="text" placeholder="Search in this project..." autocomplete="off" />
  </header>

  <main>
    @php
      $clientName = $client?->name ?? ('Client #' . $project->client_id);
      $contractHours = (int) $project->contractHours;
      $usedHours = (int) $project->usedHours;
      $remainingHours = max(0, $contractHours - $usedHours);
      $percent = getProjectProgressPercent($contractHours, $usedHours);
      $over = $contractHours > 0 && $usedHours > $contractHours;
      $progressBarClass = $over ? 'alert' : '';
      $progressFillClass = $over ? 'warning' : '';
    @endphp

    <div class="page-header">
      <div>
        <h1 id="titleboard">Project {{ $project->id }} : {{ $project->name }}</h1>
        <p class="client-subtitle">Client: <strong>{{ $clientName }}</strong></p>
      </div>
      <div class="action-buttons">
        <!-- <button class="btn-outline">Edit Project</button> -->
        <a href="#projectModal" class="btn-outline">Edit Project</a>
        <!-- <button class="btn-primary">+ Add Ticket</button> -->
        <a href="#newTicketModal" class="btn-primary">+ add ticket</a>
      </div>
    </div>

    <section class="details-grid">
      <div class="info-card contract-summary">
        <h2>Contract Status</h2>
        <div class="contract-data">
          <div class="data-item">
            <span>Included Hours</span>
            <span class="value">{{ $contractHours }}h</span>
          </div>
          <div class="data-item">
            <span>Consumed</span>
            <span class="value">{{ $usedHours }}h</span>
          </div>
          <div class="data-item highlight">
            <span>Remaining</span>
            <span class="value">{{ $remainingHours }}h</span>
          </div>
        </div>
        <div class="progress-container large">
          <div class="progress-bar {{ $progressBarClass }}">
            <div class="progress-fill {{ $progressFillClass }}" style="width: {{ $percent }}%"></div>
          </div>
          <p class="progress-text">{{ $percent }}% of the budget used</p>
        </div>
        <div class="info-card-footer">
          <a href="contract.pdf" class="file-link" target="_blank">
            See contract :
            <span class="file-icon">&#128196;</span>
            <span class="file-name">contract.pdf</span>
          </a>
          <div class="data-item extra">
            <span>Overtime rate</span>
            <span class="value">$15/h</span>
          </div>
        </div>
      </div>

      <div class="info-card collaborator-list">
        <div class="collaborators-header">
          <h2>Collaborators</h2>
          <!-- <span class="avatar"><a href="#projectModalcollab">+</a></span> -->
        </div>
        <ul>
          @forelse ($collaborators ?? [] as $collaborator)
            <li>
              <span class="avatar"
                style="background: {{ $collaborator->avatar_color }}; border-radius: 20px; width: 45px">{{ getInitials($collaborator->full_name) }}</span>
              {{ $collaborator->full_name }} ({{ $collaborator->position }})
            </li>
          @empty
            <li>No collaborators assigned yet.</li>
          @endforelse
        </ul>
      </div>
    </section>

    <section class="project-tickets-section">
      <div class="section-header">
        <h2>Project Tickets</h2>
        <div class="filters">
          <select name="type" class="ticket-status">
            <option value="all">Type</option>
            <option value="included">Included</option>
            <option value="billable">Billable</option>
          </select>
        </div>
      </div>

      <table class="tickets-table">
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
              $statusLower = strtolower(trim((string) $ticket->status));
              $statusClass = 'pending';
              if ($statusLower === 'closed') {
                $statusClass = 'done';
              } elseif ($statusLower === 'in progress' || $statusLower === 'in_progress' || $statusLower === 'in-progress') {
                $statusClass = 'in-progress';
              }

              $priorityLower = strtolower(trim((string) $ticket->priority));
              $priorityClass = in_array($priorityLower, ['low', 'medium', 'high'], true) ? $priorityLower : 'high';
              $priorityLabel = $ticket->priority;

              $typeLower = strtolower(trim((string) $ticket->type));
              $isBillable = $typeLower === 'billable';
              $billingLabel = $isBillable ? 'Billable' : 'Included';
              $billingClass = $isBillable ? 'extra' : 'included';

              $estText = $ticket->time_est ?: '--';
              $realText = $ticket->time_real ?: '--';
            @endphp
            <tr class="{{ $isBillable ? 'billable-row' : '' }}">
              <td>
                <div class="ticket-info-cell">
                  <strong>{{ $ticket->code }} - {{ $ticket->title }}</strong>
                </div>
              </td>
              <td><span class="status-tag {{ $statusClass }}">{{ $ticket->status }}</span></td>
              <td><span class="badge-priority {{ $priorityClass }}">{{ $priorityLabel }}</span></td>
              <td class="type">
                <span class="billing-tag {{ $billingClass }}">{{ $billingLabel }}</span>
              </td>
              <td>
                <div class="avatar-group">
                  @forelse ($collaborators ?? [] as $collaborator)
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
                <a href="{{ route('admin.tickets', ['return_project_id' => $project->id]) }}#viewTicketModal{{ $ticket->id_ticket }}"
                  class="btn-icon-settings">&#128065;</a>
              </td>
            </tr>
          @empty
            <tr class="noFilter">
              <td colspan="7">No tickets for this project.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </section>

    <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
      <button type="submit" form="delete-project-{{ $project->id }}" class="btn-primary alert" style="width: auto;">
        Delete Project
      </button>
    </div>

    <form id="delete-project-{{ $project->id }}" method="POST" action="{{ route('admin.projects.destroy', $project) }}"
      style="display: none;">
      @csrf
      @method('DELETE')
    </form>

    <div id="projectModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Edit project</h2>
          <!-- <span class="close-modal">&times;</span> -->
          <a href="#" class="close-modal">&times;</a>
        </div>
        <form id="editProjectForm" method="POST" action="{{ route('admin.projects.update', $project) }}">
          @csrf
          @method('PUT')
          <input type="hidden" name="client_id" value="{{ $project->client_id }}" />
          <input type="hidden" name="usedHours" value="{{ $project->usedHours }}" />
          <input type="hidden" name="openTickets" value="{{ $project->openTickets }}" />
          <div class="form-group">
            <label for="projectName">Project name</label>
            <input type="text" id="projectName" name="name" value="{{ old('name', $project->name) }}"
              placeholder="Ex: Design refactoring" required />
            @error('name')
              <div class="error-text" style="margin-top: 6px;">{{ $message }}</div>
            @enderror
          </div>
          <div id="nameError" class="error-text titanic">
            Project Name must be at least 6 characters long
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="contractHours">Redefine Hours</label>
              <input type="number" id="contractHours" name="contractHours"
                value="{{ old('contractHours', $project->contractHours) }}" placeholder="50" required />
              @error('contractHours')
                <div class="error-text" style="margin-top: 6px;">{{ $message }}</div>
              @enderror
            </div>
            <div id="hoursError" class="error-text titanic">
              Should be greater than the consumed hours
            </div>

            <div class="form-group">
              <label for="projectStatus">Edit status</label>
              <select id="projectStatus" name="status">
                <option value="Active" {{ old('status', $project->status) === 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="Ready" {{ old('status', $project->status) === 'Ready' ? 'selected' : '' }}>Ready</option>
                <option value="Wait" {{ old('status', $project->status) === 'Wait' ? 'selected' : '' }}>Wait</option>
                <option value="Archived" {{ old('status', $project->status) === 'Archived' ? 'selected' : '' }}>Archived
                </option>
              </select>
              @error('status')
                <div class="error-text" style="margin-top: 6px;">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="modal-footer">
            <a href="#" type="button" class="btn-secondary close-modal">
              Cancel
            </a>
            <button type="submit" class="btn-primary">Update project</button>
          </div>
        </form>
      </div>
    </div>

    <div id="projectModalcollab" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Add a collaborator</h2>
          <!-- <span class="close-modal">&times;</span> -->
          <a href="#" class="close-modal">&times;</a>
        </div>
        <form id="createCollabForm">
          <div class="form-group">
            <label for="collabName">Search by name</label>
            <input type="text" id="collabName" placeholder="Ex: Baptiste Rault" required />
          </div>

          <div class="modal-footer">
            <a href="#" type="button" class="btn-secondary close-modal">
              Cancel
            </a>
            <button type="submit" class="btn-primary">Add</button>
          </div>
        </form>
      </div>
    </div>

    <div id="newTicketModal" class="modal-overlay">
      <div class="modal-content" style="max-width: 900px">
        <div class="modal-header">
          <h2>Create Ticket</h2>
          <a href="#" class="close-modal">&times;</a>
        </div>

        <form method="POST" action="{{ route('admin.projects.tickets.store', $project) }}"
          style="padding: 0; box-shadow: none; max-width: 100%" id="ticketForm">
          @csrf
          <input type="hidden" name="status" value="Opened" />

          @if ($errors->any() && session('open_ticket_modal') === 'new')
            <div
              style="margin: 0 0 14px; background: #fff1f2; border: 1px solid #fecdd3; padding: 12px 14px; border-radius: 12px; color: #9f1239;">
              {{ $errors->first() }}
            </div>
          @endif
          <div class="form-row" style="
                background: #f8fafc;
                padding: 15px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 1px solid #e2e8f0;
              ">
            <div class="form-group" style="margin-bottom: 0">
              <label>Ticket Number</label>
              <input type="text" value="{{ $nextTicketCode ?? '#TK-????' }}" readonly class="readonly-input" />
            </div>
            <div class="form-group" style="margin-bottom: 0">
              <label style="color: #6366f1; font-weight: 700">Client</label>
              <input type="text" value="{{ $client?->name ?? ('Client #' . $project->client_id) }}" readonly
                class="readonly-input" style="border-color: #6366f1; background: #fff" />
            </div>
          </div>

          <div style="gap: 30px">
            <div class="form-section">
              <div class="form-group">
                <label>Ticket Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter title..." id="ticketTitle"
                  required />
              </div>
              <div id="titleError" class="error-text titanic">
                Title should be at least 15 characters long.
              </div>

              <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Technical details and requirements..."
                  style="min-height: 120px" id="ticketDesc">{{ old('description') }}</textarea>
              </div>
              <div id="descError" class="error-text titanic">
                Description should be at least 30 characters long.
              </div>
            </div>

            <div class="form-section">
              <div class="form-row">
                <div class="form-group">
                  <label>Project</label>
                  <input type="text" value="{{ $project->name }}" readonly class="readonly-input" />
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
                  <select name="type" class="custom-select-styled">
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
                  Should be at least than 3h.
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
  </main>

  <script src="{{ asset('script.js') }}"></script>
  @if (session('open_ticket_modal') === 'new')
    <script>
      window.location.hash = "#newTicketModal";
    </script>
  @elseif ($errors->any())
    <script>
      window.location.hash = "#projectModal";
    </script>
  @endif
</body>

</html>