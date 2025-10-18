@extends('layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/si-dashboard.css') }}" rel="stylesheet" />

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Police GD System</h2>
        <p>Sub-Inspector Dashboard</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('si.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="#" data-section="cases"><i class="fas fa-clipboard-list"></i> My Cases</a></li>
        <li><a href="{{ route('si.reports') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="#" class="active" data-section="profile"><i class="fas fa-user"></i> Profile</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1>Profile</h1>
        <div class="user-info">
            <div class="user-avatar">SI</div>
            <span>Sub-Inspector</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</button>
            </form>
        </div>
    </div>

    <!-- Profile Section -->
    <div id="profile" class="section active">
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <h3>Personal Information</h3>
                </div>
                <form id="profileForm" action="{{ route('si.updateProfile') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="labels" for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group">
                            <label class="labels" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="labels" for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="{{ $user->phone ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="labels" for="address">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="3">{{ $user->address ?? '' }}</textarea>
                        </div>
                    </div>
                    @if($inspectorDetails)
                    <div class="form-row">
                        <div class="form-group">
                            <label class="labels" for="police_station">Police Station</label>
                            <input type="text" class="form-control" value="{{ $inspectorDetails->police_station_name ?? 'N/A' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="labels" for="badge_number">Badge Number</label>
                            <input type="text" class="form-control" value="{{ $inspectorDetails->badge_number ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    @endif
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <div class="profile-card">
                <div class="profile-header">
                    <h3>Change Password</h3>
                </div>
                <form id="passwordForm" action="{{ route('inspector.changePassword') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="labels" for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="labels" for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="labels" for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Handle form submission with AJAX or standard submit
        this.submit();
    });

    // Password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Handle form submission
        this.submit();
    });
});
</script>
@endsection
