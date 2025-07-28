<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Rekrutmen</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Bimba Baru.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
        }

        body {
            background: #f3f4f6;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .login-card .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .login-card .logo img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }

        .login-card h5 {
            text-align: center;
            color: #5e5e5e;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: normal;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .input-group input {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus {
            border-color: #6a7bc7;
            box-shadow: 0 0 0 2px rgba(106, 123, 199, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: #333;
        }

        .remember-me input {
            margin-right: 8px;
            width: auto;
        }

        .forgot-password {
            color: #6a7bc7;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .button {
            width: 100%;
            padding: 15px;
            background: #6a7bc7;
            color: white;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #5e6fa3;
        }

        
        /* Password Toggle Styles */
        .password-toggle {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #6c757d;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }

        .password-toggle-btn:hover {
            color: #495057;
        }

        .password-toggle input {
            padding-right: 40px;
        }
        

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 20px;
                margin: 10px;
            }
            
            .form-options {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="{{ asset('images/Logo Bimba Baru.png') }}" alt="Logo" class="rounded-circle mb-2">
            </div>
            <h5>Welcome Back</h5>

            <!-- Menampilkan pesan error jika ada -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <!-- Email Input -->
                <div class="input-group mb-3">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input with Toggle -->
<div class="input-group mb-3">
    <label for="password">Password</label>
    <div class="password-toggle">
        <input type="password" class="form-control" id="password" name="password" required>
        <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
            <i class="fas fa-eye" id="password-icon"></i>
        </button>
    </div>
    @error('password')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>


                <!-- Remember Me Checkbox -->
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <!-- Sign In Button -->
                <button type="submit" class="button">Sign In</button>

                <!-- Forgot Password Link -->
                <div class="form-options">
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap and Custom JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
