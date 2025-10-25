@extends('layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/si-dashboard.css') }}" rel="stylesheet" />
<!-- Hamburger Menu -->
<button class="menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
</button>
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Police GD System</h2>
    </div>
    <ul class="sidebar-menu">
        <li><a href="#" data-section="dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="#" data-section="cases"><i class="fas fa-clipboard-list"></i> My Cases</a></li>
        <li><a href="#" data-section="reports"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="#" data-section="messages"><i class="fas fa-envelope"></i> Messages</a></li>
        <li><a href="#" data-section="profile"><i class="fas fa-user"></i> Profile</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1>Sub-Inspector Dashboard</h1>
        <div class="user-info">
            <div class="user-avatar">SI</div>
            <span>Sub-Inspector</span>
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
                    <div class="card-title">Total Cases</div>
                    <div class="card-icon total">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="card-value">{{ count($assignedComplaints) }}</div>
                <div class="card-footer">All assigned cases</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Pending</div>
                    <div class="card-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="card-value">{{ $assignedComplaints->where('status', 'pending')->count() }}</div>
                <div class="card-footer">Awaiting action</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Investigating</div>
                    <div class="card-icon investigating">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="card-value">{{ $assignedComplaints->where('status', 'under_investigation')->count() }}</div>
                <div class="card-footer">Active investigations</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Resolved</div>
                    <div class="card-icon resolved">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="card-value">{{ $assignedComplaints->where('status', 'resolved')->count() }}</div>
                <div class="card-footer">Successfully resolved</div>
            </div>
        </div>
    </div>

    <!-- Cases Section (in-page) -->
    <div id="cases" class="section">
        <div class="row" style="margin: 0 40px 40px;">
            <div class="col-lg-12">
                <div class="content-section" id="Casss">
                    <div class="section-header">
                        <h2 class="section-title">My Assigned Cases</h2>
                        <div class="search-box">
                            <input type="text" class="search-input" placeholder="Search cases...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>

                    <table class="cases-table">
                        <thead>
                            <tr>
                                <th>Case ID</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Complainant</th>
                                <th>Assigned Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($assignedComplaints as $complaint)
                        <tr>
                            <td class="fw-bold text-primary">#CMP{{ str_pad($complaint->id, 7, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $iconClass = '';
                                        switch ($complaint->complaint_type) {
                                            case 'theft':
                                                $iconClass = 'fas fa-gem';
                                                break;
                                            case 'assault':
                                                $iconClass = 'fas fa-user-injured';
                                                break;
                                            case 'fraud':
                                                $iconClass = 'fas fa-file-invoice-dollar';
                                                break;
                                            case 'harassment':
                                                $iconClass = 'fas fa-exclamation-circle';
                                                break;
                                            case 'lost_item':
                                                $iconClass = 'fas fa-search';
                                                break;
                                            default:
                                                $iconClass = 'fas fa-clipboard-list';
                                        }
                                    @endphp
                                    <i class="{{ $iconClass }} me-2 text-muted"></i>
                                    {{ ucfirst(str_replace('_', ' ', $complaint->complaint_type)) }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusClass = '';
                                    switch ($complaint->status) {
                                        case 'pending':
                                            $statusClass = 'status-pending';
                                            break;
                                        case 'under_investigation':
                                            $statusClass = 'status-investigating';
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
                                        case 'rejected':
                                            $statusClass = 'status-rejected';
                                            break;
                                        case 'closed':
                                            $statusClass = 'status-closed';
                                            break;
                                        default:
                                            $statusClass = 'status-pending';
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $complaint->status)) }}</span>
                            </td>
                            <td>{{ $complaint->complainant_name }}</td>
                            <td>{{ $complaint->currentAssignment ? $complaint->currentAssignment->assigned_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <button class="btn action-btn" onclick="toggleDropdown({{ $complaint->id }})" id="actionBtn{{ $complaint->id }}">
                                    Actions <i class="fas fa-chevron-down dropdown-arrow" id="arrow{{ $complaint->id }}"></i>
                                </button>
                                <div id="dropdown{{ $complaint->id }}" class="custom-dropdown-menu" style="display: none; position: fixed; z-index: 10000; background: white; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-width: 180px;">
                                    <div style="padding: 8px 0;">
                                        <a class="dropdown-item view-details-btn" href="#" data-complaint-id="{{ $complaint->id }}" style="display: block; padding: 8px 16px; color: #333; text-decoration: none;"><i class="fas fa-eye me-2"></i>View Details</a>
                                        <a class="dropdown-item update-status-btn" href="#" data-complaint-id="{{ $complaint->id }}" style="display: block; padding: 8px 16px; color: #333; text-decoration: none;"><i class="fas fa-edit me-2"></i>Update Status</a>
                                        <a class="dropdown-item chat-btn" href="#" data-complaint-id="{{ $complaint->id }}" style="display: block; padding: 8px 16px; color: #333; text-decoration: none;"><i class="fas fa-comments me-2"></i>Chat with User</a>
                                        <a class="dropdown-item add-notes-btn" href="#" data-complaint-id="{{ $complaint->id }}" style="display: block; padding: 8px 16px; color: #333; text-decoration: none;"><i class="fas fa-file-alt me-2"></i>Add Notes</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12">
                <!-- Case Statistics Card -->
                <div class="sidebar-card" id="case_s">
                    <div class="sidebar-card-header">
                        <h3 class="sidebar-card-title">Case Statistics</h3>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ $assignedComplaints->where('status', 'pending')->count() }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $assignedComplaints->where('status', 'under_investigation')->count() }}</div>
                            <div class="stat-label">Investigating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $assignedComplaints->where('status', 'resolved')->count() }}</div>
                            <div class="stat-label">Resolved</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $assignedComplaints->where('status', 'closed')->count() }}</div>
                            <div class="stat-label">Closed</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="sidebar-card" id="rec">
                    <div class="sidebar-card-header">
                        <h3 class="sidebar-card-title">Recent Activity</h3>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-title">#CMP2024001 Status Updated</div>
                                <div class="activity-meta">2 hours ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-title">Notes added to #CMP2024002</div>
                                <div class="activity-meta">5 hours ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-title">#CMP2024003 Resolved</div>
                                <div class="activity-meta">1 day ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-assignment"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-title">New case #CMP2024004 assigned</div>
                                <div class="activity-meta">2 days ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Section (in-page) -->
    <!-- Profile Section (in-page) -->
    <div id="profile" class="section">
        <div class="content-section" id="profile">
            <div class="section-header">
                <h2 class="section-title">My Profile</h2>
            </div>

            <form id="profile-form" action="{{ route('si.profile') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" name="phone_no" value="{{ Auth::user()->phone_no ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Voter ID Number</label>
                        <input type="text" class="form-control" name="voter_id_number" value="{{ Auth::user()->voter_id_number ?? '' }}">
                    </div>
                </div>

                <button type="submit" class="btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
    <div id="messages" class="section">
        <div class="row" style="margin: 20px 40px; gap:20px;">
            <div class="col-lg-12">
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h3 class="sidebar-card-title">Conversations</h3>
                    </div>
                    <div class="user-list">
                        @foreach($assignedComplaints as $c)
                        <div class="user-item">
                            <div class="user-avatar-sm">{{ strtoupper(substr($c->complainant_name,0,1)) }}</div>
                            <div class="user-details">
                                <div class="user-name">#CMP{{ str_pad($c->id,7,'0',STR_PAD_LEFT) }} - {{ $c->complainant_name }}</div>
                                <div class="user-meta">{{ ucfirst(str_replace('_',' ',$c->status)) }}</div>
                            </div>
                            <div>
                                <button class="btn btn-outline chat-btn" data-complaint-id="{{ $c->id }}">Open</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-8">
                <div class="content-section" id="Content_of_My_cases">
                    <div class="section-header">
                        <h2 class="section-title">Messages</h2>
                    </div>
                    <div id="messages-panel">
                        <div id="messages-empty" style="text-align:center;color:#7f8c8d;padding:50px">Select a conversation on the left to start chatting.</div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Reports Section -->
    <div id="reports" class="section">
        <div class="content-section" style="margin:20px;">
            <div class="section-header">
                <h2 class="section-title">Reports & Analytics</h2>
                <div class="d-flex" style="gap:10px;align-items:center;">
                    <input type="date" id="report-from" class="form-control" />
                    <input type="date" id="report-to" class="form-control" />
                    <select id="report-status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="under_investigation">Under Investigation</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                    <button id="generateReportBtn" class="btn btn-primary">Generate</button>
                </div>
            </div>

            <div class="dashboard-cards" style="margin-top:20px;">
                <div class="card">
                    <div class="card-title">Total Assigned</div>
                    <div class="card-value">{{ count($assignedComplaints) }}</div>
                </div>
                <div class="card">
                    <div class="card-title">Open</div>
                    <div class="card-value">{{ $assignedComplaints->where('status','pending')->count() }}</div>
                </div>
                <div class="card">
                    <div class="card-title">Investigations</div>
                    <div class="card-value">{{ $assignedComplaints->where('status','under_investigation')->count() }}</div>
                </div>
                <div class="card">
                    <div class="card-title">Resolved</div>
                    <div class="card-value">{{ $assignedComplaints->where('status','resolved')->count() }}</div>
                </div>
            </div>
            


























            <div style="margin-top:20px;">
                <table class="cases-table">
                    <thead>
                        <tr>
                            <th>Case ID</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Complainant</th>
                            <th>Assigned Date</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        @foreach($assignedComplaints as $c)
                        <tr>
                            <td>#CMP{{ str_pad($c->id,7,'0',STR_PAD_LEFT) }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$c->complaint_type)) }}</td>
                            <td>{{ ucwords(str_replace('_',' ',$c->status)) }}</td>
                            <td>{{ $c->complainant_name }}</td>
                            <td>{{ $c->currentAssignment ? $c->currentAssignment->assigned_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusUpdateModalLabel">
                        <i class="fas fa-edit me-2"></i>Update Case Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusUpdateForm" action="{{ route('si.updateStatus') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="card-icon investigating mx-auto mb-3">
                                <i class="fas fa-search"></i>
                            </div>
                            <p class="mb-0">Update status for case</p>
                            <h5 class="fw-bold text-primary" id="caseNumber">#CMP2024001</h5>
                        </div>

                        <input type="hidden" id="caseId" name="complaint_id">

                        <div class="form-group">
                            <label class="form-label">New Status</label>
                            <select class="form-control" id="statusSelect" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="under_investigation">Under Investigation</option>
                                <option value="resolved">Resolved</option>
                                <option value="rejected">Rejected</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" id="statusRemarks" name="remarks" rows="3" placeholder="Add any remarks about this status update..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.chat')

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="d-flex align-items-center">
            <div class="spinner-border text-primary me-3" role="status"></div>
            <span>Updating status...</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar navigation for in-page sections (reports, messages)
    document.querySelectorAll('.sidebar-menu a[data-section]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // remove active on other links
            document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');

            // hide all sections and show the selected one
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            const id = this.getAttribute('data-section');
            const section = document.getElementById(id);
            if (section) section.classList.add('active');
        });
    });

    // Persist sidebar active item across page navigations (so clicking My Cases / Profile keeps sidebar appearance)
    // Store active link key (data-section or href) in localStorage whenever a sidebar link is clicked.
    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        link.addEventListener('click', function(e) {
            try {
                const key = this.getAttribute('data-section') || this.getAttribute('href');
                if (key) localStorage.setItem('siSidebarActive', key);
            } catch (err) {
                // ignore storage errors
            }
        });
    });

    // On load, restore active sidebar item if stored
    try {
        const activeKey = localStorage.getItem('siSidebarActive');
        if (activeKey) {
            // remove active from all and add to the stored one
            document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
            const selectorByData = document.querySelector(`.sidebar-menu a[data-section="${activeKey}"]`);
            const selectorByHref = document.querySelector(`.sidebar-menu a[href="${activeKey}"]`);
            const toActivate = selectorByData || selectorByHref;
            if (toActivate) {
                toActivate.classList.add('active');
                // If it's an in-page section, also show it
                const ds = toActivate.getAttribute('data-section');
                if (ds) {
                    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                    const sec = document.getElementById(ds);
                    if (sec) sec.classList.add('active');
                }
            }
        }
    } catch (err) {
        // ignore localStorage errors
    }

    // Custom dropdown functionality using event delegation
    document.addEventListener('click', function(e) {
        // Handle dropdown button clicks
        if (e.target.classList.contains('action-btn')) {
            e.preventDefault();
            e.stopPropagation();

            const complaintId = e.target.id.replace('actionBtn', '');
            toggleDropdown(complaintId);
        }
        // Close dropdowns when clicking outside
        else if (!e.target.closest('.custom-dropdown-menu') && !e.target.classList.contains('action-btn')) {
            closeAllDropdowns();
        }
    });

    function toggleDropdown(complaintId) {
        // Close all other dropdowns
        closeAllDropdowns();

        const button = document.getElementById('actionBtn' + complaintId);
        const dropdown = document.getElementById('dropdown' + complaintId);
        const arrow = document.getElementById('arrow' + complaintId);

        if (!button || !dropdown) {
            console.error('Button or dropdown not found for complaint ID:', complaintId);
            return;
        }

        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            // Position the dropdown
            const rect = button.getBoundingClientRect();
            dropdown.style.display = 'block';
            dropdown.style.left = rect.left + 'px';
            dropdown.style.top = (rect.bottom + window.scrollY) + 'px';
            // Rotate arrow up
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.style.display = 'none';
            // Rotate arrow down
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
        // Reset all arrows to down position
        document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
            arrow.style.transform = 'rotate(0deg)';
        });
    }

    // Status update modal functionality
    const statusUpdateModal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));

    document.querySelectorAll('.update-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const complaintId = this.getAttribute('data-complaint-id');
            document.getElementById('caseId').value = complaintId;
            document.getElementById('caseNumber').textContent = `#CMP${complaintId.padStart(7, '0')}`;
            statusUpdateModal.show();
            closeAllDropdowns(); // Close dropdown when modal opens
        });
    });

    // View details functionality (placeholder)
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const complaintId = this.getAttribute('data-complaint-id');
            // TODO: Implement view details functionality
            alert(`View details for complaint ID: ${complaintId}`);
            closeAllDropdowns(); // Close dropdown when alert shows
        });
    });

    // Chat functionality
    let currentComplaintId = null;
    let chatModal = null;

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('chat-btn')) {
            e.preventDefault();
            currentComplaintId = e.target.getAttribute('data-complaint-id');
            openChatModal(currentComplaintId);
            closeAllDropdowns(); // Close dropdown when chat modal opens
        }
    });

    function openChatModal(complaintId) {
        // Initialize modal if not already done
        if (!chatModal) {
            chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
        }

        // Load messages
        loadMessages(complaintId);

        // Show modal
        chatModal.show();
    }

    function loadMessages(complaintId) {
        fetch(`/messages/${complaintId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    console.error('Failed to load messages (status ' + response.status + '):', err);
                    const messagesContainer = document.getElementById('chat-messages');
                    messagesContainer.innerHTML = `<p style="text-align: center; color: #e74c3c;">Unable to load messages (HTTP ${response.status}). Check console for details.</p>`;
                    throw new Error('Failed to load messages');
                }).catch(() => {
                    const messagesContainer = document.getElementById('chat-messages');
                    messagesContainer.innerHTML = `<p style="text-align: center; color: #e74c3c;">Unable to load messages (HTTP ${response.status}).</p>`;
                    throw new Error('Failed to load messages');
                });
            }

            return response.json();
        })
        .then(data => {
            console.debug('loadMessages response for complaint', complaintId, data);

            const messagesContainer = document.getElementById('chat-messages');
            messagesContainer.innerHTML = '';

            const messages = data && data.messages ? data.messages : (data && data.data ? data.data : []);

            if (Array.isArray(messages) && messages.length > 0) {
                messages.forEach(message => {
                    // Fix sender detection - use strict comparison
                    const isSent = parseInt(message.sender_id) === {{ Auth::id() }};
                    const senderName = message.sender && message.sender.name ? message.sender.name : (message.sender_name || 'Unknown');
                    const createdAt = message.created_at || message.createdAt || message.created;

                    const messageDiv = document.createElement('div');
                    messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
                    messageDiv.innerHTML = `
                        <div class="message-content">
                            <strong>${isSent ? 'You' : senderName}:</strong> 
                            <span>${message.message}</span>
                        </div>
                        <div class="message-time">${createdAt ? new Date(createdAt).toLocaleString() : ''}</div>
                    `;
                    messagesContainer.appendChild(messageDiv);
                });
            } else {
                messagesContainer.innerHTML = '<p style="text-align: center; color: #7f8c8d;">No messages yet. Start the conversation!</p>';
            }

            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
    }

    // Send message
    document.getElementById('send-message').addEventListener('click', function() {
        const messageInput = document.getElementById('chat-message');
        const message = messageInput.value.trim();

        if (!message || !currentComplaintId) return;

        // Disable send button
        this.disabled = true;
        this.textContent = 'Sending...';

        fetch('/messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                complaint_id: currentComplaintId,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages(currentComplaintId);
            } else {
                alert('Error sending message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Error sending message. Please try again.');
        })
        .finally(() => {
            // Re-enable send button
            this.disabled = false;
            this.textContent = 'Send';
        });
    });

    // Send message on Enter key
    document.getElementById('chat-message').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('send-message').click();
        }
    });

    // Add notes functionality (placeholder)
    document.querySelectorAll('.add-notes-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const complaintId = this.getAttribute('data-complaint-id');
            // TODO: Implement add notes functionality
            alert(`Add notes for complaint ID: ${complaintId}`);
            closeAllDropdowns(); // Close dropdown when alert shows
        });
    });

    // Status form submission
    document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        const complaintId = document.getElementById('caseId').value;
        const newStatus = document.getElementById('statusSelect').value;
        const remarks = document.getElementById('statusRemarks').value;

        // Show loading spinner
        document.getElementById('loadingSpinner').style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';

        // Prepare form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('complaint_id', complaintId);
        formData.append('status', newStatus);
        formData.append('remarks', remarks);

        // Make AJAX request
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            if (data.success) {
                // Close modal
                statusUpdateModal.hide();

                // Update the status in the table
                updateCaseStatus(complaintId, newStatus);

                // Show success message
                showAlert(data.message, 'success');

                // Clear form
                document.getElementById('statusSelect').value = '';
                document.getElementById('statusRemarks').value = '';
            } else {
                // Show error message
                showAlert(data.message || 'Failed to update status', 'danger');
            }
        })
        .catch(error => {
            // Hide loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            // Show error message
            showAlert('An error occurred while updating the status', 'danger');
            console.error('Error:', error);
        });
    });

    // Search functionality
    document.querySelector('.search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.cases-table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Function to update case status in the table
    function updateCaseStatus(complaintId, newStatus) {
        const rows = document.querySelectorAll('.cases-table tbody tr');
        const formattedCaseId = `#CMP${complaintId.padStart(7, '0')}`;
        rows.forEach(row => {
            if (row.querySelector('td:first-child').textContent === formattedCaseId) {
                const statusCell = row.querySelector('td:nth-child(3)');
                const statusBadge = statusCell.querySelector('.status-badge');

                // Remove existing status classes
                statusBadge.className = 'status-badge';

                // Add new status class and update text
                switch(newStatus) {
                    case 'pending':
                        statusBadge.classList.add('status-pending');
                        statusBadge.textContent = 'Pending';
                        break;
                    case 'under_investigation':
                        statusBadge.classList.add('status-investigating');
                        statusBadge.textContent = 'Under Investigation';
                        break;
                    case 'resolved':
                        statusBadge.classList.add('status-resolved');
                        statusBadge.textContent = 'Resolved';
                        break;
                    case 'rejected':
                        statusBadge.classList.add('status-rejected');
                        statusBadge.textContent = 'Rejected';
                        break;
                    case 'closed':
                        statusBadge.classList.add('status-closed');
                        statusBadge.textContent = 'Closed';
                        break;
                    default:
                        statusBadge.classList.add('status-pending');
                        statusBadge.textContent = 'Pending';
                }
            }
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

// Handle dropdown positioning on mobile
function positionDropdown(complaintId) {
    const button = document.getElementById('actionBtn' + complaintId);
    const dropdown = document.getElementById('dropdown' + complaintId);
    
    if (!button || !dropdown) return;
    
    const rect = button.getBoundingClientRect();
    
    if (window.innerWidth <= 767.98) {
        // On mobile, position dropdown to avoid going off-screen
        const viewportWidth = window.innerWidth;
        const dropdownWidth = 180;
        
        let left = rect.left;
        if (left + dropdownWidth > viewportWidth - 10) {
            left = viewportWidth - dropdownWidth - 10;
        }
        if (left < 10) {
            left = 10;
        }
        
        dropdown.style.left = left + 'px';
    } else {
        dropdown.style.left = rect.left + 'px';
    }
    
    dropdown.style.top = (rect.bottom + window.scrollY) + 'px';
}

// Update your existing toggleDropdown function to use positionDropdown
function toggleDropdown(complaintId) {
    // Close all other dropdowns
    closeAllDropdowns();

    const button = document.getElementById('actionBtn' + complaintId);
    const dropdown = document.getElementById('dropdown' + complaintId);
    const arrow = document.getElementById('arrow' + complaintId);

    if (!button || !dropdown) {
        console.error('Button or dropdown not found for complaint ID:', complaintId);
        return;
    }

    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        // Position the dropdown
        positionDropdown(complaintId);
        dropdown.style.display = 'block';
        // Rotate arrow up
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.style.display = 'none';
        // Rotate arrow down
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
}
</script>

   

<style>

</style>
@endsection