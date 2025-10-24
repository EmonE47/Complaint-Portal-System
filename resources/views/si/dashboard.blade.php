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
        <li><a href="{{ route('si.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="{{ route('si.cases') }}"><i class="fas fa-clipboard-list"></i> My Cases</a></li>
        <li><a href="{{ route('si.reports') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="#" data-section="messages"><i class="fas fa-envelope"></i> Messages</a></li>
        <li><a href="{{ route('si.profile') }}"><i class="fas fa-user"></i> Profile</a></li>
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

        <div class="row" style="margin: 0 40px 40px;">
            <div class="col-lg-8">
                <div class="content-section">
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
                                <div class="dropdown">
                                    <button class="btn action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item view-details-btn" href="#" data-complaint-id="{{ $complaint->id }}"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                        <li><a class="dropdown-item update-status-btn" href="#" data-complaint-id="{{ $complaint->id }}"><i class="fas fa-edit me-2"></i>Update Status</a></li>
                                        <li><a class="dropdown-item chat-btn" href="#" data-complaint-id="{{ $complaint->id }}"><i class="fas fa-comments me-2"></i>Chat with User</a></li>
                                        <li><a class="dropdown-item add-notes-btn" href="#" data-complaint-id="{{ $complaint->id }}"><i class="fas fa-file-alt me-2"></i>Add Notes</a></li>
                                    </ul>
                                </div>

                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Case Statistics Card -->
                <div class="sidebar-card">
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
                <div class="sidebar-card">
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

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatModalLabel">Chat with User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="chat-messages" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                    <!-- Messages will be loaded here -->
                </div>
                <div class="input-group">
                    <input type="text" id="chat-message" class="form-control" placeholder="Type your message..." maxlength="1000">
                    <button class="btn btn-primary" id="send-message">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div class="loading-spinner" id="loadingSpinner">
    <div class="d-flex align-items-center">
        <div class="spinner-border text-primary me-3" role="status"></div>
        <span>Updating status...</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update modal functionality
    const statusUpdateModal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));

    document.querySelectorAll('.update-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const complaintId = this.getAttribute('data-complaint-id');
            document.getElementById('caseId').value = complaintId;
            document.getElementById('caseNumber').textContent = `#CMP${complaintId.padStart(7, '0')}`;
            statusUpdateModal.show();
        });
    });

    // View details functionality (placeholder)
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const complaintId = this.getAttribute('data-complaint-id');
            // TODO: Implement view details functionality
            alert(`View details for complaint ID: ${complaintId}`);
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
        .then(response => response.json())
        .then(data => {
            const messagesContainer = document.getElementById('chat-messages');
            messagesContainer.innerHTML = '';

            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `message ${message.sender_id == {{ Auth::id() }} ? 'sent' : 'received'}`;
                    messageDiv.innerHTML = `
                        <div class="message-content">
                            <strong>${message.sender.name}:</strong> ${message.message}
                        </div>
                        <div class="message-time">${new Date(message.created_at).toLocaleString()}</div>
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
</script>

<style>
.message {
    margin-bottom: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    max-width: 70%;
}

.message.sent {
    margin-left: auto;
    background-color: #007bff;
    color: white;
}

.message.received {
    margin-right: auto;
    background-color: #f8f9fa;
    color: #333;
}

.message-content {
    margin-bottom: 4px;
}

.message-time {
    font-size: 0.8em;
    opacity: 0.7;
}
</style>
@endsection
