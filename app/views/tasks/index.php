<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
            <div>
                <h2 class="fw-bold text-neutral-900 mb-1 font-outfit">Task Management</h2>
                <p class="text-neutral-500 mb-0 fw-medium">Track and manage project tasks and assignments</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-primary-grad rounded-pill px-4 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 28px; height: 28px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                            <i class="fas fa-plus text-white" style="font-size: 0.85rem;"></i>
                        </div>
                        <span class="fw-bold text-white">Create Task</span>
                    </div>
                </button>

            </div>
        </div>

        <!-- Dynamic Filters -->
        <div class="glass-card mb-5 p-4 border-0">
            <form id="filterForm" class="row g-4 align-items-end">
                <div class="col-xl-3 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-3 ms-1">Active Project</label>
                    <div class="input-group bg-neutral-50 rounded-pill">
                        <span class="input-group-text ps-3">
                            <i class="fas fa-layer-group text-neutral-400"></i>
                        </span>
                        <select class="form-select text-sm fw-bold h-100" name="project_id" id="filter_project">
                            <option value="">All Active Projects</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= (isset($project_id) && $project_id == $p['id']) ? 'selected' : '' ?>>
                                    <?= $p['project_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-3 ms-1">Assigned To</label>
                    <div class="input-group bg-neutral-50 rounded-pill">
                        <span class="input-group-text ps-3">
                            <i class="fas fa-user-check text-neutral-400"></i>
                        </span>
                        <select class="form-select text-sm fw-bold h-100" name="assigned_to" id="filter_assignee">
                            <option value="">All Team Members</option>
                            <?php foreach ($staff as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-3 ms-1">Status</label>
                    <div class="input-group bg-neutral-50 rounded-pill">
                        <span class="input-group-text ps-3">
                            <i class="fas fa-check-circle text-neutral-400"></i>
                        </span>
                        <select class="form-select text-sm fw-bold h-100" name="status" id="filter_status">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <button type="button" id="resetFilters" class="btn btn-secondary-soft w-100 rounded-pill h-100 text-xs fw-bold">
                        <i class="fas fa-sync-alt me-2"></i> Reset Filters
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tasksTable" style="min-width: 1100px;">
                <thead>
                    <tr>
                        <th class="ps-4 text-xs fw-bold text-uppercase text-neutral-400">Task Details</th>
                        <th class="text-xs fw-bold text-uppercase text-neutral-400">Status</th>
                        <th class="text-xs fw-bold text-uppercase text-neutral-400">Deadline</th>
                        <th class="text-xs fw-bold text-uppercase text-neutral-400">Priority</th>
                        <th class="text-xs fw-bold text-uppercase text-neutral-400">Assignee To</th>
                        <th class="text-xs fw-bold text-uppercase text-neutral-400">Project</th>
                        <th class="text-end pe-4 text-xs fw-bold text-uppercase text-neutral-400">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</main>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-3">
                <h4 class="fw-bold text-neutral-900 mb-0">Create New Task</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addTaskForm" action="<?= url('/api/tasks') ?>" method="POST">
                <div class="modal-body py-0">
                    <div id="task-blocks-container">
                        <!-- Initial Task Block -->
                        <div class="task-block-card mb-5 p-4 border rounded-5 bg-white bg-opacity-10 position-relative animate-fade-in">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-neutral-100">
                                <h5 class="fw-bold text-neutral-900 mb-0 d-flex align-items-center">
                                    <span class="bg-primary-grad text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 28px; height: 28px; font-size: 0.75rem;">1</span>
                                    Task Details
                                </h5>
                                <button type="button" class="btn btn-sm btn-danger-soft rounded-pill px-3 remove-task-block d-none">
                                    <i class="fas fa-times me-1"></i> Remove
                                </button>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Task Title</label>
                                <input type="text" class="form-control glass-input" name="tasks[0][title]" required placeholder="[ Enter task title ]">
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Detailed Description</label>
                                <textarea class="form-control glass-input py-3" name="tasks[0][description]" rows="3" placeholder="[ Brief explanation of the task ]"></textarea>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Select Project</label>
                                    <select class="form-select glass-input text-sm" name="tasks[0][project_id]" required>
                                        <option value="" selected disabled>Select Project...</option>
                                        <?php foreach ($projects as $p): ?>
                                            <option value="<?= $p['id'] ?>" <?= (isset($project_id) && $project_id == $p['id']) ? 'selected' : '' ?>>
                                                <?= $p['project_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Assign Team Lead</label>
                                    <select class="form-select glass-input text-sm" name="tasks[0][assigned_to]" required>
                                        <option value="" selected disabled>Select Member...</option>
                                        <?php foreach ($staff as $s): ?>
                                            <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?> (<?= $s['role_name'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Department</label>
                                    <select class="form-select glass-input text-sm" name="tasks[0][role_id]" required>
                                        <option value="" selected disabled>Select Department...</option>
                                        <?php foreach ($roles as $r): ?>
                                            <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Priority</label>
                                    <select class="form-select glass-input text-sm" name="tasks[0][priority]">
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Status</label>
                                    <select class="form-select glass-input text-sm" name="tasks[0][status]">
                                        <option value="pending" selected>Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="review">Review</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Initial Progress (%)</label>
                                    <div class="premium-slider-wrap">
                                        <input type="range" class="form-range premium-slider add-progress-range" min="0" max="100" step="5" value="0">
                                        <span class="badge rounded-pill bg-primary-soft text-primary ms-3 add-progress-val">0%</span>
                                        <input type="hidden" name="tasks[0][progress_percentage]" class="add-progress-input" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Expected Delivery Date</label>
                                    <input type="date" class="form-control glass-input" name="tasks[0][due_date]" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Expected Delivery Time</label>
                                    <input type="time" class="form-control glass-input" name="tasks[0][due_time]" required value="09:00">
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Status Notes / Remarks</label>
                                <input type="text" class="form-control glass-input" name="tasks[0][status_notes]" placeholder="[ Any initial notes or remarks... ]">
                            </div>
                        </div>
                    </div>

                    <!-- Add More Button Section -->
                    <div class="text-center mb-4">
                        <button type="button" id="add-more-tasks" class="btn btn-primary-soft rounded-pill px-4 py-2 text-sm fw-bold">
                            <i class="fas fa-plus-circle me-2"></i> Add Another Task
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary-soft rounded-pill px-5 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-grad rounded-pill px-5 py-3">Create All Tasks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-3">
                <h4 class="fw-bold text-neutral-900 mb-0">Update Execution Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTaskForm" action="<?= url('/api/tasks/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body py-0">
                    <!-- Project Selection Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Project</label>
                            <select class="form-select glass-input text-sm" name="project_id" id="edit_project_id" required>
                                <?php foreach ($projects as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['project_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Core Details -->
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Task Title</label>
                        <input type="text" class="form-control glass-input" name="title" id="edit_title" required placeholder="[ Task title ]">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Description</label>
                        <textarea class="form-control glass-input py-3" name="description" id="edit_description" rows="3" placeholder="[ Task description ]"></textarea>
                    </div>

                    <!-- Attribution & Priority Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Assigned To</label>
                            <select class="form-select glass-input text-sm" name="assigned_to" id="edit_assigned_to" required>
                                <?php foreach ($staff as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Department</label>
                            <select class="form-select glass-input text-sm" name="role_id" id="edit_role_id" required>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Priority & Status & Timeline Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Priority</label>
                            <select class="form-select glass-input text-sm" name="priority" id="edit_priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Status</label>
                            <select class="form-select glass-input text-sm" name="status" id="edit_status">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Progress & Date Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Progress (%)</label>
                            <div class="premium-slider-wrap">
                                <input type="range" class="form-range premium-slider" id="edit_progress_range" min="0" max="100" step="5">
                                <span class="badge rounded-pill bg-primary-soft text-primary ms-3" id="progress_val">0%</span>
                                <input type="hidden" name="progress_percentage" id="edit_progress">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Expected Delivery</label>
                            <div class="row g-2">
                                <div class="col-7"><input type="date" class="form-control glass-input" name="due_date" id="edit_due_date" required></div>
                                <div class="col-5"><input type="time" class="form-control glass-input" name="due_time" id="edit_due_time" required></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase ms-1 mb-2">Progress Notes</label>
                        <input type="text" class="form-control glass-input" name="status_notes" id="edit_notes" placeholder="[ Detailed update on current blockages or progress... ]">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-5 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary-soft rounded-pill px-5 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-grad rounded-pill px-5 py-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Recurring History Modal -->
<div class="modal fade" id="recurringHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-3">
                <h4 class="fw-bold text-neutral-900 mb-0">Recurring History</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-0">
                <div id="recurring-logs-container" class="py-2">
                    <!-- Logs will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const table = $('#tasksTable').DataTable({
        ajax: {
            url: '<?= url('/api/tasks') ?>',
            dataSrc: 'data',
            data: function(d) {
                d.project_id = $('#filter_project').val();
                d.assigned_to = $('#filter_assignee').val();
                d.status = $('#filter_status').val();
            }
        },
        scrollX: true,
        autoWidth: false,
        columns: [
            { 
                data: 'title',
                render: function(data, type, row) {
                    let recurringBadge = '';
                    if (row.is_recurring == 1) {
                        const typeLabel = row.recurring_type === 'daily' ? 'Daily Repeat' : (row.recurring_type === 'weekly' ? 'Weekly Repeat' : 'Monthly Repeat');
                        recurringBadge = `<span class="badge bg-purple-grad rounded-pill px-2 py-1 ms-2 text-white" style="font-size: 0.6rem;"><i class="fas fa-sync-alt me-1"></i>${typeLabel}</span>`;
                    }
                    return `
                        <div class="py-2">
                            <div class="d-flex align-items-center">
                                <div class="fw-bold text-neutral-900 mb-1 font-outfit fs-6 lh-sm">${data}</div>
                                ${recurringBadge}
                            </div>
                            <div class="text-xs text-neutral-400 font-medium text-truncate" style="max-width: 280px;">${row.description || 'No detailed description provided'}</div>
                        </div>
                    `;
                }
            },
            { 
                data: 'status',
                render: function(data, type, row) {
                    const progress = row.progress_percentage || 0;
                    let cls = 'bg-primary-soft text-primary';
                    if (data === 'in_progress') cls = 'bg-warning-soft text-warning';
                    if (data === 'review') cls = 'bg-info-soft text-info';
                    if (data === 'completed') cls = 'bg-success-soft text-success';
                    
                    return `
                        <div style="width: 150px;" class="py-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge ${cls} rounded-pill py-1 px-3 text-capitalize border-0" style="font-size: 0.6rem; font-weight: 800;">${data.replace('_', ' ')}</span>
                                <span class="text-xs fw-bold text-neutral-900 font-outfit" style="font-size: 0.65rem;">${progress}%</span>
                            </div>
                            <div class="progress rounded-pill bg-light" style="height: 6px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
                                <div class="progress-bar rounded-pill shadow-sm" role="progressbar" style="width: ${progress}%; background: linear-gradient(90deg, #8b5cf6, #6366f1);"></div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'due_date',
                render: function(data, type, row) {
                    if (!data) return '<span class="text-neutral-400">No Date</span>';
                    
                    const datePart = data.split(' ')[0];
                    const timePart = row.due_time ? (row.due_time.includes(':') ? row.due_time : row.due_time + ':00') : '09:00';
                    const target = moment(datePart + ' ' + timePart);
                    
                    if (!target.isValid()) return '<span class="text-neutral-400">Invalid Date</span>';
                    
                    return `
                        <div class="d-flex align-items-center gap-3 py-1">
                            <div class="timeline-dot bg-primary"></div>
                            <div>
                                <div class="text-neutral-400 fw-bold text-uppercase mb-1" style="font-size: 0.55rem; letter-spacing: 0.05em;">Target Time</div>
                                <div class="text-neutral-900 fw-bold font-outfit text-sm">
                                    ${target.format('DD MMM')} <span class="text-neutral-400 fw-medium ms-1" style="font-size: 0.75rem;">${target.format('hh:mm A')}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'priority',
                render: function(data) {
                    let cls = 'bg-primary-soft text-primary';
                    let icon = 'fa-circle-info';
                    if (data === 'high') { cls = 'bg-danger-soft text-danger'; icon = 'fa-fire-flame-curved'; }
                    if (data === 'medium') { cls = 'bg-warning-soft text-warning'; icon = 'fa-clock'; }
                    return `<span class="badge ${cls} rounded-pill px-3 py-2 font-outfit fw-bold border-0 shadow-sm" style="font-size: 0.7rem; min-width: 90px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas ${icon} me-2" style="font-size: 0.8rem;"></i>${data}</span>`;
                }
            },
            { 
                data: 'assigned_to_name',
                render: function(data, type, row) {
                    const name = data || 'Unassigned';
                    const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    return `
                        <div class="d-flex align-items-center py-2">
                            <div class="rounded-circle bg-primary-grad text-white d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm border border-2 border-white" style="width: 40px; height: 40px; min-width: 40px; font-size: 0.8rem;">
                                ${initials}
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <div class="fw-bold text-neutral-900 font-outfit text-sm lh-1 mb-1">${name}</div>
                                <div class="text-xs text-neutral-400 fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.02em;">${row.role_name || 'Member'}</div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'project_name',
                render: function(data, type, row) {
                    const name = data || '[ No Project ]';
                    const client = row.client_name || 'Internal';
                    return `
                        <div class="py-1">
                            <div class="text-neutral-900 fw-bold font-outfit text-sm mb-1">${name}</div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary-soft text-primary p-0 me-2" style="font-size: 0.6rem; background: transparent !important;"><i class="fas fa-building me-1"></i></span>
                                <span class="text-xs text-neutral-400 fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.05em;">${client}</span>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: null,
                className: 'text-end pe-4',
                orderable: false,
                render: function(data, type, row) {
                    const isAdmin = '<?= $_SESSION['user_role'] ?>' === 'admin';
                    let recurringActions = '';
                    
                    if (isAdmin) {
                        recurringActions = `
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header text-xs text-uppercase fw-bold text-neutral-400">Automation</h6></li>
                            <li><a class="dropdown-item py-2 text-sm fw-medium enable-recurring" href="javascript:void(0)" data-id="${row.id}" data-type="daily"><i class="fas fa-calendar-day me-2 text-purple-500"></i>Repeat Daily</a></li>
                            <li><a class="dropdown-item py-2 text-sm fw-medium enable-recurring" href="javascript:void(0)" data-id="${row.id}" data-type="weekly"><i class="fas fa-calendar-week me-2 text-purple-500"></i>Repeat Weekly</a></li>
                            <li><a class="dropdown-item py-2 text-sm fw-medium enable-recurring" href="javascript:void(0)" data-id="${row.id}" data-type="monthly"><i class="fas fa-calendar-alt me-2 text-purple-500"></i>Repeat Monthly</a></li>
                            ${row.is_recurring == 1 ? `<li><a class="dropdown-item py-2 text-sm fw-medium disable-recurring text-danger" href="javascript:void(0)" data-id="${row.id}"><i class="fas fa-stop-circle me-2"></i>Disable Repeat</a></li>` : ''}
                            <li><a class="dropdown-item py-2 text-sm fw-medium view-recurring-logs" href="javascript:void(0)" data-id="${row.id}"><i class="fas fa-history me-2 text-neutral-500"></i>View History</a></li>
                        `;
                    }

                    return `
                        <div class="dropdown">
                            <button class="btn action-btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end glass-card border-0 shadow-lg py-2 animate-fade-in" style="min-width: 200px;">
                                <li><h6 class="dropdown-header text-xs text-uppercase fw-bold text-neutral-400">Manage</h6></li>
                                <li><a class="dropdown-item py-2 text-sm fw-medium edit-task" href="javascript:void(0)"><i class="fas fa-edit me-2 text-primary"></i>Edit</a></li>
                                ${isAdmin ? `<li><a class="dropdown-item py-2 text-sm fw-medium delete-task text-danger" href="javascript:void(0)" data-id="${row.id}"><i class="fas fa-trash-alt me-2"></i>Delete</a></li>` : ''}
                                ${recurringActions}
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        order: [[5, 'asc']],
        dom: '<"d-flex justify-content-between align-items-center p-4"f<"d-flex gap-3"l>>t<"d-flex justify-content-between align-items-center p-4 border-top border-light"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search task specifying keywords...",
            lengthMenu: "_MENU_ per page",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });

    $('.dataTables_filter input').addClass('form-control border-0 bg-neutral-50 rounded-pill px-4').attr('placeholder', 'Search tasks...').css({'height': '45px'});

    $('#edit_progress_range').on('input', function() {
        const val = $(this).val();
        $('#progress_val').text(val + '%');
        $('#edit_progress').val(val);
    });

    $('.add-progress-range').on('input', function() {
        const val = $(this).val();
        $('.add-progress-val').text(val + '%');
        $('.add-progress-input').val(val);
    });

    $('#filter_project, #filter_assignee, #filter_status').on('change', () => table.ajax.reload());
    $('#resetFilters').on('click', () => { $('#filterForm')[0].reset(); table.ajax.reload(); });

    // Range Slider UI Synchronization
    $(document).on('input', '.add-progress-range', function() {
        const val = $(this).val();
        $(this).closest('.task-block-card').find('.add-progress-val').text(val + '%');
        $(this).closest('.task-block-card').find('.add-progress-input').val(val);
    });

    $(document).on('input', '#edit_progress_range', function() {
        const val = $(this).val();
        $('#progress_val').text(val + '%');
        $('#edit_progress').val(val);
    });

    // Dynamic Task Blocks Logic
    let taskCount = 1;
    $('#add-more-tasks').on('click', function() {
        const firstBlock = $('.task-block-card').first();
        const newBlock = firstBlock.clone();
        
        // Reset values and update indices
        newBlock.find('input, select, textarea').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/tasks\[\d+\]/, `tasks[${taskCount}]`));
            }
            if ($(this).is('input') || $(this).is('textarea')) {
                if ($(this).attr('type') !== 'date' && $(this).attr('type') !== 'time' && $(this).attr('type') !== 'hidden') {
                    $(this).val('');
                } else if ($(this).hasClass('add-progress-input')) {
                    $(this).val(0);
                }
            } else if ($(this).is('select')) {
                $(this).prop('selectedIndex', 0);
            }
        });

        // Reset progress slider UI
        newBlock.find('.add-progress-range').val(0);
        newBlock.find('.add-progress-val').text('0%');
        
        // Update number badge
        newBlock.find('.bg-primary-grad').text(taskCount + 1);
        
        // Show remove button
        newBlock.find('.remove-task-block').removeClass('d-none');
        
        // Append with animation
        newBlock.hide().appendTo('#task-blocks-container').slideDown(400);
        taskCount++;
    });

    $(document).on('click', '.remove-task-block', function() {
        $(this).closest('.task-block-card').slideUp(400, function() {
            $(this).remove();
            reindexTaskBlocks();
        });
    });

    function reindexTaskBlocks() {
        taskCount = 0;
        $('.task-block-card').each(function() {
            const index = taskCount;
            $(this).find('.bg-primary-grad').text(index + 1);
            $(this).find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/tasks\[\d+\]/, `tasks[${index}]`));
                }
            });
            if (index === 0) {
                $(this).find('.remove-task-block').addClass('d-none');
            } else {
                $(this).find('.remove-task-block').removeClass('d-none');
            }
            taskCount++;
        });
    }

    handleFormSubmit('#addTaskForm', () => { 
        $('#addTaskModal').modal('hide'); 
        // Reset dynamic blocks to only one
        $('.task-block-card:not(:first)').remove();
        $('#addTaskForm')[0].reset(); 
        reindexTaskBlocks();
        table.ajax.reload(); 
    });
    handleFormSubmit('#editTaskForm', () => { $('#editTaskModal').modal('hide'); table.ajax.reload(); });

    $(document).on('click', '.edit-task', function(e) {
        const trigger = e.currentTarget;
        const $tr = $(this).closest('.dropdown-menu').data('original-tr') || $(this).closest('tr');
        const data = table.row($tr).data();
        $('#edit_id').val(data.id);
        $('#edit_project_id').val(data.project_id);
        $('#edit_title').val(data.title);
        $('#edit_description').val(data.description);
        $('#edit_assigned_to').val(data.assigned_to);
        $('#edit_role_id').val(data.role_id);
        $('#edit_priority').val(data.priority);
        $('#edit_status').val(data.status);
        if (data.due_date) {
            const dt = data.due_date.split(' ');
            $('#edit_due_date').val(dt[0]);
            $('#edit_due_time').val(data.due_time ? data.due_time.substring(0, 5) : '09:00');
        }
        const prog = data.progress_percentage || 0;
        $('#edit_progress').val(prog);
        $('#edit_progress_range').val(prog);
        $('#progress_val').text(prog + '%');
        $('#edit_notes').val(data.status_notes);
        
        const modal = bootstrap.Modal.getOrCreateInstance('#editTaskModal');
        modal.show(trigger);
    });

    // Fix for "Blocked aria-hidden on an element because its descendant retained focus"
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement && this.contains(document.activeElement)) {
            document.activeElement.blur();
        }
    });

    $(document).on('click', '.delete-task', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Task?',
            text: "This operation cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/api/tasks/delete') ?>', { id: id }, (res) => {
                    if (res.status === 'success' || res.success) { 
                        toastr.success(res.message || 'Task deleted'); 
                        table.ajax.reload(null, false); 
                    } else { 
                        toastr.error(res.message || 'Failed to delete task'); 
                    }
                }).fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred');
                });
            }
        });
    });

    // Recurring Task Handlers
    $(document).on('click', '.enable-recurring', function() {
        const id = $(this).data('id');
        const type = $(this).data('type');
        const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);

        Swal.fire({
            title: `Enable ${typeLabel} Repeat?`,
            text: `This will automatically create a new task every ${type === 'daily' ? 'day' : (type === 'weekly' ? 'week' : 'month')}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8b5cf6',
            confirmButtonText: 'Yes, enable it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/api/tasks/recurring/enable') ?>', { id: id, type: type }, (res) => {
                    if (res.status === 'success' || res.success) {
                        toastr.success(res.message);
                        table.ajax.reload(null, false);
                    } else {
                        toastr.error(res.message);
                    }
                }).fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred');
                });
            }
        });
    });

    $(document).on('click', '.disable-recurring', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Disable Recurring?',
            text: "No future tasks will be generated for this automation.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Yes, disable it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/api/tasks/recurring/disable') ?>', { id: id }, (res) => {
                    if (res.status === 'success' || res.success) {
                        toastr.success(res.message);
                        table.ajax.reload(null, false);
                    } else {
                        toastr.error(res.message);
                    }
                }).fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred');
                });
            }
        });
    });

    $(document).on('click', '.view-recurring-logs', function() {
        const id = $(this).data('id');
        const container = $('#recurring-logs-container');
        container.html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $('#recurringHistoryModal').modal('show');

        $.get('<?= url('/api/tasks/recurring/logs') ?>', { id: id }, (res) => {
            if ((res.status === 'success' || res.success) && res.data && res.data.length > 0) {
                let html = '<div class="list-group list-group-flush">';
                res.data.forEach(log => {
                    html += `
                        <div class="list-group-item border-0 px-0 py-3 animate-fade-in">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary-soft text-primary rounded-pill px-3 py-1 text-xs fw-bold text-uppercase">${log.recurring_type}</span>
                                <span class="text-xs text-neutral-400 fw-medium">${moment(log.created_at).format('DD MMM YYYY, hh:mm A')}</span>
                            </div>
                            <h6 class="text-sm fw-bold text-neutral-900 mb-1">${log.generated_task_title}</h6>
                            <p class="text-xs text-neutral-500 mb-0">Generated by ${log.creator_name}</p>
                        </div>
                    `;
                });
                html += '</div>';
                container.html(html);
            } else {
                container.html(`
                    <div class="text-center py-5">
                        <div class="bg-neutral-100 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-history text-neutral-400 fs-4"></i>
                        </div>
                        <h6 class="text-neutral-900 fw-bold">No History Found</h6>
                        <p class="text-neutral-500 text-xs mb-0">No recurring tasks have been generated yet.</p>
                    </div>
                `);
            }
        }).fail((xhr) => {
            container.html('<div class="text-center py-4 text-danger">Failed to load history</div>');
        });
    });
});
</script>

