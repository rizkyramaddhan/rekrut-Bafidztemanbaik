<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <div class="container-fluid">
        <button class="btn btn-toggle-sidebar d-lg-none me-2" type="button" id="sidebarToggle">
            <i class="fas fa-bars fs-4"></i>
        </button>
        <a class="navbar-brand" href="{{ route('admin.dasbord') }}">
            <i class="fas fa-building me-2"></i> Sistem Rekrutmen
        </a>
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                    <img src="https://via.placeholder.com/32x32/667eea/ffffff?text=U" class="rounded-circle me-2" alt="User" width="32" height="32">
                    Admin User
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="logout()">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
