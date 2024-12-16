<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/img/avatars/avatar.png') }}" class="rounded avatar img-fluid me-1"
                        alt="Admin" /> <span class="text-dark">{{ $nameUser ?? '-' }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="dropdown-item">Log out</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
