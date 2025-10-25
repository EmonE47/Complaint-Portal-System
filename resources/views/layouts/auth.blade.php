<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Police Complaint Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #00416A 0%, #E4E5E6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            margin: 2rem;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-header h2 {
            color: #00416A;
            font-weight: 600;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #00416A;
            box-shadow: 0 0 0 0.25rem rgba(0, 65, 106, 0.25);
        }
        .btn-primary {
            background-color: #00416A;
            border-color: #00416A;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #002d4a;
            border-color: #002d4a;
        }
        .btn-secondary {
            background-color: transparent;
            border: 2px solid #00416A;
            color: #00416A;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            margin-top: 1rem;
            width: 100%;
        }
        .btn-secondary:hover {
            background-color: #00416A;
            border-color: #00416A;
            color: white;
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .select-wrapper {
            position: relative;
        }
        .select-wrapper:after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
    </style>
    @yield('additional_css')
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>