<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Rekrutmen</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom responsive styles */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            color: #2c3e50;
        }

        .form-header h2 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: #667eea;
            width: 16px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
            color: #721c24;
        }

        .form-text {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        /* File input styling */
        .form-control[type="file"] {
            padding: 0.5rem;
        }

        .form-control[type="file"]::-webkit-file-upload-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            cursor: pointer;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 0;
            }
            
            .container {
                padding: 1rem;
            }
            
            .form-container {
                margin: 1rem auto;
                padding: 1.5rem;
                border-radius: 15px;
            }
            
            .form-header h2 {
                font-size: 1.8rem;
            }
            
            .form-header p {
                font-size: 1rem;
            }
            
            .form-control, .form-select {
                padding: 0.6rem 0.8rem;
                font-size: 0.95rem;
            }
            
            .btn-primary {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
            }
            
            .mb-3 {
                margin-bottom: 1.5rem !important;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                margin: 0.5rem auto;
                padding: 1rem;
                border-radius: 10px;
            }
            
            .form-header h2 {
                font-size: 1.6rem;
            }
            
            .form-label {
                font-size: 0.95rem;
            }
            
            .form-control, .form-select {
                padding: 0.5rem 0.7rem;
                font-size: 0.9rem;
            }
        }

        /* Animation for form validation */
        .was-validated .form-control:invalid,
        .was-validated .form-select:invalid {
            border-color: #dc3545;
            animation: shake 0.5s ease-in-out;
        }

        .was-validated .form-control:valid,
        .was-validated .form-select:valid {
            border-color: #28a745;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Loading state for submit button */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Error styling untuk Laravel validation */
        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="form-header">
            <h2><i class="fas fa-briefcase"></i> Form Rekrutmen</h2>
            <p>Lengkapi data diri Anda dengan benar</p>
        </div>

        {{-- Laravel Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Laravel Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rekrutmen.submit') }}" method="POST" enctype="multipart/form-data" id="recruitmentForm" novalidate>
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">
                    <i class="fas fa-user"></i>Nama Lengkap
                </label>
                <input type="text" 
                       class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" 
                       name="nama" 
                       value="{{ old('nama') }}"
                       required 
                       placeholder="Masukkan nama lengkap Anda">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i>Alamat Email
                </label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       maxlength="100" 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                       inputmode="email" 
                       required
                       placeholder="contoh@email.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Masukkan alamat email yang valid.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="telepon" class="form-label">
                    <i class="fas fa-phone"></i>Nomor Telepon
                </label>
                <input type="text" 
                       class="form-control @error('telepon') is-invalid @enderror" 
                       id="telepon" 
                       name="telepon" 
                       value="{{ old('telepon') }}"
                       pattern="\d{11,13}" 
                       maxlength="13" 
                       inputmode="numeric" 
                       required
                       placeholder="08123456789">
                <div class="form-text">Masukkan 11–13 digit angka.</div>
                @error('telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Nomor telepon harus 11-13 digit angka.</div>
                @enderror
            </div>

            <div class="mb-3">
    <label for="posisi" class="form-label">
        <i class="fas fa-briefcase"></i>Posisi yang Dilamar
    </label>
    <select class="form-select @error('posisi') is-invalid @enderror" 
            id="posisi" 
            name="posisi" 
            required>
        <option value="">-- Pilih Posisi --</option>
        @if(isset($posisis))
            @foreach($posisis as $posisiItem)
                <option value="{{ $posisiItem->id }}" 
                        {{ old('posisi') == $posisiItem->id ? 'selected' : '' }}>
                    {{ $posisiItem->nama_posisi }}
                </option>
            @endforeach
        @else
            {{-- Fallback jika $posisis tidak tersedia --}}
            <option value="1" {{ old('posisi') == '1' ? 'selected' : '' }}>Software Developer</option>
            <option value="2" {{ old('posisi') == '2' ? 'selected' : '' }}>UI/UX Designer</option>
            <option value="3" {{ old('posisi') == '3' ? 'selected' : '' }}>Project Manager</option>
            <option value="4" {{ old('posisi') == '4' ? 'selected' : '' }}>Data Analyst</option>
            <option value="5" {{ old('posisi') == '5' ? 'selected' : '' }}>Marketing Specialist</option>
            <option value="6" {{ old('posisi') == '6' ? 'selected' : '' }}>HR Specialist</option>
        @endif
    </select>
    @error('posisi')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        <div class="invalid-feedback">Pilih posisi yang Anda lamar.</div>
    @enderror
</div>


            <div class="mb-3">
                <label for="cv" class="form-label">
                    <i class="fas fa-file-pdf"></i>Upload CV (PDF/DOC)
                </label>
                <input type="file" 
                       class="form-control @error('cv') is-invalid @enderror" 
                       id="cv" 
                       name="cv" 
                       accept=".pdf,.doc,.docx" 
                       required>
                <div class="form-text">Format yang diterima: PDF, DOC, DOCX (Maks. 5MB)</div>
                @error('cv')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">CV wajib diupload.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ktp" class="form-label">
                    <i class="fas fa-id-card"></i>Foto KTP
                </label>
                <input type="file" 
                       class="form-control @error('ktp') is-invalid @enderror" 
                       id="ktp" 
                       name="ktp" 
                       accept="image/*" 
                       required>
                <div class="form-text">Format gambar: JPG, PNG, JPEG (Maks. 2MB)</div>
                @error('ktp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Foto KTP wajib diupload.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kk" class="form-label">
                    <i class="fas fa-users"></i>Kartu Keluarga
                </label>
                <input type="file" 
                       class="form-control @error('kk') is-invalid @enderror" 
                       id="kk" 
                       name="kk" 
                       accept=".pdf,image/*" 
                       required>
                <div class="form-text">Format: PDF atau gambar (Maks. 2MB)</div>
                @error('kk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Kartu Keluarga wajib diupload.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="pas_foto" class="form-label">
                    <i class="fas fa-camera"></i>Pas Foto
                </label>
                <input type="file" 
                       class="form-control @error('pas_foto') is-invalid @enderror" 
                       id="pas_foto" 
                       name="pas_foto" 
                       accept="image/*" 
                       required>
                <div class="form-text">Foto formal ukuran 3x4 atau 4x6 (Maks. 1MB)</div>
                @error('pas_foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Pas foto wajib diupload.</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="ijazah_skck" class="form-label">
                    <i class="fas fa-certificate"></i>Ijazah atau SKCK
                </label>
                <input type="file" 
                       class="form-control @error('ijazah_skck') is-invalid @enderror" 
                       id="ijazah_skck" 
                       name="ijazah_skck" 
                       accept=".pdf,image/*" 
                       required>
                <div class="form-text">Upload ijazah terakhir atau SKCK (Maks. 3MB)</div>
                @error('ijazah_skck')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Ijazah atau SKCK wajib diupload.</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-paper-plane me-2"></i>Kirim Lamaran
            </button>
        </form>
    </div>
</div>

<script>
    // Enhanced form validation with Laravel integration
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';
        
        const form = document.getElementById('recruitmentForm');
        const submitBtn = document.getElementById('submitBtn');

        // File size validation limits
        const fileSizeLimits = {
            cv: 5 * 1024 * 1024, // 5MB
            ktp: 2 * 1024 * 1024, // 2MB
            kk: 2 * 1024 * 1024, // 2MB
            pas_foto: 1 * 1024 * 1024, // 1MB
            ijazah_skck: 3 * 1024 * 1024 // 3MB
        };

        // Validate file size
        function validateFileSize(input) {
            const file = input.files[0];
            const limit = fileSizeLimits[input.name];
            
            if (file && file.size > limit) {
                const limitMB = (limit / 1024 / 1024).toFixed(0);
                input.setCustomValidity(`Ukuran file maksimal ${limitMB}MB`);
                
                // Show custom error message
                const feedback = input.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = `Ukuran file maksimal ${limitMB}MB`;
                }
                
                input.classList.add('is-invalid');
                return false;
            }
            
            input.setCustomValidity('');
            input.classList.remove('is-invalid');
            
            // Reset error message
            const feedback = input.parentNode.querySelector('.invalid-feedback');
            if (feedback && !feedback.textContent.includes('wajib')) {
                const originalText = input.getAttribute('data-original-error') || 'File wajib diupload.';
                feedback.textContent = originalText;
            }
            
            return true;
        }

        // Store original error messages
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            const input = feedback.parentNode.querySelector('input, select');
            if (input) {
                input.setAttribute('data-original-error', feedback.textContent);
            }
        });

        // Add file size validation to all file inputs
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                validateFileSize(this);
            });
        });

        // Phone number formatting and validation
        const teleponInput = document.getElementById('telepon');
        if (teleponInput) {
            teleponInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                let value = e.target.value.replace(/\D/g, '');
                
                // Ensure it starts with 08 or 62
                if (value.length > 0 && !value.startsWith('08') && !value.startsWith('62')) {
                    if (value.startsWith('8')) {
                        value = '0' + value;
                    }
                }
                
                e.target.value = value;
            });
        }

        // Form submission with loading state
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Validate all file sizes before submission
            document.querySelectorAll('input[type="file"]').forEach(input => {
                if (input.files.length > 0 && !validateFileSize(input)) {
                    isValid = false;
                }
            });

            if (isValid && form.checkValidity()) {
                // Show loading state
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                
                // Prevent double submission
                form.style.pointerEvents = 'none';
            } else {
                event.preventDefault();
                event.stopPropagation();
                
                // Focus on first invalid field
                const firstInvalid = form.querySelector(':invalid, .is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            form.classList.add('was-validated');
        });

        // Real-time validation feedback
        form.querySelectorAll('input, select').forEach(field => {
            field.addEventListener('blur', function() {
                if (this.checkValidity() && !this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Auto-scroll to alerts
        const alerts = document.querySelectorAll('.alert');
        if (alerts.length > 0) {
            alerts[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
</body>
</html>