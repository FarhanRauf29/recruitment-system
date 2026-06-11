<nav class="navbar navbar-expand-lg navbar-dark bg-f8f9fa shadow">
    <div class="container">
        {{-- <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <x-application-logo style="height: 30px; filter: brightness(0) invert(1);" />
            <span class="ms-2 fw-bold">Recruitment System</span>
        </a> --}}

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    {{-- <a class="nav-link @if (request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a> --}}
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown">
                        <div class="me-2 d-none d-sm-inline">{{ Auth::user()->name }}</div>
                        <i class="fas fa-user-circle fs-5"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            {{-- <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i> Profile
                            </a> --}}
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        padding: 0.75rem 1rem;
    }

    .navbar-brand {
        font-size: 1.25rem;
    }

    .nav-link {
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .bg-primary {
        background-color: #0d6efd !important;
    }
</style>
