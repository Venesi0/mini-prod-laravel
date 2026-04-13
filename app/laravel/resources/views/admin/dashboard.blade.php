@section('nav_dashboard_active', 'active')
@section('query_placeholder', 'Search for a ticket, project or client...')
@include('utils')

{{-- @section('nav_dashboard_active', 'active') --}}

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Dashboard</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/logo_projecta.png') }}" />
</head>

<body>
  <!-- NAVBAR PARTIAL START: layouts/partials/navbar.blade.php -->
  @include('layouts.partials.navbar-admin')
  <!-- NAVBAR PARTIAL END -->

  <!-- HEADER PARTIAL START: layouts/partials/header.blade.php -->
  @include('layouts.partials.header')
  <!-- HEADER PARTIAL END -->

  <main>
    <h1 id="titleboard">Dashboard</h1>

    <section id="board">
      <div id="stats">
        <ul>
          <li>
            <div class="statdiv">
              <h2 class="stat-title">Open Tickets</h2>
              <p id="blueTicket">{{ $stats['opened'] ?? 0 }}</p>
            </div>
          </li>
          <li>
            <div class="statdiv">
              <h2 class="stat-title">In Progress</h2>
              <p id="orangeTicket">{{ $stats['in_progress'] ?? 0 }}</p>
            </div>
          </li>
          <li>
            <div class="statdiv">
              <h2 class="stat-title">To validate</h2>
              <p id="greenTicket">{{ $stats['to_validate'] ?? 0 }}</p>
            </div>
          </li>
        </ul>
      </div>
    </section>

    <section id="overview">
      <div class="overview-card">
        <h3>Latest tickets</h3>
        <table class="tickets-table">
          <thead>
            <tr>
              <th>Subject</th>
              <th>Project</th>
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
                <select name="type" class="ticket-status">
                  <option selected>Type</option>
                  <option>Included</option>
                  <option>Billable</option>
                </select>
              </th>
              <th>Client</th>
            </tr>
          </thead>
          <tbody>
            @forelse (($latestTickets ?? []) as $ticket)
              @php
                $projectName = $projectsById[$ticket->project_id] ?? ('Project #' . $ticket->project_id);
                $clientName = $clientsById[$ticket->client_id] ?? ('Client #' . $ticket->client_id);

                $statusLower = strtolower(trim((string) $ticket->status));
                $statusClass = 'pending';
                if ($statusLower === 'closed') {
                    $statusClass = 'done';
                } elseif ($statusLower === 'in progress' || $statusLower === 'in_progress' || $statusLower === 'in-progress') {
                    $statusClass = 'in-progress';
                }

                $typeLower = strtolower(trim((string) $ticket->type));
                $isBillable = $typeLower === 'billable';
              @endphp
              <tr class="{{ $isBillable ? 'billable-row' : '' }}">
                <td>{{ $ticket->code }} - {{ $ticket->title }}</td>
                <td>{{ $projectName }}</td>
                <td><span class="status-tag {{ $statusClass }}">{{ $ticket->status }}</span></td>
                <td class="type">{{ $ticket->type ?: '--' }}</td>
                <td>{{ $clientName }}</td>
              </tr>
            @empty
              <tr class="noFilter">
                <td colspan="5">No tickets yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
  <script src="{{asset('script.js')}}"></script>
</body>

</html>

