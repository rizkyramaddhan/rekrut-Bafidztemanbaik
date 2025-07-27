<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Rekrutmen</title>
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
            margin-bottom: 20px;
        }

        .login-card .logo img {
            width: 50px;
            height: 50px;
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

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="https://via.placeholder.com/50x50/667eea/ffffff?text=Logo" alt="Logo" class="rounded-circle mb-2">
            </div>
            <h5>Reset Password</h5>

            <!-- Menampilkan status jika ada pesan sukses -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Menampilkan pesan error jika ada -->
            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <!-- Form Lupa Password -->
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <!-- Email Input -->
                <div class="input-group mb-3">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                </div>

                <!-- Reset Password Button -->
                <button type="submit" class="button">Send Reset Link</button>
            </form>

            <!-- Link Kembali ke Halaman Login -->
            <div class="text-center mt-4">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap and Custom JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
