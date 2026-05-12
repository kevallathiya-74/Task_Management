<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1">Leave Management</h3>
                <p class="text-neutral-400 mb-0">Apply for leave and track your requests</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#applyLeaveModal">
                <i class="fas fa-plus me-2"></i>Apply for Leave
            </button>
        </div>

        <div class="row g-4">
            <!-- Leave Summary Cards -->
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-primary border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Total Applied</div>
                    <h3 class="fw-bold text-neutral-900 mb-0"><?= count($leaves) ?></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-warning border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Pending</div>
                    <h3 class="fw-bold text-neutral-900 mb-0"><?= count(array_filter($leaves, fn($l) => $l['status'] == 'pending')) ?></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-success border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Approved</div>
                    <h3 class="fw-bold text-neutral-900 mb-0"><?= count(array_filter($leaves, fn($l) => $l['status'] == 'approved')) ?></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-danger border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Rejected</div>
                    <h3 class="fw-bold text-neutral-900 mb-0"><?= count(array_filter($leaves, fn($l) => $l['status'] == 'rejected')) ?></h3>
                </div>
            </div>

            <!-- Leave History -->
            <div class="col-12">
                <div class="glass-card overflow-hidden">
                    <div class="p-4 border-bottom border-light">
                        <h5 class="fw-bold text-neutral-900 mb-0">My Leave History</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th class="ps-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase">Leave Type</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase">Duration</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Total Days</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Status</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase">Applied On</th>
                                    <th class="pe-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($leaves)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-neutral-400">No leave requests found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($leaves as $leave): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-neutral-800 text-sm"><?= ucfirst(str_replace('_', ' ', $leave['leave_type'])) ?></div>
                                            <div class="text-xs text-neutral-400 text-truncate" style="max-width: 200px;" title="<?= $leave['reason'] ?>"><?= $leave['reason'] ?></div>
                                        </td>
                                        <td>
                                            <div class="text-sm fw-medium text-neutral-700"><?= date('d M Y', strtotime($leave['from_date'])) ?> - <?= date('d M Y', strtotime($leave['to_date'])) ?></div>
                                        </td>
                                        <td class="text-center fw-bold text-neutral-800"><?= $leave['total_days'] ?></td>
                                        <td class="text-center">
                                            <?php 
                                                $badgeClass = 'bg-neutral-100 text-neutral-500';
                                                if ($leave['status'] == 'approved') $badgeClass = 'bg-success-soft text-success';
                                                elseif ($leave['status'] == 'rejected') $badgeClass = 'bg-danger-soft text-danger';
                                                elseif ($leave['status'] == 'pending') $badgeClass = 'bg-warning-soft text-warning';
                                                elseif ($leave['status'] == 'cancelled') $badgeClass = 'bg-neutral-200 text-neutral-400';
                                            ?>
                                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-1 text-xs text-capitalize"><?= $leave['status'] ?></span>
                                        </td>
                                        <td>
                                            <div class="text-xs text-neutral-400"><?= date('d M Y, H:i', strtotime($leave['created_at'])) ?></div>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <?php if ($leave['status'] == 'pending'): ?>
                                                <button onclick="cancelLeave('<?= $leave['id'] ?>')" class="btn btn-xs btn-outline-danger rounded-pill px-3">Cancel</button>
                                            <?php elseif ($leave['admin_comment']): ?>
                                                <button class="btn btn-xs btn-light rounded-pill px-3" data-bs-toggle="tooltip" title="<?= $leave['admin_comment'] ?>">Comment</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Apply Leave Modal -->
<div class="modal fade" id="applyLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0">Apply for Leave</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="applyLeaveForm">
                <div class="modal-body py-4">
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Leave Type</label>
                        <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="leave_type" required>
                            <option value="">Select Type</option>
                            <option value="sick_leave">Sick Leave</option>
                            <option value="casual_leave">Casual Leave</option>
                            <option value="emergency_leave">Emergency Leave</option>
                            <option value="other_leave">Other Leave</option>
                        </select>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">From Date</label>
                            <input type="date" class="form-control border-0 bg-neutral-100 rounded-3 py-3" name="from_date" id="from_date" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">To Date</label>
                            <input type="date" class="form-control border-0 bg-neutral-100 rounded-3 py-3" name="to_date" id="to_date" required min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase mb-0">Reason</label>
                            <span class="text-xs text-neutral-400" id="reason-char-count">0/1000</span>
                        </div>
                        <textarea class="form-control border-0 bg-neutral-100 rounded-3" name="reason" rows="3" required minlength="10" maxlength="1000" placeholder="Please provide a valid reason..."></textarea>
                    </div>
                    <div class="bg-primary-soft p-3 rounded-4 d-flex justify-content-between align-items-center">
                        <span class="text-sm fw-bold text-primary">Total Days Calculated:</span>
                        <span class="h4 mb-0 fw-bold text-primary" id="calc-total-days">0</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-3">
                    <button type="button" class="btn btn-light flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3" id="btn-submit-leave">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-calculate total days
    $('#from_date, #to_date').on('change', function() {
        const from = $('#from_date').val();
        const to = $('#to_date').val();
        
        if (from && to) {
            const start = new Date(from);
            const end = new Date(to);
            
            if (end < start) {
                $('#to_date').addClass('is-invalid');
                $('#calc-total-days').text('0');
                return;
            }
            
            $('#to_date').removeClass('is-invalid');
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            $('#calc-total-days').text(diffDays);
        }
    });

    $('textarea[name="reason"]').on('input', function() {
        $('#reason-char-count').text($(this).val().length + '/1000');
    });

    $('#applyLeaveForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize() + '&total_days=' + $('#calc-total-days').text();
        
        $('#btn-submit-leave').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');

        $.post('<?= url('/api/leaves/submit') ?>', formData, function(res) {
            if (res.success) {
                toastr.success(res.message);
                location.reload();
            } else {
                toastr.error(res.message);
                $('#btn-submit-leave').prop('disabled', false).text('Submit Request');
            }
        });
    });
});

function cancelLeave(id) {
    if (confirm('Are you sure you want to cancel this leave request?')) {
        $.post('<?= url('/api/leaves/cancel') ?>', { id: id }, function(res) {
            if (res.success) {
                toastr.success(res.message);
                location.reload();
            } else {
                toastr.error(res.message);
            }
        });
    }
}
</script>

<style>
.bg-primary-soft { background: rgba(139, 92, 246, 0.1); }
.bg-success-soft { background: rgba(16, 185, 129, 0.1); }
.bg-warning-soft { background: rgba(245, 158, 11, 0.1); }
.bg-danger-soft { background: rgba(239, 68, 68, 0.1); }
</style>
