<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Sistem Rekrutmen')</title>

    {{-- Favicon lewat Vite asset --}}
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/Logo Bimba Baru.png') }}">

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <button class="btn-toggle-sidebar" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-users-cog me-2"></i>
                Sistem Rekrutmen Bimba Tahfidz Temanbaik
            </a>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        {{ Auth::user()->name ?? 'User' }}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('settings') }}">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <h6 class="text-white-50 text-uppercase mb-3">Menu Utama</h6>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-tachometer-alt"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posisi') ? 'active' : '' }}"
                        href="{{ route('posisi') }}">
                        <i class="fas fa-briefcase"></i> Lowongan Kerja
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dasbord') ? 'active' : '' }}"
                        href="{{ route('admin.dasbord') }}">
                        <i class="fas fa-users"></i> Kandidat
                    </a>
                </li>
            </ul>

            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">

            @auth
                @if (Auth::user()->role === 'admin')
                    <h6 class="text-white-50 text-uppercase mb-3">Manajemen</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manajement.accounts') ? 'active' : '' }}"
                                href="{{ route('manajement.accounts') }}">
                                <i class="fas fa-user-cog"></i> Manajemen Akun
                            </a>
                        </li>
                    </ul>
                @endif
            @endauth
        </div>
    </nav>

    {{-- Overlay sidebar (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Main --}}
    <div class="main-wrapper">
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 fade-in">
                <div>
                    <h2 class="text-primary-custom mb-1">@yield('page_title', 'home')</h2>
                    <p class="text-muted mb-0">@yield('page_description', 'Selamat datang di sistem rekrutmen')</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>
                        @if (!request()->routeIs('home'))
                            <li class="breadcrumb-item active" aria-current="page">
                                @yield('breadcrumb', 'Dashboard')
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>

            <div class="fade-in">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <footer class="bg-white border-top mt-5 py-3">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">&copy; {{ date('Y') }} Sistem Rekrutmen. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 text-muted">Version 1.0.0 | Powered by Laravel</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>
