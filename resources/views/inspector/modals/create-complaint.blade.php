<!-- Create Complaint Modal -->
<div class="modal fade" id="createComplaintModal" tabindex="-1" aria-labelledby="createComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createComplaintModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Create New Complaint
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createComplaintForm" action="{{ route('inspector.createComplaint') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="complaint_type" class="form-label">
                                    <i class="fas fa-tag me-1"></i>
                                    Complaint Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="complaint_type" id="complaint_type" required>
                                    <option value="">Select complaint type...</option>
                                    <option value="theft">Theft</option>
                                    <option value="assault">Assault</option>
                                    <option value="burglary">Burglary</option>
                                    <option value="fraud">Fraud</option>
                                    <option value="vandalism">Vandalism</option>
                                    <option value="traffic_violation">Traffic Violation</option>
                                    <option value="domestic_violence">Domestic Violence</option>
                                    <option value="missing_person">Missing Person</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Priority Level <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="priority" id="priority" required>
                                    <option value="">Select priority...</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="complainant_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Complainant Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="complainant_name" id="complainant_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="complainant_contact" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Contact Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="complainant_contact" id="complainant_contact" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="incident_location" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Incident Location <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="incident_location" id="incident_location" required>
                            </div>

                            <div class="mb-3">
                                <label for="incident_datetime" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Incident Date & Time <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" class="form-control" name="incident_datetime" id="incident_datetime" required>
                            </div>

                            <div class="mb-3">
                                <label for="evidence" class="form-label">
                                    <i class="fas fa-file-upload me-1"></i>
                                    Evidence Files (Optional)
                                </label>
                                <input type="file" class="form-control" name="evidence[]" id="evidence" multiple accept="image/*,.pdf,.doc,.docx">
                                <div class="form-text">
                                    You can upload multiple files (images, PDFs, documents). Max 2MB each.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Detailed Description <span class="text-danger">*</span>
                        </label>
                        <textarea
                            class="form-control"
                            name="description"
                            id="description"
                            rows="4"
                            placeholder="Please provide a detailed description of the incident..."
                            required
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
