@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-header">
        <h2>Welcome Back</h2>
        <p class="text-muted">Please login to your account</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('loginMatch') }}" method="POST">
        @csrf
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            <label for="email"><i class="fas fa-envelope me-2"></i>Email address</label>
        </div>
        
        <div class="form-floating mb-4">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>
    </form>

    <button type="button" class="btn btn-secondary mt-3" onclick="window.location.href='{{ route('register') }}'">
        <i class="fas fa-user-plus me-2"></i>Create New Account
    </button>

    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('inspector.register.form') }}'">
        <i class="fas fa-user-shield me-2"></i>Inspector Registration
    </button>
@endsection
