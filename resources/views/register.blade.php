@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-header">
        <h2>Create Account</h2>
        <p class="text-muted">Fill in your information to create an account</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('registerSave') }}" method="POST">
        @csrf

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Full Name" required>
            <label for="name"><i class="fas fa-user me-2"></i>Full Name</label>
        </div>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
            <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="6">
            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
        </div>

        <div class="form-floating mb-4">
            <input type="password" class="form-control" id="confirm_password" name="password_confirmation" placeholder="Confirm Password" required>
            <label for="confirm_password"><i class="fas fa-lock me-2"></i>Confirm Password</label>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Register
        </button>
    </form>

    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('login') }}'">
        <i class="fas fa-arrow-left me-2"></i>Back to Login
    </button>
@endsection
