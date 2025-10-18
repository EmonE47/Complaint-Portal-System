<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
    
        <form action="{{ route('loginMatch') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        <button type="button" class="register-btn" onclick="window.location.href='{{ route('register') }}'">
            Create New Account
        </button>
        <button type="button" class="register-btn" onclick="window.location.href='{{ route('inspector.register.form') }}'">
            Inspector Registration
        </button>
    </div>
</body>
</html>
