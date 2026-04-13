@section('nav_clients_active', 'active')
@section('query_placeholder', 'Search for a client...')
@include('utils')

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Admin - Clients</title>
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
      <h1 id="titleboard">Clients Management</h1>
      <a href="#clientModal" class="btn-primary">+ New Client</a>
    </div>

    <section class="admin-clients-grid">
      @forelse($clients ?? [] as $client)
        <div class="client-card">
          <div class="client-card-header">
            <div class="client-avatar" style="background: {{ $client->avatarColor }}">{{ getInitials($client->name) }}
            </div>
            <div class="client-meta">
              <h2>{{ $client->name }}</h2>
              <span>Registered since {{ $client->date->format('F Y') }}</span>
            </div>
            <span class="badge-status {{ $client->status === 'Premium' ? 'active' : '' }}">{{ $client->status }}</span>
          </div>

          <div class="client-stats-row">
            <div class="stat-item">
              <span class="stat-label">Projects</span>
              <span class="stat-value">{{ $client->projectsNb }}</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Open Tickets</span>
              <span class="stat-value">{{ $client->openedTickets}}</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Hours (Month)</span>
              <span class="stat-value">{{ $client->totalHours }}</span>
            </div>
          </div>

          <div class="client-card-footer">
            <a href="{{ route('admin.projects', ['client_id' => $client->id_client]) }}" class="btn-outline">See
              projects</a>
            <a href="#clientEditModal{{ $client->id_client }}" class="btn-icon-settings">&#9881;</a>
          </div>
        </div>
        <div id="clientEditModal{{ $client->id_client }}" class="modal-overlay">
          <div class="modal-content" style="padding-top: 40px">
            <div class="modal-header">
              <h2>Edit Client</h2>
              <a href="#" class="close-modal">&times;</a>
            </div>
            <form method="POST" action="{{ route('admin.clients.update', $client) }}">
              @csrf
              @method('PUT')
              <div class=" form-group">
                <label for="clientNameEdit-{{ $client->id_client }}">Client name</label>
                <input type="text" id="clientNameEdit-{{ $client->id_client }}" name="name" value="{{ $client->name }}"
                  required />
                <div class="error-text titanic">
                  Client name must be at least 4 characters
                </div>
              </div>

              <div class="form-group">
                <label for="clientEmailEdit-{{ $client->id_client }}">Email</label>
                <input type="email" id="clientEmailEdit-{{ $client->id_client }}" name="email"
                  value="{{ $client->email }}" />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="projectStatusEdit-{{ $client->id_client }}">Edit status</label>
                  <select id="projectStatusEdit-{{ $client->id_client }}" name="status">
                    <option value="Premium" {{ $client->status === 'Premium' ? 'selected' : '' }}>Premium</option>
                    <option value="Standard" {{ $client->status === 'Standard' ? 'selected' : '' }}>Standard</option>
                  </select>
                  <div class="error-text titanic">
                    Please choose a status
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="clientDateEdit-{{ $client->id_client }}">Date</label>
                <input type="date" id="clientDateEdit-{{ $client->id_client }}" name="date"
                  value="{{ $client->date->format('Y-m-d') }}" required />
                <div class="error-text titanic">
                  Please choose a date
                </div>
              </div>

              <div class="modal-footer" style="justify-content: space-between; align-items: center;">
                <button type="submit" form="delete-client-{{ $client->id_client }}" class="btn-primary alert"
                  style="width: auto;">
                  Delete Client
                </button>
                <div style="display: flex; gap: 10px">
                  <a href="#" class="btn-secondary">Cancel</a>
                  <button type="submit" class="btn-primary">Edit Client</button>
                </div>
              </div>
            </form>

          </div>
        </div>
        <form id="delete-client-{{ $client->id_client }}" method="POST"
          action="{{ route('admin.clients.destroy', $client) }}" style="display: none;">
          @csrf
          @method('DELETE')
        </form>
      @empty
        <div class="client-card">
          <div class="client-card-header">
            <div class="client-meta">
              <h2>No clients yet</h2>
              <span>Add one to see it here.</span>
            </div>
          </div>
        </div>
      @endforelse
    </section>

    <div id="clientModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h2>New Client</h2>
          <a href="#" class="close-modal">&times;</a>
        </div>
        <form id="createClientForm" method="POST" action="{{ route('admin.clients.store') }}">
          @csrf
          <div class="form-group">
            <label for="clientName">Client name</label>
            <input type="text" id="clientName" name="name" placeholder="Ex: ESIEA Ivry" required />
            <div id="clientNameError" class="error-text titanic">
              Client name must be at least 4 characters
            </div>
          </div>

          <div class="form-group">
            <label for="clientEmail">Email</label>
            <input type="email" id="clientEmail" name="email" placeholder="contact@client.com" />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="projectStatus">Edit status</label>
              <select id="projectStatus" name="status">
                <option value="Premium">Premium</option>
                <option value="Standard">Standard</option>
              </select>
              <div id="clientStatusError" class="error-text titanic">
                Please choose a status
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="clientDate">Date</label>
            <input type="date" id="clientDate" name="date" required />
            <div id="clientDateError" class="error-text titanic">
              Please choose a date
            </div>
          </div>

          <!-- <div class="form-group">
              <label for="contractFile">Contract file</label>
              <input
                type="file"
                id="contractFile"
                accept=".pdf,.doc,.docx"
                required
              />
            </div> -->

          <div class="form-group">
            <label>Contract file</label>
            <div class="file-drop-area" id="dropArea">
              <span class="fake-btn">Choose file</span>
              <span class="file-msg">or drag and drop here</span>
              <input class="file-input" type="file" id="contractFile" accept=".pdf,.doc,.docx" />
            </div>
          </div>

          <div class="modal-footer">
            <a href="#" type="button" class="btn-secondary close-modal">
              Cancel
            </a>
            <button type="submit" class="btn-primary">Add Client</button>
          </div>
        </form>
      </div>
    </div>

  </main>
  <script src="{{ asset('script.js') }}"></script>
  <script>
    document.querySelectorAll(".file-input").forEach((input) => {
      input.addEventListener("change", function () {
        const fileName = this.files[0] ? this.files[0].name : "";
        const label = this.parentElement.querySelector(".file-msg");
        if (label) label.textContent = fileName || "or drag and drop here";
      });
    });
  </script>
</body>

</html>