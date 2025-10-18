<!-- Assign Case Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">
                    <i class="fas fa-user-plus me-2"></i>
                    Assign Case to Sub-Inspector
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" action="{{ route('inspector.assignCase') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You are about to assign complaint <strong id="complaintNumber">#CMP0000000</strong> to a Sub-Inspector.
                    </div>

                    <input type="hidden" name="complaint_id" id="complaintId">

                    <div class="mb-3">
                        <label for="user_id" class="form-label">
                            <i class="fas fa-user-shield me-1"></i>
                            Select Sub-Inspector
                        </label>
                        <select class="form-select" name="user_id" id="user_id" required>
                            <option value="">Choose a Sub-Inspector...</option>
                            @foreach($subInspectors as $si)
                            <option value="{{ $si->id }}">
                                {{ $si->name }} ({{ $si->active_cases_count }} active cases)
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Only Sub-Inspectors with role 2 are eligible for case assignment.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="assignment_notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>
                            Assignment Notes (Optional)
                        </label>
                        <textarea
                            class="form-control"
                            name="assignment_notes"
                            id="assignment_notes"
                            rows="3"
                            placeholder="Add any specific instructions or notes for the Sub-Inspector..."
                        ></textarea>
                        <div class="form-text">
                            These notes will be visible to the assigned Sub-Inspector.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>
                        Assign Case
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