<style>
.progress-bar { transition: width 1.2s cubic-bezier(0.34, 1.56, 0.64, 1); overflow: visible; }
.progress-glow {
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: progress-glow 2s infinite;
}
@keyframes progress-glow { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

.timeline-dot { width: 8px; height: 8px; border-radius: 50%; }

.action-btn-sm {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: none;
    background: transparent;
    color: var(--neutral-400);
    transition: all 0.3s ease;
}
.action-btn-sm:hover { background: var(--neutral-100); }
.action-btn-sm.edit-task:hover { background: #eef2ff; }
.action-btn-sm.edit-task:hover i { color: #4338ca !important; }
.action-btn-sm.delete-task:hover { background: #fff1f2; }
.action-btn-sm.delete-task:hover i { color: #be123c !important; }

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 10px !important;
    padding: 0.5rem 0.9rem !important;
    border: none !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--grad-primary) !important;
    color: white !important;
}

.form-range::-webkit-slider-runnable-track { background: var(--neutral-200); height: 6px; border-radius: 10px; }
.form-range::-webkit-slider-thumb { margin-top: -6px; background: var(--primary-500); width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.2s ease; }
.form-range::-webkit-slider-thumb:active { transform: scale(1.2); }

/* Premium Glassmorphism Form Elements */
.glass-input, 
.form-control.glass-input, 
.form-select.glass-input,
.input-group.glass-input {
    background: rgba(255, 255, 255, 0.6) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    border-radius: 14px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    height: 52px !important;
    min-height: 52px !important;
    padding: 0 1.25rem !important;
    display: flex !important;
    align-items: center !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    color: #1e293b !important;
}

.glass-input:focus,
.glass-input:focus-within {
    background: #fff !important;
    border-color: #8b5cf6 !important;
    box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1) !important;
}

/* Internal Reset for Inputs/Selects */
.glass-input input, 
.glass-input select,
select.glass-input,
input.glass-input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    height: 100% !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    line-height: 52px !important; /* Matches height for vertical centering */
    color: inherit !important;
}

/* Specific Select Fixes */
select.glass-input, 
.form-select.glass-input {
    appearance: none !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 1.25rem center !important;
    background-size: 14px 10px !important;
    padding-right: 3rem !important;
}

/* Date/Time Input Align */
input[type="date"].glass-input,
input[type="time"].glass-input {
    display: inline-flex !important;
}

/* Placeholder Styling */
.glass-input::placeholder {
    color: #94a3b8 !important;
    font-weight: 400 !important;
}

.btn-primary-grad {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    border: none !important;
    color: white !important;
    box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary-grad:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 15px 35px -5px rgba(99, 102, 241, 0.5);
    color: white !important;
}

.btn-secondary-soft {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-secondary-soft:hover {
    background: #e2e8f0;
    color: #1e293b;
}

/* Premium Slider */
.premium-slider-wrap {
    display: flex;
    align-items: center;
    background: rgba(248, 250, 252, 0.6);
    padding: 0 1.25rem;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    flex-grow: 1;
    height: 52px !important;
}

.premium-slider {
    height: 6px;
    flex-grow: 1;
}

.premium-slider::-webkit-slider-runnable-track {
    background: #e2e8f0;
    height: 6px;
    border-radius: 10px;
}

.premium-slider::-webkit-slider-thumb {
    margin-top: -7px;
    background: #8b5cf6;
    width: 20px;
    height: 20px;
    border: 4px solid #fff;
    box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3);
    cursor: pointer;
    transition: all 0.2s ease;
}

.premium-slider::-webkit-slider-thumb:hover {
    transform: scale(1.15);
    background: #7c3aed;
}

/* Dynamic Task Block Animations */
.task-block-card {
    transition: all 0.4s ease;
    border: 1px solid rgba(226, 232, 240, 0.5) !important;
    background: rgba(255, 255, 255, 0.4);
}

.animate-fade-in {
    animation: fadeIn 0.5s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.btn-primary-soft:hover {
    transform: translateY(-1px);
}

/* DataTables UI Overrides */
.dataTables_length, .dataTables_filter {
    padding: 1.5rem 1.5rem 1rem !important;
}

.dataTables_filter input {
    background: rgba(248, 250, 252, 0.8) !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 0.6rem 1.25rem !important;
    font-size: 0.85rem !important;
    font-weight: 500;
    transition: all 0.3s ease;
    width: 250px !important;
}

.dataTables_filter input:focus {
    border-color: #8b5cf6 !important;
    background: #fff !important;
    box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1) !important;
    outline: none;
}

.dataTables_length select {
    background: rgba(248, 250, 252, 0.8) !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 0.4rem 0.8rem !important;
    font-weight: 600;
    cursor: pointer;
}

.bg-primary-grad {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%) !important;
}

.btn-primary-grad {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%) !important;
    color: white !important;
    border: none !important;
    transition: all 0.3s ease;
}

.btn-primary-grad:hover {
    filter: brightness(1.1);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
}

.font-outfit { font-family: 'Outfit', sans-serif; }

/* Custom Task View Enhancements */
.bg-purple-grad {
    background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
    box-shadow: 0 4px 12px rgba(168, 85, 247, 0.2);
}

.text-purple-500 {
    color: #a855f7 !important;
}

.dropdown-item.text-danger:hover {
    background: #fff1f2 !important;
    color: #e11d48 !important;
}
</style>
