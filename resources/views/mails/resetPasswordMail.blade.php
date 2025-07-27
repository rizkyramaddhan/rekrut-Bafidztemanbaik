<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Your Password</title>
    <style>
        /* Reset some styles */
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            padding: 20px;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .email-header {
            padding-bottom: 20px;
        }

        .email-header img {
            max-width: 120px;
        }

        .email-body {
            padding-bottom: 30px;
        }

        .email-body h2 {
            font-size: 24px;
            color: #4c4c4c;
            margin-bottom: 15px;
        }

        .email-body p {
            font-size: 16px;
            color: #777;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        .email-button {
            display: inline-block;
            background-color: #6a7bc7;
            color: white;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            text-transform: uppercase;
            transition: background-color 0.3s;
        }

        .email-button:hover {
            background-color: #5e6fa3;
        }

        .email-footer {
            font-size: 14px;
            color: #999;
            margin-top: 30px;
        }

        .email-footer p {
            margin-bottom: 10px;
        }

        @media (max-width: 600px) {
            .email-container {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="https://via.placeholder.com/120x120/667eea/ffffff?text=Logo" alt="Logo">
        </div>
        <div class="email-body">
            <h2>Password Reset Request</h2>
            <p>Hello, {{ $user->name }}!</p>
            <p>We received a request to reset your password. Click the button below to reset your password.</p>
            <a href="{{ route('password.reset', ['token' => $token]) }}" class="email-button">Reset Password</a>
            <p>If you didn't request this, please ignore this email.</p>
        </div>
        <div class="email-footer">
            <p>Regards,</p>
            <p>The Admin Team</p>
            <p><small>If you have any issues, feel free to reach out to us at support@example.com.</small></p>
        </div>
    </div>
</body>
</html>
