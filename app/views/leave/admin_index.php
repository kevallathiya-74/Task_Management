<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1">Leave Requests</h3>
                <p class="text-neutral-400 mb-0">Manage and approve employee leave applications</p>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-primary border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Total Requests</div>
                    <h3 class="fw-bold text-neutral-900 mb-0"><?= $stats['total'] ?></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-warning border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Pending Review</div>
                    <h3 class="fw-bold text-neutral-900 mb-0" id="stat-pending"><?= $stats['pending'] ?></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-success border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Approved</div>
                    <h3 class="fw-bold text-neutral-900 mb-0"><?= $stats['approved'] ?></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 border-start border-danger border-4">
                    <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Rejected</div>
                    <h3 class="fw-bold text-neutral-900 mb-0"><?= $stats['rejected'] ?></h3>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card mb-4 p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select class="form-select border-0 bg-neutral-50 rounded-pill px-4" id="filter-status">
                        <option value="">All Statuses</option>
                        <option value="pending" selected>Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select border-0 bg-neutral-50 rounded-pill px-4" id="filter-staff">
                        <option value="">All Staff Members</option>
                        <?php foreach ($staff as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary rounded-pill px-4" onclick="loadLeaves()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh List
                    </button>
                </div>
            </div>
        </div>

        <!-- Requests List -->
        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="ps-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase">Staff Member</th>
                            <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase">Leave Details</th>
                            <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Duration</th>
                            <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Status</th>
                            <th class="pe-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="leave-list-body">
                        <!-- Loaded by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Action Modal -->
<div class="modal fade" id="leaveActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0" id="action-modal-title">Review Leave</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="mb-4">
                    <p class="text-sm text-neutral-600 mb-2">Staff: <span class="fw-bold text-neutral-900" id="action-staff-name"></span></p>
                    <p class="text-sm text-neutral-600 mb-2">Period: <span class="fw-bold text-neutral-900" id="action-period"></span></p>
                    <p class="text-sm text-neutral-600">Reason: <span class="text-neutral-500 italic" id="action-reason"></span></p>
                </div>
                <div class="mb-0">
                    <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Admin Comment (Optional)</label>
                    <textarea class="form-control border-0 bg-neutral-100 rounded-3" id="admin-comment" rows="3" placeholder="Add your notes here..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 gap-3">
                <button type="button" class="btn btn-danger flex-grow-1 py-3" onclick="processLeave('rejected')">Reject</button>
                <button type="button" class="btn btn-success flex-grow-1 py-3" onclick="processLeave('approved')">Approve</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentLeaveId = null;

$(document).ready(function() {
    loadLeaves();
    
    $('#filter-status, #filter-staff').on('change', function() {
        loadLeaves();
    });

    // Auto-refresh every 30 seconds
    setInterval(loadLeaves, 30000);
});

function loadLeaves() {
    const status = $('#filter-status').val();
    const staffId = $('#filter-staff').val();

    $.get('<?= url('/api/leaves/list') ?>', { status: status, user_id: staffId }, function(res) {
        if (res.success) {
            renderLeaves(res.data);
        }
    });
}

function renderLeaves(leaves) {
    let html = '';
    if (leaves.length === 0) {
        html = '<tr><td colspan="5" class="text-center py-5 text-neutral-400">No matching leave requests found.</td></tr>';
    } else {
        leaves.forEach(l => {
            const statusClass = l.status === 'approved' ? 'bg-success-soft text-success' : 
                               (l.status === 'rejected' ? 'bg-danger-soft text-danger' : 
                               (l.status === 'pending' ? 'bg-warning-soft text-warning' : 'bg-neutral-100 text-neutral-400'));
            
            html += `
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(l.staff_name)}&background=8b5cf6&color=fff" class="rounded-circle me-3" width="40">
                            <div>
                                <div class="fw-bold text-neutral-800 text-sm">${l.staff_name}</div>
                                <div class="text-xs text-neutral-400">Applied: ${moment(l.created_at).format('DD MMM, HH:mm')}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">${l.leave_type.replace('_', ' ')}</div>
                        <div class="text-sm text-neutral-600 text-truncate" style="max-width: 250px;">${l.reason}</div>
                    </td>
                    <td class="text-center">
                        <div class="text-sm fw-bold text-neutral-800">${l.total_days} Days</div>
                        <div class="text-xs text-neutral-400">${moment(l.from_date).format('DD MMM')} - ${moment(l.to_date).format('DD MMM')}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge ${statusClass} rounded-pill px-3 py-1 text-xs text-capitalize">${l.status}</span>
                    </td>
                    <td class="pe-4 text-end">
                        ${l.status === 'pending' ? 
                            `<button class="btn btn-sm btn-primary rounded-pill px-3" onclick="openReviewModal('${l.id}', '${l.staff_name}', '${moment(l.from_date).format('DD MMM')} - ${moment(l.to_date).format('DD MMM')}', '${l.reason.replace(/'/g, "\\'")}')">Review</button>` : 
                            `<span class="text-xs text-neutral-400">${l.admin_comment || 'No comment'}</span>`
                        }
                    </td>
                </tr>
            `;
        });
    }
    $('#leave-list-body').html(html);
}

function openReviewModal(id, staff, period, reason) {
    currentLeaveId = id;
    $('#action-staff-name').text(staff);
    $('#action-period').text(period);
    $('#action-reason').text(reason);
    $('#admin-comment').val('');
    $('#leaveActionModal').modal('show');
}

function processLeave(status) {
    const comment = $('#admin-comment').val();
    
    $.post('<?= url('/api/leaves/update-status') ?>', { 
        id: currentLeaveId, 
        status: status, 
        admin_comment: comment 
    }, function(res) {
        if (res.success) {
            toastr.success(res.message);
            $('#leaveActionModal').modal('hide');
            loadLeaves();
            updateStats();
        } else {
            toastr.error(res.message);
        }
    });
}

function updateStats() {
    // Optionally refresh stats counters here
}
</script>

<style>
.bg-primary-soft { background: rgba(139, 92, 246, 0.1); }
.bg-success-soft { background: rgba(16, 185, 129, 0.1); }
.bg-warning-soft { background: rgba(245, 158, 11, 0.1); }
.bg-danger-soft { background: rgba(239, 68, 68, 0.1); }
</style>
