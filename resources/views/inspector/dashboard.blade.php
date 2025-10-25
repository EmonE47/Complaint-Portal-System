@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspector Dashboard - Online GD System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/inspector_dash.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Hamburger Menu -->
<button class="menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
</button>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>NAME OF STATION</h2>
            {{-- <p>Inspector Dashboard</p> --}}
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#" data-section="complaints"><i class="fas fa-clipboard-list"></i> Complaints</a></li>
            <li><a href="#" data-section="personnel"><i class="fas fa-users"></i> Personnel</a></li>
            <li><a href="#" data-section="reports"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="#" data-section="settings"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h1>Inspector Dashboard</h1>
            <div class="user-info">
                <div class="user-avatar">ID</div>
                <span>Inspector {{ Auth::user()->name ?? 'User' }}</span>
               <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</button>
                </form>
            </div>
        </div>
        
        <!-- Dashboard Section -->
        <div id="dashboard" class="section active">
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Total Complaints</div>
                        <div class="card-icon total">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                    <div class="card-value">{{ count($complaints) }}</div>
                    <div class="card-footer">All time complaints</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Pending</div>
                        <div class="card-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="card-value">{{ $complaints->where('status', 'pending')->count() }}</div>
                    <div class="card-footer">Awaiting action</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Under Investigation</div>
                        <div class="card-icon in-progress">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="card-value">{{ $complaints->where('status', 'under_investigation')->count() }}</div>
                    <div class="card-footer">Active investigations</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Sub-Inspectors</div>
                        <div class="card-icon si">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div class="card-value">{{ count($subInspectors) }}</div>
                    <div class="card-footer">Active personnel</div>
                </div>
            </div>
            
            <div class="dashboard-row row">
                <div class="col-lg-8" id="adj_com" >
                    <div class="content-section content-section-main">
                        <div class="section-header">
                            <h2 class="section-title">Complaints Management</h2>
                            <div class="d-flex gap-3 align-items-center">
                                <button class="btn btn-primary create-complaint-btn">
                                    <i class="fas fa-plus me-1"></i> Create Complaint
                                </button>
                                <div class="search-box">
                                    <input type="text" class="search-input" placeholder="Search complaints...">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                        </div>
                        
                        <table class="complaints-table">
                            <thead class="table_heading">
                                <tr>
                                    <th>Complaint ID</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Complainant</th>
                                    <th>Assigned SI</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($complaints as $complaint)
                            <tr>
                                <td>#CMP{{ str_pad($complaint->id, 7, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ ucfirst($complaint->complaint_type) }}</td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        switch ($complaint->status) {
                                            case 'pending':
                                                $statusClass = 'status-pending';
                                                break;
                                            case 'under_investigation':
                                                $statusClass = 'status-under-investigation';
                                                break;
                                            case 'in_progress':
                                                $statusClass = 'status-in-progress';
                                                break;
                                            case 'resolved':
                                                $statusClass = 'status-resolved';
                                                break;
                                            case 'assigned':
                                                $statusClass = 'status-assigned';
                                                break;
                                            default:
                                                $statusClass = 'status-pending';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $complaint->status)) }}</span>
                                </td>
                                <td>{{ $complaint->complainant_name }}</td>
                                <td>
                                    @if ($complaint->currentAssignment && $complaint->currentAssignment->user)
                                        SI {{ $complaint->currentAssignment->user->name }}
                                    @else
                                        Not Assigned
                                    @endif
                                </td>
                                <td>
                                    @if ($complaint->status === 'pending')
                                        <button class="btn btn-sm btn-primary assign-si-btn" data-complaint-id="{{ $complaint->id }}" data-complaint-number="#CMP{{ str_pad($complaint->id, 7, '0', STR_PAD_LEFT) }}" data-action="assign">
                                            <i class="fas fa-user-plus me-1"></i>Assign SI
                                        </button>
                                    @elseif ($complaint->currentAssignment)
                                        <button class="btn btn-sm btn-warning reassign-si-btn" data-complaint-id="{{ $complaint->id }}" data-complaint-number="#CMP{{ str_pad($complaint->id, 7, '0', STR_PAD_LEFT) }}" data-action="reassign">
                                            <i class="fas fa-exchange-alt me-1"></i>Reassign SI
                                        </button>
                                    @else
                                        <button class="action-btn view-details-btn" data-complaint-id="{{ $complaint->id }}">View Details</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="col-lg-6" gridcar>
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h3 class="sidebar-card-title">Sub-Inspectors</h3>
                            <span class="badge bg-primary">{{ count($subInspectors) }} Active</span>
                        </div>
                        <ul class="user-list">
                            @foreach ($subInspectors as $si)
                            <li class="user-item">
                                <div class="user-avatar-sm">{{ strtoupper(substr($si->name, 0, 2)) }}</div>
                                <div class="user-details">
                                    <div class="user-name">{{ $si->name }}</div>
                                    <div class="user-meta">{{ $si->active_cases_count }} Active Cases</div>
                                </div>
                                <div class="user-status status-active">Active</div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="sidebar-card" class=>
                        <div class="sidebar-card-header">
                            <h3 class="sidebar-card-title">User Management</h3>
                        </div>
                        <ul class="user-list">
                            @foreach ($usersForPromotion as $user)
                            <li class="user-item">
                                <div class="user-avatar-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                <div class="user-details">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-meta">{{ $user->email }}</div>
                                </div>
                                <form action="{{ route('inspector.promoteToSI') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="promote-btn">Promote to SI</button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h3 class="sidebar-card-title">Recent Activity</h3>
                        </div>
                        <div class="activity-list">
                            @foreach ($statusHistories as $history)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div class="activity-details">
                                    <div class="activity-title">#CMP{{ str_pad($history->complaint->id, 7, '0', STR_PAD_LEFT) }} Status Updated</div>
                                    <div class="activity-meta">{{ $history->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaints Section -->
        <div id="complaints" class="section">
            <div class="content-section section-content">
                <div class="section-header">
                    <h2 class="section-title">All Complaints</h2>
                    <div class="d-flex gap-3 align-items-center">
                        <button class="btn btn-primary create-complaint-btn">
                            <i class="fas fa-plus me-1"></i> Create Complaint
                        </button>
                        <select class="form-control" id="statusFilter" style="width: 200px;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="assigned">Assigned</option>
                            <option value="under_investigation">Under Investigation</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                        <div class="search-box">
                            <input type="text" class="search-input-complaints" placeholder="Search complaints...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>
                
                <table class="complaints-table">
                    <thead class="table_heading">
                        <tr>
                            <th>Complaint ID</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Complainant</th>
                            <th>Contact</th>
                            <th>Incident Date</th>
                            <th>Assigned SI</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="complaintsTableBody">
                    @foreach ($complaints as $complaint)
                    <tr data-status="{{ $complaint->status }}">
                        <td>#CMP{{ str_pad($complaint->id, 7, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ ucfirst($complaint->complaint_type) }}</td>
                        <td>
                            @php
                                $statusClass = '';
                                switch ($complaint->status) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        break;
                                    case 'under_investigation':
                                        $statusClass = 'status-under-investigation';
                                        break;
                                    case 'in_progress':
                                        $statusClass = 'status-in-progress';
                                        break;
                                    case 'resolved':
                                        $statusClass = 'status-resolved';
                                        break;
                                    case 'assigned':
                                        $statusClass = 'status-assigned';
                                        break;
                                    default:
                                        $statusClass = 'status-pending';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $complaint->status)) }}</span>
                        </td>
                        <td>{{ $complaint->complainant_name }}</td>
                        <td>{{ $complaint->complainant_contact }}</td>
                        <td>{{ \Carbon\Carbon::parse($complaint->incident_datetime)->format('d M Y') }}</td>
                        <td>
                            @if ($complaint->currentAssignment && $complaint->currentAssignment->user)
                                SI {{ $complaint->currentAssignment->user->name }}
                            @else
                                Not Assigned
                            @endif
                        </td>
                        <td>
                            @if ($complaint->status === 'pending')
                                <button class="btn btn-sm btn-primary assign-si-btn" data-complaint-id="{{ $complaint->id }}" data-complaint-number="#CMP{{ str_pad($complaint->id, 7, '0', STR_PAD_LEFT) }}" data-action="assign">
                                    <i class="fas fa-user-plus me-1"></i>Assign SI
                                </button>
                            @elseif ($complaint->currentAssignment)
                                <button class="btn btn-sm btn-warning reassign-si-btn" data-complaint-id="{{ $complaint->id }}" data-complaint-number="#CMP{{ str_pad($complaint->id, 7, '0', STR_PAD_LEFT) }}" data-action="reassign">
                                    <i class="fas fa-exchange-alt me-1"></i>Reassign SI
                                </button>
                            @else
                                <button class="action-btn view-details-btn" data-complaint-id="{{ $complaint->id }}">Details</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Personnel Section -->
        <div id="personnel" class="section">
            <div class="content-section section-content">
                <div class="section-header">
                    <h2 class="section-title">Personnel Management</h2>
                    <div class="search-box">
                        <input type="text" class="search-input-personnel" placeholder="Search personnel...">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="mb-3">Sub-Inspectors</h4>
                        <table class="complaints-table">
                            <thead class="table_heading">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Active Cases</th>
                                    <th>Resolved Cases</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="personnelTableBody">
                            @foreach ($subInspectors as $si)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-2">{{ strtoupper(substr($si->name, 0, 2)) }}</div>
                                        {{ $si->name }}
                                    </div>
                                </td>
                                <td>{{ $si->email }}</td>
                                <td><span class="badge bg-warning">{{ $si->active_cases_count }}</span></td>
                                <td><span class="badge bg-success">{{ $si->resolved_cases_count ?? 0 }}</span></td>
                                <td><span class="user-status status-active">Active</span></td>
                                <td>
                                    <button class="action-btn view-si-details" data-si-id="{{ $si->id }}">View Profile</button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Eligible for Promotion</h3>
                            </div>
                            <ul class="user-list">
                                @foreach ($usersForPromotion as $user)
                                <li class="user-item">
                                    <div class="user-avatar-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-meta">{{ $user->email }}</div>
                                    </div>
                                    <form action="{{ route('inspector.promoteToSI') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <button type="submit" class="promote-btn">Promote</button>
                                    </form>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div id="reports" class="section">
            <div class="content-section section-content">
                <div class="section-header">
                    <h2 class="section-title">Reports & Analytics</h2>
                    <button class="btn btn-primary" id="generateReportBtn">
                        <i class="fas fa-file-pdf me-1"></i> Generate Report
                    </button>
                </div>
                
                <div class="row mb-4" id="report_header">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Report Type</label>
                            <select class="form-control" id="reportType">
                                <option value="summary">Summary Report</option>
                                <option value="detailed">Detailed Report</option>
                                <option value="personnel">Personnel Report</option>
                                <option value="statistics">Statistics Report</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" id="reportDateFrom">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" id="reportDateTo">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Status Filter</label>
                            <select class="form-control" id="reportStatusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="under_investigation">Under Investigation</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Complaint Statistics</h3>
                            </div>
                            <div class="p-3">
                                <div class="stat-row">
                                    <span>Total Complaints:</span>
                                    <strong>{{ count($complaints) }}</strong>
                                </div>
                                <div class="stat-row">
                                    <span>Pending:</span>
                                    <strong class="text-warning">{{ $complaints->where('status', 'pending')->count() }}</strong>
                                </div>
                                <div class="stat-row">
                                    <span>Under Investigation:</span>
                                    <strong class="text-info">{{ $complaints->where('status', 'under_investigation')->count() }}</strong>
                                </div>
                                <div class="stat-row">
                                    <span>Resolved:</span>
                                    <strong class="text-success">{{ $complaints->where('status', 'resolved')->count() }}</strong>
                                </div>
                                <div class="stat-row">
                                    <span>Resolution Rate:</span>
                                    <strong>{{ count($complaints) > 0 ? round(($complaints->where('status', 'resolved')->count() / count($complaints)) * 100, 2) : 0 }}%</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Complaint Types Breakdown</h3>
                            </div>
                            <div class="p-3">
                                @php
                                    $typeCount = $complaints->groupBy('complaint_type')->map->count();
                                @endphp
                                @foreach ($typeCount as $type => $count)
                                <div class="stat-row">
                                    <span>{{ ucfirst($type) }}:</span>
                                    <strong>{{ $count }}</strong>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Recent Reports</h3>
                            </div>
                            <table class="complaints-table">
                                <thead class="table_heading">
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Type</th>
                                        <th>Generated Date</th>
                                        <th>Date Range</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#RPT001</td>
                                        <td>Summary Report</td>
                                        <td>{{ now()->format('d M Y') }}</td>
                                        <td>01 Oct 2025 - 04 Oct 2025</td>
                                        <td><button class="action-btn">Download</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div id="settings" class="section">
                
                <div class="row" id="adj_set">
                    <div class="col-lg-8" id="adj_st" >
                        <div class="sidebar-card">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Profile Information</h3>
                            </div>
                            <div class="p-4">
                                <form action="{{ route('inspector.updateProfile') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="text-center mb-4">
                                        <div class="user-avatar" style="width: 100px; height: 100px; font-size: 40px; margin: 0 auto;">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'ID', 0, 2)) }}
                                        </div>
                                        <div class="mt-3">
                                            <label for="profilePhoto" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-camera me-1"></i> Change Photo
                                            </label>
                                            <input type="file" id="profilePhoto" name="profile_photo" class="d-none" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email ?? '' }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Badge Number</label>
                                                <input type="text" class="form-control" name="badge_number" value="{{ Auth::user()->badge_number ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Station/Department</label>
                                        <input type="text" class="form-control" name="department" value="{{ Auth::user()->department ?? '' }}">
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="sidebar-card mt-4" id="pw_card">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Change Password</h3>
                            </div>
                            <div class="p-4">
                                <form action="{{ route('inspector.changePassword') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" name="new_password_confirmation" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-1"></i> Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="sidebar-card" id="acc_info">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Account Information</h3>
                            </div>
                            <div class="p-3">
                                <div class="stat-row">
                                    <span>Role:</span>
                                    <strong>Inspector</strong>
                                </div>
                                <div class="stat-row">
                                    <span>Member Since:</span>
                                    <strong>{{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : 'N/A' }}</strong>
                                </div>
                                <div class="stat-row">
                                    <span>Last Login:</span>
                                    <strong>{{ now()->format('d M Y, h:i A') }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-card mt-3">
                            <div class="sidebar-card-header">
                                <h3 class="sidebar-card-title">Notification Settings</h3>
                            </div>
                            <div class="p-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                                    <label class="form-check-label" for="emailNotif">
                                        Email Notifications
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="smsNotif">
                                    <label class="form-check-label" for="smsNotif">
                                        SMS Notifications
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="caseUpdates" checked>
                                    <label class="form-check-label" for="caseUpdates">
                                        Case Update Alerts
                                    </label>
                                </div>
                                <button class="btn btn-sm btn-primary w-100">Save Preferences</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Assign Sub-Inspector
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignForm" action="{{ route('inspector.assignCase') }}" method="POST">
                    @csrf
                    <input type="hidden" id="complaintId" name="complaint_id">
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="card-icon total mx-auto mb-3">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <p class="mb-0">Assign SI to complaint</p>
                            <h5 class="fw-bold text-primary" id="complaintNumber">#CMP2024002</h5>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Select Sub-Inspector</label>
                            <select class="form-control" id="inspectorSelect" name="user_id" required>
                                <option value="">Choose a Sub-Inspector</option>
                                @foreach ($subInspectors as $si)
                                <option value="{{ $si->id }}">{{ $si->name }} ({{ $si->active_cases_count }} active cases)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Assignment Notes</label>
                            <textarea class="form-control" id="assignmentNotes" name="assignment_notes" rows="3" placeholder="Add any notes or instructions for the Sub-Inspector..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i> Assign Case
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Complaint Modal -->
    <div class="modal fade" id="createComplaintModal" tabindex="-1" aria-labelledby="createComplaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createComplaintModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Create New Complaint
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createComplaintForm" action="{{ route('inspector.createComplaint') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Complaint Type</label>
                                    <select class="form-control" name="complaint_type" required>
                                        <option value="">Select Type</option>
                                        <option value="theft">Theft</option>
                                        <option value="assault">Assault</option>
                                        <option value="fraud">Fraud</option>
                                        <option value="harassment">Harassment</option>
                                        <option value="lost_item">Lost Item</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Priority</label>
                                    <select class="form-control" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Complainant Name</label>
                            <input type="text" class="form-control" name="complainant_name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Complainant Contact</label>
                            <input type="text" class="form-control" name="complainant_contact" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Incident Location</label>
                            <input type="text" class="form-control" name="incident_location" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Incident Date & Time</label>
                            <input type="datetime-local" class="form-control" name="incident_datetime" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Detailed description of the incident..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Evidence/Attachments</label>
                            <input type="file" class="form-control" name="evidence" multiple>
                            <small class="form-text text-muted">Upload any relevant documents, photos, or evidence files</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Create Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Complaint Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="complaintDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="d-flex align-items-center">
            <div class="spinner-border text-primary me-3" role="status"></div>
            <span>Processing...</span>
        </div>
    </div>

    <style>
        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .stat-row:last-child {
            border-bottom: none;
        }
        .btn-group {
            display: flex;
            gap: 5px;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation between sections
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all links
                    document.querySelectorAll('.sidebar-menu a').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Hide all sections
                    document.querySelectorAll('.section').forEach(section => {
                        section.classList.remove('active');
                    });
                    
                    // Show selected section
                    const sectionId = this.getAttribute('data-section');
                    document.getElementById(sectionId).classList.add('active');
                    
                    // Update header title
                    const headerTitle = document.querySelector('.header h1');
                    const titleMap = {
                        'dashboard': 'Inspector Dashboard',
                        'complaints': 'Complaints Management',
                        'personnel': 'Personnel Management',
                        'reports': 'Reports & Analytics',
                        'settings': 'Settings'
                    };
                    headerTitle.textContent = titleMap[sectionId] || this.textContent.trim();
                });
            });
            
            // Assignment modal functionality
            document.querySelectorAll('.assign-si-btn, .reassign-si-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const complaintId = this.getAttribute('data-complaint-id');
                    const complaintNumber = this.getAttribute('data-complaint-number');
                    const action = this.getAttribute('data-action');

                    document.getElementById('complaintId').value = complaintId;
                    document.getElementById('complaintNumber').textContent = complaintNumber;

                    // Update modal title and button based on action
                    const modalTitle = document.getElementById('assignModalLabel');
                    const submitBtn = document.querySelector('#assignForm button[type="submit"]');

                    if (action === 'reassign') {
                        modalTitle.innerHTML = '<i class="fas fa-exchange-alt me-2"></i>Reassign Sub-Inspector';
                        submitBtn.innerHTML = '<i class="fas fa-check me-1"></i> Reassign Case';
                    } else {
                        modalTitle.innerHTML = '<i class="fas fa-user-plus me-2"></i>Assign Sub-Inspector';
                        submitBtn.innerHTML = '<i class="fas fa-check me-1"></i> Assign Case';
                    }

                    const modal = new bootstrap.Modal(document.getElementById('assignModal'));
                    modal.show();
                });
            });

            // Create complaint modal functionality
            document.querySelectorAll('.create-complaint-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = new bootstrap.Modal(document.getElementById('createComplaintModal'));
                    modal.show();
                });
            });

            // View details modal functionality
            document.querySelectorAll('.view-details-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const complaintId = this.getAttribute('data-complaint-id');
                    // Here you would fetch the complaint details via AJAX
                    const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
                    document.getElementById('complaintDetailsContent').innerHTML = '<p>Loading complaint details...</p>';
                    modal.show();
                    
                    // Simulate loading details
                    setTimeout(() => {
                        document.getElementById('complaintDetailsContent').innerHTML = `
                            <div class="complaint-details">
                                <h6>Complaint ID: #CMP${complaintId}</h6>
                                <p><strong>Status:</strong> Under Investigation</p>
                                <p><strong>Type:</strong> Theft</p>
                                <p><strong>Description:</strong> Detailed description would appear here...</p>
                            </div>
                        `;
                    }, 500);
                });
            });
            
            // Assignment form submission
            document.getElementById('assignForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                // Show loading spinner
                document.getElementById('loadingSpinner').style.display = 'block';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Assigning...';

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading spinner
                    document.getElementById('loadingSpinner').style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('assignModal'));
                        modal.hide();

                        // Show success message
                        showAlert('Case assigned successfully!', 'success');

                        // Reload the page to update the table
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('Error assigning case: ' + (data.message || 'Unknown error'), 'danger');
                    }
                })
                .catch(error => {
                    // Hide loading spinner
                    document.getElementById('loadingSpinner').style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    showAlert('Case Assigned', 'danger');
                    console.error('Error:', error);
                });
            });
            
            // Search functionality for dashboard
            document.querySelector('.search-input').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.complaints-table tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // Search functionality for complaints section
            const complaintsSearchInput = document.querySelector('.search-input-complaints');
            if (complaintsSearchInput) {
                complaintsSearchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#complaintsTableBody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // Status filter for complaints section
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const selectedStatus = this.value;
                    const rows = document.querySelectorAll('#complaintsTableBody tr');
                    
                    rows.forEach(row => {
                        if (selectedStatus === '') {
                            row.style.display = '';
                        } else {
                            const rowStatus = row.getAttribute('data-status');
                            row.style.display = rowStatus === selectedStatus ? '' : 'none';
                        }
                    });
                });
            }

            // Search functionality for personnel section
            const personnelSearchInput = document.querySelector('.search-input-personnel');
            if (personnelSearchInput) {
                personnelSearchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#personnelTableBody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // Generate report functionality
            const generateReportBtn = document.getElementById('generateReportBtn');
            if (generateReportBtn) {
                generateReportBtn.addEventListener('click', function() {
                    const reportType = document.getElementById('reportType').value;
                    const dateFrom = document.getElementById('reportDateFrom').value;
                    const dateTo = document.getElementById('reportDateTo').value;
                    const statusFilter = document.getElementById('reportStatusFilter').value;

                    // Show loading
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
                    this.disabled = true;

                    // Simulate report generation
                    setTimeout(() => {
                        showAlert('Report generated successfully! Download will begin shortly.', 'success');
                        this.innerHTML = '<i class="fas fa-file-pdf me-1"></i> Generate Report';
                        this.disabled = false;
                    }, 2000);
                });
            }
            
            // Function to show alert messages
            function showAlert(message, type) {
                // Remove any existing alerts
                const existingAlert = document.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                // Create new alert
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show`;
                alert.style.position = 'fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '9999';
                alert.style.minWidth = '300px';
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                document.body.appendChild(alert);
                
                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 5000);
            }
        });
        // Mobile menu functionality
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');

if (menuToggle) {
    menuToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('sidebar-open');
        
        // Prevent body scroll when menu is open on mobile
        if (sidebar.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });
}

// Close menu when clicking on a link (mobile)
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 767.98) {
            menuToggle.classList.remove('active');
            sidebar.classList.remove('active');
            mainContent.classList.remove('sidebar-open');
            document.body.style.overflow = '';
        }
    });
});

// Close menu when clicking outside (mobile)
document.addEventListener('click', function(event) {
    if (window.innerWidth <= 767.98 && 
        sidebar.classList.contains('active') &&
        !sidebar.contains(event.target) &&
        !menuToggle.contains(event.target)) {
        menuToggle.classList.remove('active');
        sidebar.classList.remove('active');
        mainContent.classList.remove('sidebar-open');
        document.body.style.overflow = '';
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 767.98) {
        // Reset mobile menu state on larger screens
        menuToggle.classList.remove('active');
        sidebar.classList.remove('active');
        mainContent.classList.remove('sidebar-open');
        document.body.style.overflow = '';
    }
});
    </script>
</body>
</html>
@endsection