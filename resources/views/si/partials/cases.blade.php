<!-- Partial: My Assigned Cases (used in dashboard and cases section) -->
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
