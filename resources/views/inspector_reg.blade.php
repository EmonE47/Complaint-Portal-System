@extends('layouts.auth')

@section('title', 'Inspector Registration')

@section('content')
    <div class="auth-header">
        <h2>Inspector Registration</h2>
        <p class="text-muted">Register as a law enforcement officer</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inspector.register') }}" method="POST">
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
            <input type="tel" class="form-control" id="number" name="number" value="{{ old('number') }}" placeholder="Phone Number" required>
            <label for="number"><i class="fas fa-phone me-2"></i>Phone Number</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nid_number" name="nid_number" value="{{ old('nid_number') }}" placeholder="NID Number" required>
            <label for="nid_number"><i class="fas fa-id-card me-2"></i>NID Number</label>
        </div>

        <div class="form-floating mb-3">
            <select class="form-select" id="rank" name="rank" required>
                <option value="">Select Rank</option>
                <option value="inspector" {{ old('rank') == 'inspector' ? 'selected' : '' }}>Inspector</option>
                <option value="si" {{ old('rank') == 'si' ? 'selected' : '' }}>Sub-Inspector (SI)</option>
                <option value="asi" {{ old('rank') == 'asi' ? 'selected' : '' }}>Assistant Sub-Inspector (ASI)</option>
            </select>
            <label for="rank"><i class="fas fa-star me-2"></i>Rank</label>
        </div>

        <div class="form-floating mb-3">
            <select class="form-select" id="police_station_id" name="police_station_id" required>
                <option value="">Select Police Station</option>
                @foreach($policeStations as $station)
                    <option value="{{ $station->id }}" {{ old('police_station_id') == $station->id ? 'selected' : '' }}>
                        {{ $station->name }}
                    </option>
                @endforeach
            </select>
            <label for="police_station_id"><i class="fas fa-building me-2"></i>Police Station</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="6">
            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
        </div>

        <div class="form-floating mb-4">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
            <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirm Password</label>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-shield me-2"></i>Register as Inspector
        </button>
    </form>

    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('login') }}'">
        <i class="fas fa-arrow-left me-2"></i>Back to Login
    </button>
@endsection
