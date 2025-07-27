<div class="sidebar d-flex flex-column p-3" id="sidebar">
    <div class="mb-4 text-center">
        <img src="https://via.placeholder.com/80x80/ffffff/667eea?text=LOGO" class="rounded-circle mb-2" alt="Logo" width="80" height="80">
        <h6 class="text-white">HR Dashboard</h6>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dasbord') ? 'active' : '' }}" href="{{ route('admin.dasbord') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('admin.pelamar') ? 'active' : '' }}" href="{{ route('admin.pelamar') }}">
            <i class="fas fa-users me-2"></i> Rekrutmen
        </a>
        <a class="nav-link {{ request()->routeIs('rekrutmen.form') ? 'active' : '' }}" href="{{ route('rekrutmen.form') }}">
            <i class="fas fa-file-alt me-2"></i> Form Rekrutmen
        </a>
        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
            <i class="fas fa-user-plus me-2"></i> Register Akun
        </a>
    </nav>
</div>
