@section('query_placeholder', 'Search for a ticket in this project...')
@section('nav_projects_active', 'active')


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>My Space - Projecta</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/planet_logo_32x32.png') }}" />
</head>

<body>
  @include('layouts.partials.navbar-client')

  <header class="header">
    <div class="breadcrumb">
      <a href="{{route('user.projects')}}">Projects</a> /
      <span>Project 1 Details</span>
    </div>
    <input type="text" placeholder="Search for a ticket or a project..." />
  </header>

  <main>
    <div class="page-header">
      <h1 id="titleboard">Project 1</h1>
      <a href="#newTicketModal" class="btn-primary">Create a ticket</a>
    </div>

    <section class="details-grid">
      <div class="info-card contract-summary">
        <h2>Contract Status</h2>
        <div class="contract-data">
          <div class="data-item">
            <span>Included Hours</span>
            <span class="value">50h</span>
          </div>
          <div class="data-item">
            <span>Consumed</span>
            <span class="value">35h</span>
          </div>
          <div class="data-item highlight">
            <span>Remaining</span>
            <span class="value">15h</span>
          </div>
        </div>
        <div class="progress-container large">
          <div class="progress-bar">
            <div class="progress-fill" style="width: 70%"></div>
          </div>
          <p class="progress-text">70% of the budget used</p>
        </div>
        <div class="info-card-footer">
          <a href="contract.pdf" class="file-link" target="_blank">
            See contract :
            <span class="file-icon">📄</span>
            <span class="file-name">contract.pdf</span>
          </a>
          <div class="data-item extra">
            <span>Overtime rate</span>
            <span class="value">15$/h</span>
          </div>
        </div>
      </div>

      <div class="info-card collaborator-list">
        <div class="collaborators-header">
          <h2>Working on this project</h2>
        </div>
        <ul>
          <li><span class="avatar">CP</span> Corentin Pivert (Lead)</li>
          <li><span class="avatar">DG</span> Doryan Girault</li>
          <li><span class="avatar">RC</span> Raphaël Canu</li>
          <!-- <li><span class="avatar">BR</span> Baptiste Rault</li> -->
        </ul>
      </div>
    </section>

    <section class="project-tickets-section">
      <div class="section-header">
        <h2>Project Tickets</h2>
        <div class="filters">
          <select name="type" class="ticket-status">
            <option value="all">Type</option>
            <option value="included">Included in Contract</option>
            <option value="billable">Extra / Billable</option>
          </select>
        </div>
      </div>

      <table class="tickets-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Time Spent</th>
            <th>Billing</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>#TK-102</td>
            <td>Homepage Redesign</td>
            <td><span class="status-tag done">Done</span></td>
            <td>12h</td>
            <td class="type" value="included">
              <span class="billing-tag included">Included</span>
            </td>
          </tr>
          <tr>
            <td>#TK-145</td>
            <td>Mobile Navigation Bug</td>
            <td><span class="status-tag in-progress">In Progress</span></td>
            <td>4h</td>
            <td class="type" value="included">
              <span class="billing-tag included">Included</span>
            </td>
          </tr>
          <tr class="billable-row">
            <td>#TK-201</td>
            <td>New Custom Feature Request</td>
            <td><span class="status-tag pending">Waiting</span></td>
            <td>--</td>
            <td class="type" value="billable">
              <span class="billing-tag extra">Billable</span>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <section id="overview" class="noFilter" style="margin-top: 40px">
      <div class="overview-card">
        <div class="card-header-flex">
          <h2>Tickets to validate</h2>
          <span class="badge-alert">2 actions required</span>
        </div>

        <table class="tickets-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Subject</th>
              <th>Hours</th>
              <th>Status</th>
              <th style="text-align: right">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#TK-109</td>
              <td>Ajout module paiement Stripe</td>
              <td><strong>4.5h</strong></td>
              <td>
                <span class="status-tag done">To validate (done)</span>
              </td>
              <td style="text-align: right">
                <button class="btn-action approve">Accept</button>
                <button class="btn-action decline">Refuse</button>
              </td>
            </tr>
            <tr class="billable-row">
              <td>#TK-118</td>
              <td>Optimisation SEO Images</td>
              <td><strong>2.0h</strong></td>
              <td>
                <span class="status-tag pending">To validate (billable)</span>
              </td>
              <td style="text-align: right">
                <button class="btn-action approve">Accept</button>
                <button class="btn-action decline">Refuse</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <div id="newTicketModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 700px">
      <div class="modal-header">
        <h2>📝 Create New Ticket Request</h2>
        <a href="#" class="close-modal">&times;</a>
      </div>

      <form style="padding: 0; box-shadow: none; max-width: 100%" id="userTicketForm">
        <div class="form-group">
          <label>Project</label>
          <input type="text" value="Project 1" readonly />
        </div>

        <div class="form-group">
          <label>Subject / Title</label>
          <input type="text" placeholder="e.g., Login page is not responsive on mobile" id="userTicketTitle" required />
        </div>
        <div id="userTicketTitleError" class="error-text titanic">
          Title should be at least 6 characters long.
        </div>

        <div class="form-group">
          <label>Priority Level</label>
          <select class="custom-select-styled" required>
            <option value="" selected disabled>Select priority...</option>
            <option>Low - Can wait</option>
            <option>Medium - Normal priority</option>
            <option>High - Important</option>
            <option>Urgent - Blocking issue</option>
          </select>
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea placeholder="Please describe your request in detail. Include:
- What is the issue or feature request?
- Steps to reproduce (if applicable)
- Expected behavior
- Any additional context or screenshots" style="min-height: 150px" id="userTicketDesc" required></textarea>
        </div>
        <div id="userTicketDescError" class="error-text titanic">
          Description should be at least 50 characters long.
        </div>

        <div class="form-row" style="
              background: #f8fafc;
              padding: 15px;
              border-radius: 12px;
              margin-bottom: 20px;
              border: 1px solid #e2e8f0;
            ">
          <div class="form-group" style="margin-bottom: 0">
            <label>Type of Request</label>
            <select class="custom-select-styled">
              <option selected>Included in Contract</option>
              <option>Additional Service (Billable)</option>
            </select>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <label>Estimated Hours (Optional)</label>
            <input type="number" placeholder="How long do you think this will take?" step="0.5" id="userTicketHours" />
          </div>
        </div>
        <div id="userTicketHoursError" class="error-text titanic">
          Should be at least 3h.
        </div>

        <div style="
              background: #fef3c7;
              border: 1px solid #fbbf24;
              padding: 12px 16px;
              border-radius: 10px;
              margin-bottom: 20px;
            ">
          <p style="font-size: 0.85rem; color: #92400e; margin: 0">
            <strong>💡 Info:</strong> If you select "Additional Service", you
            will be charged for each hour on this ticket.
          </p>
        </div>

        <div class="modal-footer" style="
              margin-top: 30px;
              border-top: 1px solid #f1f5f9;
              padding-top: 20px;
            ">
          <a href="#" class="btn-secondary">Cancel</a>
          <button type="submit" class="btn-primary">
            Submit Ticket Request
          </button>
        </div>
      </form>
    </div>
  </div>
  <script src="{{ asset('script.js') }}"></script>
</body>

</html>