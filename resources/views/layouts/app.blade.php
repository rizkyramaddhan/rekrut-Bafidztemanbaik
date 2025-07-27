<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Sistem Rekrutmen')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Bimba Baru.png') }}">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Di bagian head -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        
        :root {
            --sidebar-width: 280px;
            --navbar-height: 56px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: rgba(255,255,255,0.1);
            --sidebar-active: rgba(255,255,255,0.2);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar Styles */
        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: var(--navbar-height);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: var(--primary-gradient);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            margin: 5px 15px;
            padding: 12px 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }
        
        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-left 0.3s ease;
        }
        
        .main-content {
            padding: 20px;
            background: #f8f9fa;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .btn-toggle-sidebar {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.2rem;
            padding: 0.5rem;
            margin-right: 1rem;
        }

        .btn-toggle-sidebar:hover {
            color: #5a6fd8;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: var(--navbar-height);
                left: 0;
                width: 100%;
                height: calc(100vh - var(--navbar-height));
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .btn-toggle-sidebar {
                display: inline-block;
            }
        }

        @media (min-width: 992px) {
            .btn-toggle-sidebar {
                display: none;
            }
        }

        /* Utility Classes */
        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .bg-primary-custom {
            background: var(--primary-gradient) !important;
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body>

    <!-- Header/Navbar -->
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
                    <!-- Pengaturan Profil -->
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                    </li>
                    <!-- Pengaturan Akun -->
                    <li>
                        <a class="dropdown-item" href="{{ route('settings') }}">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <!-- Logout -->
                    <li>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>


    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <h6 class="text-white-50 text-uppercase mb-3">Menu Utama</h6>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posisi') ? 'active' : '' }}" href="{{ route('posisi') }}">
                        <i class="fas fa-briefcase"></i>
                        Lowongan Kerja
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}" href="">
                        <i class="fas fa-file-alt"></i>
                        Lamaran
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('candidates.*') ? 'active' : '' }}" href="{{ route('admin.dasbord') }}">
                        <i class="fas fa-users"></i>
                        Kandidat
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('interviews.*') ? 'active' : '' }}" href="">
                        <i class="fas fa-calendar-check"></i>
                        Jadwal Interview
                    </a>
                </li> --}}
            </ul>

            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
            
            <h6 class="text-white-50 text-uppercase mb-3">Manajemen</h6>
            <ul class="nav flex-column">
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="">
                        <i class="fas fa-building"></i>
                        Departemen
                    </a>
                </li> --}}
                <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manajement.accounts') ? 'active' : '' }}" href="{{ route('manajement.accounts') }}">
    <i class="fas fa-user-cog"></i>
    Manajemen Pengguna
</a>

</li>

                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="">
                        <i class="fas fa-chart-bar"></i>
                        Laporan
                    </a>
                </li> --}}
            </ul>
        </div>
    </nav>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <div class="main-content">
            <!-- Breadcrumb -->
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
                        @if(!request()->routeIs('home'))
                            <li class="breadcrumb-item active" aria-current="page">
                                @yield('breadcrumb', 'Dashboard')
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>

            <!-- Page Content -->
            <div class="fade-in">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-top mt-5 py-3">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            &copy; {{ date('Y') }} Sistem Rekrutmen. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 text-muted">
                            Version 1.0.0 | Powered by Laravel
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Toggle sidebar function
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }

            // Event listeners
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);

            // Close sidebar when clicking on a nav link (mobile)
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        toggleSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 150);
                    }
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>