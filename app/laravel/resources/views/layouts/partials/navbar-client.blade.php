<nav id="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('img/planet_logo.png') }}" alt="Logo" />
        <span>Projecta</span>
    </div>
    <ul>
        <li><a href="{{route('user.projects')}}" class="@yield('nav_projects_active')">My Projects</a></li>
        <li><a href="{{route('user.tickets')}}" class="@yield('nav_tickets_active')">My Tickets</a></li>
        <li><a href="{{route('user.contact')}}" class="@yield('nav_contact_active')">Contact Support</a></li>
        <li><a href="{{route('user.profile')}}" class="@yield('nav_profile_active')">Profile</a></li>
        <li><a href="{{route('user.settings')}}" class="@yield('nav_settings_active')">Settings</a></li>
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
    <div class="sidebar-footer user">
        <span>Connected as CLIENT</span>
    </div>
</nav>