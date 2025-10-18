@extends('layouts.app')

@section('content')
<link href="{{ asset('css/User_dash.css') }}" rel="stylesheet" />
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Complaint</h2>
        <p>User Dashboard</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="#" class="active" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="#" data-section="profile"><i class="fas fa-user"></i> My Profile</a></li>
        <li><a href="#" data-section="complaints"><i class="fas fa-list"></i> My Complaints</a></li>
        <li><a href="#" data-section="new-complaint"><i class="fas fa-plus-circle"></i> File Complaint</a></li>
        <li><a href="#" data-section="help"><i class="fas fa-question-circle"></i> Help</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1>Dashboard</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</button>
            </form>
        </div>
    </div>

    <div class="success-message" id="success-message">
        <i class="fas fa-check-circle"></i> Complaint submitted successfully! Your complaint ID is <strong id="complaint-id"></strong>
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
                <div class="card-value">{{ $totalComplaints }}</div>
                <div class="card-footer">All time complaints</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Pending</div>
                    <div class="card-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="card-value">{{ $pendingComplaints }}</div>
                <div class="card-footer">Awaiting response</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">In Progress</div>
                    <div class="card-icon in-progress">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
                <div class="card-value">{{ $inProgressComplaints }}</div>
                <div class="card-footer">Being processed</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Resolved</div>
                    <div class="card-icon resolved">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="card-value">{{ $resolvedComplaints }}</div>
                <div class="card-footer">Successfully resolved</div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">Recent Complaints</h2>
                <button class="btn-primary" id="view-all-complaints">View All</button>
            </div>

                <table class="complaints-table">
                    <thead>
                        <tr>
                            <th>Complaint ID</th>
                            <th>Type</th>
                            <th>Police Station</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentComplaints as $complaint)
                    <tr>
                        <td>{{ $complaint->complaint_number }}</td>
                        <td>{{ $complaint->complaint_type_text }}</td>
                        <td>{{ $complaint->police_station_text }}</td>
                        <td>{{ $complaint->created_at->format('d M Y') }}</td>
                        <td><span class="status-badge status-{{ str_replace('_', '-', $complaint->status) }}">{{ $complaint->status_text }}</span></td>
                        <td><a href="#" class="view-details" data-id="{{ $complaint->id }}">View Details</a></td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
        </div>
    </div>

    <!-- Profile Section -->
    <div id="profile" class="section">
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">My Profile</h2>
            </div>

            <form id="profile-form" action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
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

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address">{{ Auth::user()->address ?? '' }}</textarea>
                </div>

                <button type="submit" class="btn-primary">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Complaints Section -->
    <div id="complaints" class="section">
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">My Complaints</h2>
                <button class="btn-primary" id="file-new-complaint">File New Complaint</button>
            </div>

            <table class="complaints-table">
                <thead>
                    <tr>
                        <th>Complaint ID</th>
                        <th>Type</th>
                        <th>Police Station</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                    <tr>
                        <td>{{ $complaint->complaint_number }}</td>
                        <td>{{ $complaint->complaint_type_text }}</td>
                        <td>{{ $complaint->police_station_text }}</td>
                        <td>{{ $complaint->created_at->format('d M Y') }}</td>
                        <td><span class="status-badge status-{{ str_replace('_', '-', $complaint->status) }}">{{ $complaint->status_text }}</span></td>
                        <td><a href="#" class="view-details" data-id="{{ $complaint->id }}">View Details</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- New Complaint Section -->
    <div id="new-complaint" class="section">
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">File New Complaint</h2>
            </div>

            <form id="complaint-form" action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="phone_no" name="phone_no" value="{{ Auth::user()->phone_no ?? '' }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Voter ID Number *</label>
                        <input type="text" class="form-control" id="voter_id_number" name="voter_id_number" value="{{ Auth::user()->voter_id_number ?? '' }}" required>
                    </div>
                </div>

                <div class="address-section">
                    <div class="address-title">Permanent Address</div>
                    <div class="form-group">
                        <label class="form-label">Detailed Address *</label>
                        <textarea class="form-control" id="permanent_address" name="permanent_address" placeholder="Enter your complete permanent address" required>{{ Auth::user()->address ?? '' }}</textarea>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="is_same_address" name="is_same_address">
                    <label for="is_same_address">Present address is same as permanent address</label>
                </div>

                <div class="address-section" id="present-address-section">
                    <div class="address-title">Present Address</div>
                    <div class="form-group">
                        <label class="form-label">Detailed Address *</label>
                        <textarea class="form-control" id="present_address" name="present_address" placeholder="Enter your complete present address"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Complaint Type *</label>
                        <select class="form-control" id="complaint_type" name="complaint_type" required>
                            <option value="">Select complaint type</option>
                            <option value="lost_item">Lost Item</option>
                            <option value="land_dispute">Land Dispute</option>
                            <option value="harassment">Harassment</option>
                            <option value="theft">Theft</option>
                            <option value="fraud">Fraud</option>
                            <option value="domestic_violence">Domestic Violence</option>
                            <option value="public_nuisance">Public Nuisance</option>
                            <option value="cyber_crime">Cyber Crime</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Police Station *</label>
                        <select class="form-control" id="police_station" name="police_station_id" required>
                            <option value="">Select police station</option>
                            @foreach(\App\Models\PoliceStation::all() as $station)
                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Complaint Description *</label>
                    <textarea class="form-control" id="description" name="description" placeholder="Please describe your complaint in detail" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Attach Supporting Documents/Photos</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept="image/*,.pdf,.doc,.docx">
                    <small style="display: block; margin-top: 8px; color: #7f8c8d;">You can attach multiple files (Max: 10MB total)</small>

                    <div class="file-preview" id="file-preview"></div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">I confirm that all information provided is true and accurate</label>
                </div>

                <button type="submit" class="btn-primary">Submit Complaint</button>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div id="help" class="section">
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">Help & Support</h2>
            </div>

            <div style="margin-bottom: 40px;">
                <h3 style="font-family: 'Roboto Condensed', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Frequently Asked Questions</h3>
                <div style="margin-top: 15px;">
                    <p style="margin-bottom: 15px;"><strong style="font-weight: 700;">Q: How long does it take to resolve a complaint?</strong></p>
                    <p style="color: #7f8c8d; margin-bottom: 30px;">A: Resolution time depends on the complexity of the issue. Simple issues are typically resolved within 24-48 hours, while complex issues may take longer.</p>

                    <p style="margin-bottom: 15px;"><strong style="font-weight: 700;">Q: Can I update my complaint after submission?</strong></p>
                    <p style="color: #7f8c8d; margin-bottom: 30px;">A: Yes, you can add additional information to your complaint by using the "View Details" option.</p>

                    <p style="margin-bottom: 15px;"><strong style="font-weight: 700;">Q: How will I be notified about updates?</strong></p>
                    <p style="color: #7f8c8d;">A: You will receive email notifications for all status updates and when your complaint is resolved.</p>
                </div>
            </div>

            <div>
                <h3 style="font-family: 'Roboto Condensed', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Contact Support</h3>
                <p style="margin-bottom: 15px; color: #7f8c8d;">If you need immediate assistance, please contact our support team:</p>
                <ul style="margin-left: 20px; margin-top: 10px; color: #7f8c8d;">
                    <li style="margin-bottom: 10px;">Email: support@complaintsystem.com</li>
                    <li style="margin-bottom: 10px;">Phone: +880 1234-567890</li>
                    <li>Office Hours: Mon-Fri, 9:00 AM - 6:00 PM</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
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

            // Show the selected section
            const sectionId = this.getAttribute('data-section');
            document.getElementById(sectionId).classList.add('active');

            // Update header title
            const headerTitle = document.querySelector('.header h1');
            const sectionTitles = {
                'dashboard': 'Dashboard',
                'profile': 'My Profile',
                'complaints': 'My Complaints',
                'new-complaint': 'File New Complaint',
                'help': 'Help & Support'
            };
            headerTitle.textContent = sectionTitles[sectionId] || 'Dashboard';
        });
    });

    // Navigation buttons
    document.getElementById('view-all-complaints').addEventListener('click', function(e) {
        e.preventDefault();

        // Update sidebar active state
        document.querySelectorAll('.sidebar-menu a').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector('[data-section="complaints"]').classList.add('active');

        // Show complaints section
        document.querySelectorAll('.section').forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById('complaints').classList.add('active');

        // Update header
        document.querySelector('.header h1').textContent = 'My Complaints';
    });

    document.getElementById('file-new-complaint').addEventListener('click', function(e) {
        e.preventDefault();

        // Update sidebar active state
        document.querySelectorAll('.sidebar-menu a').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector('[data-section="new-complaint"]').classList.add('active');

        // Show new complaint section
        document.querySelectorAll('.section').forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById('new-complaint').classList.add('active');

        // Update header
        document.querySelector('.header h1').textContent = 'File New Complaint';
    });

    // Profile form submission
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        // Disable button and show loading
        submitButton.disabled = true;
        submitButton.textContent = 'Updating...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Profile updated successfully!');
                // Optionally refresh the page or update UI
                // location.reload();
            } else {
                alert('Error updating profile. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    });

    // Complaint form submission
    document.getElementById('complaint-form').addEventListener('submit', function(e) {
        // Let the form submit normally to the backend
    });

    // Logout button confirmation
    document.querySelector('.logout-btn').addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to logout?')) {
            e.preventDefault();
        }
    });

    // Same address checkbox functionality
    document.getElementById('is_same_address').addEventListener('change', function() {
        const presentAddressSection = document.getElementById('present-address-section');
        const presentAddressField = document.getElementById('present_address');

        if (this.checked) {
            presentAddressSection.style.display = 'none';
            presentAddressField.removeAttribute('required');
            presentAddressField.value = '';
        } else {
            presentAddressSection.style.display = 'block';
            presentAddressField.setAttribute('required', 'required');
        }
    });

    // File preview functionality
    document.getElementById('attachments').addEventListener('change', function(e) {
        const filePreview = document.getElementById('file-preview');
        filePreview.innerHTML = '';

        Array.from(this.files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-preview-item';

            const fileIcon = document.createElement('i');
            if (file.type.startsWith('image/')) {
                fileIcon.className = 'fas fa-image';
            } else if (file.type === 'application/pdf') {
                fileIcon.className = 'fas fa-file-pdf';
            } else {
                fileIcon.className = 'fas fa-file';
            }

            const fileName = document.createElement('span');
            fileName.textContent = file.name;

            fileItem.appendChild(fileIcon);
            fileItem.appendChild(fileName);
            filePreview.appendChild(fileItem);
        });
    });

    // View complaint details
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-details')) {
            e.preventDefault();
            const complaintId = e.target.getAttribute('data-id');
            alert('Viewing details for complaint ID: ' + complaintId + '\n\nThis would normally fetch and display full complaint details.');
        }
    });

    // Initialize the form state
    document.getElementById('is_same_address').dispatchEvent(new Event('change'));
</script>
@endsection
