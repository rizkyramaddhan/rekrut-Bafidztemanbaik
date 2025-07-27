<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Sederhana</title>

    {{-- Jika pakai CDN --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    {{-- Jika pakai Vite (hapus CDN di atas) --}}
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">MyApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten utama --}}
    <div class="container mt-5">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Selamat Datang di MyApp</h1>
                <p class="col-md-8 fs-4">
                    Ini adalah halaman contoh sederhana menggunakan Bootstrap 5 di Laravel 12.
                </p>
                <button class="btn btn-primary btn-lg" type="button">Pelajari Lebih Lanjut</button>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-dark text-white text-center py-3">
        &copy; {{ date('Y') }} MyApp. Semua hak dilindungi.
    </footer>

    {{-- JS Bootstrap jika pakai CDN --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
</body>
</html>
