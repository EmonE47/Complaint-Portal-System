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
        <li><a href="{{ route('si.cases') }}" class="active"><i class="fas fa-clipboard-list"></i> My Cases</a></li>
        <li><a href="{{ route('si.reports') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="{{ route('si.profile') }}"><i class="fas fa-user"></i> Profile</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1>My Cases</h1>
        <div class="user-info">
            <div class="user-avatar">SI</div>
            <span>Sub-Inspector</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</button>
            </form>
        </div>
    </div>

    <!-- Cases Section -->
    <div id="cases" class="section active">
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
                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                <li><a class="dropdown-item update-status-btn" href="#" data-complaint-id="{{ $complaint->id }}"><i class="fas fa-edit me-2"></i>Update Status</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-alt me-2"></i>Add Notes</a></li>
                            </ul>
                        </div>
                    </td>
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

<!-- Loading Spinner -->
<div class="loading-spinner" id="loadingSpinner">
    <div class="d-flex align-items-center">
        <div class="spinner-border text-primary me-3" role="status"></div>
        <span>Updating status...</span>
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
@endsection
