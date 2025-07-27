

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Styling untuk halaman reset password */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            padding: 20px;
        }

        .reset-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .reset-container h2 {
            text-align: center;
            color: #333;
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

        .reset-button {
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

        .reset-button:hover {
            background: #5e6fa3;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #6a7bc7;
            text-decoration: none;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Password</h2>

        <!-- Menampilkan pesan error jika ada -->
        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Reset Password Form -->
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="input-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="input-group">
                <label for="password-confirm">Confirm Password</label>
                <input type="password" id="password-confirm" name="password_confirmation" required>
            </div>

            <button type="submit" class="reset-button">Reset Password</button>
        </form>

        <div class="back-to-login">
            <p><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
