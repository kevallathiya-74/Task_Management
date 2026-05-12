<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h4 class="fw-bold mb-0 text-neutral-900">Task Management</h4>
                <p class="text-neutral-500 text-xs mb-0">Track and manage project tasks and assignments</p>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary rounded-pill px-3 py-2 text-xs shadow-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="fas fa-plus-circle me-1"></i> New Task
                </button>
            </div>
        </div>

        <!-- Dynamic Filters -->
        <div class="glass-card mb-3 p-3">
            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-xl-3 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-2 ms-1">Project</label>
                    <select class="form-select border-0 bg-neutral-50 rounded-3 py-2 text-sm" name="project_id" id="filter_project">
                        <option value="">All Projects</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= (isset($project_id) && $project_id == $p['id']) ? 'selected' : '' ?>>
                                <?= $p['project_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-2 ms-1">Assigned To</label>
                    <select class="form-select border-0 bg-neutral-50 rounded-3 py-2 text-sm" name="assigned_to" id="filter_assignee">
                        <option value="">All Members</option>
                        <?php foreach ($staff as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-2 ms-1">Status</label>
                    <select class="form-select border-0 bg-neutral-50 rounded-3 py-2 text-sm" name="status" id="filter_status">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-xl-3 col-md-6">
                    <button type="button" id="resetFilters" class="btn btn-light rounded-3 py-2 w-100 text-xs fw-bold text-neutral-600">
                        <i class="fas fa-filter me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Task Monitoring Grid -->
        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tasksTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Task Details</th>
                            <th>Project</th>
                            <th>Assignee To</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th class="text-end pe-4">Manage</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0">New Task</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTaskForm" action="<?= url('/api/tasks') ?>" method="POST">
                <div class="modal-body py-4">
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Task Title</label>
                        <input type="text" class="form-control" name="title" required placeholder="Enter task title">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Task Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Enter task description"></textarea>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Project</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-2" name="project_id" required>
                                <option value="">Select Project</option>
                                <?php foreach ($projects as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= (isset($project_id) && $project_id == $p['id']) ? 'selected' : '' ?>>
                                        <?= $p['project_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Assign To</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="assigned_to" required>
                                <option value="">Select Member</option>
                                <?php foreach ($staff as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?> (<?= $s['role_name'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Department Role</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="role_id" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Priority</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Status</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="status">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Expected Delivery</label>
                            <div class="input-group gap-0">
                                <span class="input-group-text bg-neutral-100 border-0 rounded-start-3 px-3"><i class="far fa-calendar text-primary"></i></span>
                                <input type="date" class="form-control bg-neutral-100 border-0 py-3" name="due_date" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                <span class="input-group-text bg-neutral-100 border-0 px-2"><i class="far fa-clock text-primary"></i></span>
                                <input type="time" class="form-control bg-neutral-100 border-0 rounded-end-3 py-3" name="due_time" required value="09:00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-3">
                    <button type="button" class="btn btn-light flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0">Update Task Progress</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm" action="<?= url('/api/tasks/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body py-4">
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Task Title</label>
                        <input type="text" class="form-control" name="title" id="edit_title" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Assign To</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="assigned_to" id="edit_assigned_to" required>
                                <?php foreach ($staff as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Department Role</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="role_id" id="edit_role_id" required>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Priority</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="priority" id="edit_priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Status</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="status" id="edit_status">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Expected Delivery</label>
                            <div class="input-group gap-0">
                                <span class="input-group-text bg-neutral-100 border-0 rounded-start-3 px-3"><i class="far fa-calendar text-primary"></i></span>
                                <input type="date" class="form-control bg-neutral-100 border-0 py-3" name="due_date" id="edit_due_date" required>
                                <span class="input-group-text bg-neutral-100 border-0 px-2"><i class="far fa-clock text-primary"></i></span>
                                <input type="time" class="form-control bg-neutral-100 border-0 rounded-end-3 py-3" name="due_time" id="edit_due_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Completion (%)</label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range me-3" name="progress_percentage" id="edit_progress_range" min="0" max="100" step="5">
                                <span class="fw-bold text-primary" id="progress_val">0%</span>
                                <input type="hidden" name="progress_percentage" id="edit_progress">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Status Note</label>
                            <input type="text" class="form-control rounded-3 py-3" name="status_notes" id="edit_notes" placeholder="Reason for status change">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-3">
                    <button type="button" class="btn btn-light flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3"> Save Changes</button>
                </div>
            </form>
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
        columns: [
            { 
                data: 'title',
                render: function(data, type, row) {
                    return `
                        <div class="ps-2 py-2">
                            <div class="fw-bold text-neutral-900 mb-1 fs-6">${data}</div>
                            <div class="text-xs text-neutral-400 text-truncate font-medium" style="max-width: 250px;">${row.description || 'Detailed scope pending specification.'}</div>
                        </div>
                    `;
                }
            },
            { 
                data: 'project_name',
                render: function(data) {
                    return `
                        <div class="d-flex align-items-center">
                            <span class="text-xs text-neutral-600">${data}</span>
                        </div>
                    `;
                }
            },
            { 
                data: 'assigned_to_name',
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data)}&background=random" width="32" height="32" class="rounded-circle me-3">
                            <div class="text-xs">
                                <div class="fw-bold text-neutral-800">${data}</div>
                                <div class="text-neutral-400">${row.role_name}</div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'priority',
                render: function(data) {
                    let cls = 'badge-soft-primary';
                    let icon = 'fa-arrow-down';
                    if (data === 'high') { cls = 'badge-soft-danger'; icon = 'fa-arrow-up'; }
                    if (data === 'medium') { cls = 'badge-soft-warning'; icon = 'fa-minus'; }
                    return `<span class="badge ${cls} text-capitalize px-3 py-2 rounded-pill"><i class="fas ${icon} me-2 small"></i>${data}</span>`;
                }
            },
            { 
                data: 'status',
                render: function(data, type, row) {
                    const progress = row.progress_percentage || 0;
                    let cls = 'badge-soft-primary';
                    if (data === 'in_progress') cls = 'badge-soft-warning';
                    if (data === 'review') cls = 'badge-soft-info';
                    if (data === 'completed') cls = 'badge-soft-success';
                    
                    return `
                        <div style="width: 130px;">
                            <div class="d-flex justify-content-between text-xs mb-1">
                                <span class="badge ${cls} py-1 px-2 rounded-pill">${data.replace('_', ' ')}</span>
                                <span class="fw-bold text-neutral-800">${progress}%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 4px; background: var(--neutral-100);">
                                <div class="progress-bar bg-primary rounded-pill" style="width: ${progress}%"></div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'due_date',
                render: function(data, type, row) {
                    const date = new Date(data);
                    const today = new Date();
                    const isOverdue = date < today && row.status !== 'completed';
                    return `
                        <div class="d-flex align-items-center">
                            <div class="me-3 p-2 rounded-pill ${isOverdue ? 'bg-danger-soft text-danger' : 'bg-neutral-50 text-neutral-400'}">
                                <i class="far fa-calendar-check text-xs"></i>
                            </div>
                            <div class="text-xs">
                                <div class="text-neutral-400">Target</div>
                                <div class="fw-bold ${isOverdue ? 'text-danger' : 'text-neutral-900'} d-flex align-items-center flex-nowrap" style="white-space: nowrap;">
                                    ${date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' })} 
                                    <span class="text-neutral-400 fw-normal ms-2 small">
                                        ${row.due_time ? moment(row.due_time, 'HH:mm:ss').format('hh:mm A') : ''}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: null,
                className: 'text-end pe-4',
                orderable: false,
                render: function(data) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-light rounded-circle p-0" style="width: 38px; height: 38px;" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-neutral-500"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-xl border-0 rounded-4 p-2">
                                <li><a class="dropdown-item rounded-3 py-2 edit-task" href="javascript:void(0)"><i class="fas fa-edit me-2 text-primary"></i>Update</a></li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li><a class="dropdown-item rounded-3 py-2 text-danger delete-task" href="javascript:void(0)" data-id="${data.id}" data-title="${data.title}"><i class="fas fa-trash-alt me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        order: [[5, 'asc']],
        dom: '<"d-flex justify-content-between align-items-center p-4 border-bottom border-light"f<"d-flex"l>>t<"d-flex justify-content-between align-items-center p-4"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search tasks...",
            paginate: {
                previous: '<i class="fas fa-chevron-left text-xs"></i>',
                next: '<i class="fas fa-chevron-right text-xs"></i>'
            }
        }
    });

    // Custom search input
    $('.dataTables_filter input').addClass('form-control border-0 bg-neutral-50 rounded-pill px-3 py-1').css({'min-width': '240px', 'font-size': '0.75rem'});

    // Progress Range Handling
    $('#edit_progress_range').on('input', function() {
        const val = $(this).val();
        $('#progress_val').text(val + '%');
        $('#edit_progress').val(val);
    });

    $('#filter_project, #filter_assignee, #filter_status').on('change', () => table.ajax.reload());
    $('#resetFilters').on('click', () => { $('#filterForm')[0].reset(); table.ajax.reload(); });

    handleFormSubmit('#addTaskForm', () => { $('#addTaskModal').modal('hide'); $('#addTaskForm')[0].reset(); table.ajax.reload(); });
    handleFormSubmit('#editTaskForm', () => { $('#editTaskModal').modal('hide'); table.ajax.reload(); });

    $(document).on('click', '.edit-task', function() {
        const data = table.row($(this).closest('tr')).data();
        $('#edit_id').val(data.id);
        $('#edit_title').val(data.title);
        $('#edit_description').val(data.description);
        $('#edit_assigned_to').val(data.assigned_to);
        $('#edit_role_id').val(data.role_id);
        $('#edit_priority').val(data.priority);
        $('#edit_status').val(data.status);
        if (data.due_date) {
            const dt = data.due_date.split(' ');
            $('#edit_due_date').val(dt[0]);
            $('#edit_due_time').val(dt[1] ? dt[1].substring(0, 5) : '09:00');
        }
        
        const prog = data.progress_percentage || 0;
        $('#edit_progress').val(prog);
        $('#edit_progress_range').val(prog);
        $('#progress_val').text(prog + '%');
        
        $('#edit_notes').val(data.status_notes);
        $('#editTaskModal').modal('show');
    });

    $(document).on('click', '.delete-task', function() {
        const id = $(this).data('id');
        $.post('<?= url('/api/tasks/delete') ?>', { id: id }, (res) => {
            if (res.success) { 
                toastr.success(res.message); 
                table.ajax.reload(null, false); 
            } else { 
                toastr.error(res.message); 
            }
        }, 'json');
    });
});
</script>

<style>
.bg-danger-soft { background: #fee2e2; }

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--grad-primary) !important;
    color: white !important;
    border: none !important;
    border-radius: 10px !important;
}

.modal-content {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(25px);
}

.shadow-primary {
    box-shadow: 0 10px 20px -5px rgba(139, 92, 246, 0.4) !important;
}

.form-range::-webkit-slider-runnable-track { background: var(--neutral-100); height: 8px; border-radius: 10px; }
.form-range::-webkit-slider-thumb { margin-top: -4px; background: var(--primary-500); width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }

.input-group-text {
    color: var(--neutral-400);
    font-size: 0.9rem;
}

.input-group .form-control:focus {
    z-index: 3;
    box-shadow: none;
}

/* Hide native browser date/time icons */
input[type="date"]::-webkit-calendar-picker-indicator,
input[type="time"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
    z-index: 2;
}

/* Remove default padding for native icons */
input[type="date"], input[type="time"] {
    position: relative;
}
</style>
