<nav id="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('img/planet_logo.png') }}" alt="Logo" />
        <span>Projecta</span>
    </div>
    <ul>
        <li><a href="{{ route('dashboard') }}" class="@yield('nav_dashboard_active')">Dashboard</a></li>
        <li><a href="{{route('admin.projects')}}" class="@yield('nav_projects_active')">Projects</a></li>
        <li><a href="{{route('admin.tickets')}}" class="@yield('nav_tickets_active')">Tickets</a></li>
        <li><a href="{{route('admin.clients')}}" class="@yield('nav_clients_active')">Clients</a></li>
        <li><a href="{{route('collaborators')}}" class="@yield('nav_collaborators_active')">Collaborators</a></li>
        <li><a href="{{route('admin.profile')}}" class="@yield('nav_profile_active')">Profile</a></li>
        <li><a href="{{route('admin.settings')}}" class="@yield('nav_settings_active')">Settings</a></li>
        <li>
            <a href="{{ route('logout') }}" class="@yield('nav_logout_active')"
                onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                Logout
            </a>
            <form id="logout-form-admin" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
    <div class="sidebar-footer">
        <span>Connected as ADMIN</span>
    </div>
</nav>
