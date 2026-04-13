<?php $__env->startSection('nav_dashboard_active', 'active'); ?>
<?php $__env->startSection('query_placeholder', 'Search for a ticket, project or client...'); ?>
<?php echo $__env->make('utils', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>



<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?php echo e(asset('styles.css')); ?>" />
  <title>Dashboard</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="<?php echo e(asset('img/logo_projecta.png')); ?>" />
</head>

<body>
  <!-- NAVBAR PARTIAL START: layouts/partials/navbar.blade.php -->
  <?php echo $__env->make('layouts.partials.navbar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!-- NAVBAR PARTIAL END -->

  <!-- HEADER PARTIAL START: layouts/partials/header.blade.php -->
  <?php echo $__env->make('layouts.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!-- HEADER PARTIAL END -->

  <main>
    <h1 id="titleboard">Dashboard</h1>

    <section id="board">
      <div id="stats">
        <ul>
          <li>
            <div class="statdiv">
              <h2 class="stat-title">Open Tickets</h2>
              <p id="blueTicket"><?php echo e($stats['opened'] ?? 0); ?></p>
            </div>
          </li>
          <li>
            <div class="statdiv">
              <h2 class="stat-title">In Progress</h2>
              <p id="orangeTicket"><?php echo e($stats['in_progress'] ?? 0); ?></p>
            </div>
          </li>
          <li>
            <div class="statdiv">
              <h2 class="stat-title">To validate</h2>
              <p id="greenTicket"><?php echo e($stats['to_validate'] ?? 0); ?></p>
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
            <?php $__empty_1 = true; $__currentLoopData = ($latestTickets ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
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
              ?>
              <tr class="<?php echo e($isBillable ? 'billable-row' : ''); ?>">
                <td><?php echo e($ticket->code); ?> - <?php echo e($ticket->title); ?></td>
                <td><?php echo e($projectName); ?></td>
                <td><span class="status-tag <?php echo e($statusClass); ?>"><?php echo e($ticket->status); ?></span></td>
                <td class="type"><?php echo e($ticket->type ?: '--'); ?></td>
                <td><?php echo e($clientName); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr class="noFilter">
                <td colspan="5">No tickets yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
  <script src="<?php echo e(asset('script.js')); ?>"></script>
</body>

</html>

<?php /**PATH C:\Users\coren\Documents\Alternance\Projets\Prod\mini-prod-laravel\app\laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>