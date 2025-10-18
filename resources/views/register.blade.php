<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="form-container">
        <h2>Create Account</h2>

        <form action="{{ route('registerSave') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="password_confirmation" required>
            </div>

            <button type="submit">Register</button>
        </form>

        <button type="button" class="login-btn" onclick="window.location.href='{{ route('login') }}'">
            Back to Login
        </button>
    </div>
</body>
</html>
