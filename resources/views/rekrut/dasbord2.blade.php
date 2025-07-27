<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Rekrutmen</title>
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            margin: 5px 0;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #667eea !important;
        }
        
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .btn-toggle-sidebar {
            display: none;
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 56px;
                left: -280px;
                width: 280px;
                height: calc(100vh - 56px);
                transition: left 0.3s ease;
                z-index: 1000;
                overflow-y: auto;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .btn-toggle-sidebar {
                display: block;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 56px;
                left: 0;
                width: 100%;
                height: calc(100vh - 56px);
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Layout Container -->
    <div class="container-fluid" style="margin-top: 56px;">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-2 px-0">
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
                        <a class="nav-link text-white {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                        <i class="fas fa-file-alt me-2"></i> Registe Akun
                    </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-xl-10">
                <div class="main-content py-4">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Dashboard</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm border-0 rounded">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title text-muted">Total Pelamar</h6>
                                                <h3 class="text-primary">{{ $totalPelamar }}</h3>
                                            </div>
                                            <div class="text-primary">
                                                <i class="fas fa-user-plus fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm border-0 rounded">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title text-muted">Interview Hari Ini</h6>
                                                <h3 class="text-success">{{ $totalInterview }}</h3>
                                            </div>
                                            <div class="text-success">
                                                <i class="fas fa-calendar-check fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm border-0 rounded">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title text-muted">Posisi Terbuka</h6>
                                                <h3 class="text-warning">{{ $totalPosisi }}</h3>
                                            </div>
                                            <div class="text-warning">
                                                <i class="fas fa-briefcase fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @yield('content')

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    


    <!-- Tidak perlu Bootstrap JS dari CDN karena sudah diload melalui Vite -->
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const recruitmentForm = document.getElementById('recruitmentForm');

        // Toggle Sidebar
        function toggleSidebar() {
            sidebar?.classList.toggle('show');
            sidebarOverlay?.classList.toggle('show');
        }

        // Event: Hamburger Menu Click
        sidebarToggle?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSidebar();
        });

        // Event: Click di luar sidebar (overlay)
        sidebarOverlay?.addEventListener('click', function () {
            sidebar?.classList.remove('show');
            sidebarOverlay?.classList.remove('show');
        });

        // Cegah sidebar tertutup saat diklik di dalamnya
        sidebar?.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // Resize: Tutup sidebar jika layar >= 992px
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                sidebar?.classList.remove('show');
                sidebarOverlay?.classList.remove('show');
            }
        });

        // Tampilkan konten berdasarkan menu
        window.showContent = function (section) {
            const contents = document.querySelectorAll('.content-section');
            contents.forEach(content => content.style.display = 'none');

            const targetContent = document.getElementById(`${section}-content`);
            if (targetContent) {
                targetContent.style.display = 'block';
            }

            // Update menu active
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => link.classList.remove('active'));

            const activeLink = document.querySelector(`.sidebar .nav-link[onclick="showContent('${section}')"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }

            // Auto close sidebar on mobile
            if (window.innerWidth < 992) {
                sidebar?.classList.remove('show');
                sidebarOverlay?.classList.remove('show');
            }
        };

        // Fungsi Logout (gunakan form Laravel)
        window.logout = function () {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                document.getElementById('logout-form')?.submit();
            }
        };

        // Handle form rekrutmen
        recruitmentForm?.addEventListener('submit', function (e) {
            e.preventDefault();

            const nama = document.getElementById('nama')?.value.trim();
            const email = document.getElementById('email')?.value.trim();
            const telepon = document.getElementById('telepon')?.value.trim();
            const posisi = document.getElementById('posisi')?.value.trim();

            if (nama && email && telepon && posisi) {
                alert('Aplikasi berhasil dikirim!');
                recruitmentForm.reset();
            } else {
                alert('Mohon lengkapi semua field yang wajib diisi!');
            }
        });
    });
    </script>
</body>
</html> 