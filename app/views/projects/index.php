<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h4 class="fw-bold mb-0 text-neutral-900">Project Management</h4>
                <p class="text-neutral-500 text-xs mb-0">Track project life cycles and client milestones.</p>
            </div>
            <?php if ($_SESSION['user_role'] == 'admin'): ?>
            <div class="col-auto">
                <button type="button" class="btn btn-primary rounded-pill px-3 py-2 text-xs" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                    <i class="fas fa-plus-circle me-1"></i> New Project
                </button>
            </div>
            <?php endif; ?>
        </div>

        <!-- Smart Filters -->
        <div class="glass-card mb-3 p-3">
            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-2 ms-1">Department</label>
                    <select class="form-select border-0 bg-neutral-50 rounded-3 py-2 text-sm" name="role_id" id="filter_role">
                        <option value="">All Departments</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-2 ms-1">Status</label>
                    <select class="form-select border-0 bg-neutral-50 rounded-3 py-2 text-sm" name="status" id="filter_status">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12">
                    <button type="button" id="resetFilters" class="btn btn-light rounded-3 py-2 w-100 text-xs fw-bold text-neutral-600">
                        <i class="fas fa-filter me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Projects Presentation -->
        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="projectsTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Project & Client</th>
                            <th>Department</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Timeline</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0">New Project</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProjectForm" action="<?= url('/api/projects') ?>" method="POST">
                <div class="modal-body py-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Project Name</label>
                            <input type="text" class="form-control" name="project_name" required placeholder="Project Name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Client Name</label>
                            <input type="text" class="form-control" name="client_name" required placeholder="Client Name">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Description"></textarea>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Department</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="role_id" required>
                                <option value="">Select Department</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Status</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="status">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Deadline</label>
                            <input type="date" class="form-control" name="deadline" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-3">
                    <button type="button" class="btn btn-light flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0">Edit Project</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProjectForm" action="<?= url('/api/projects/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body py-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Project Name</label>
                            <input type="text" class="form-control" name="project_name" id="edit_project_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Client Name</label>
                            <input type="text" class="form-control" name="client_name" id="edit_client_name" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Department</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="role_id" id="edit_role_id" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Status</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="status" id="edit_status">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="edit_start_date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Deadline</label>
                            <input type="date" class="form-control" name="deadline" id="edit_deadline">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-3">
                    <button type="button" class="btn btn-light flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const table = $('#projectsTable').DataTable({
        ajax: {
            url: '<?= url('/api/projects') ?>',
            dataSrc: 'data',
            data: function(d) {
                d.role_id = $('#filter_role').val();
                d.status = $('#filter_status').val();
            }
        },
        columns: [
            { 
                data: 'project_name',
                render: function(data, type, row) {
                    return `
                        <div class="ps-2 py-2">
                            <div class="fw-bold text-neutral-900 mb-1 fs-6">${data}</div>
                            <div class="d-flex align-items-center text-xs text-primary font-semibold mb-2">
                                <i class="fas fa-building me-2 opacity-50"></i>${row.client_name}
                            </div>
                            <div class="text-xs text-neutral-400 text-truncate font-medium" style="max-width: 280px;">${row.description || 'No summary provided.'}</div>
                        </div>
                    `;
                }
            },
            { 
                data: 'department_name',
                render: function(data) {
                    return `
                        <div class="d-flex align-items-center">
                            <span class="text-sm fw-semibold text-neutral-700">${data}</span>
                        </div>
                    `;
                }
            },
            { 
                data: null,
                render: function(data) {
                    const total = parseInt(data.total_tasks) || 0;
                    const completed = parseInt(data.completed_tasks) || 0;
                    const progress = total > 0 ? Math.round((completed / total) * 100) : 0;
                    
                    let barClass = 'bg-primary';
                    if(progress === 100) barClass = 'bg-success';
                    if(progress < 30) barClass = 'bg-danger';

                    return `
                        <div style="width: 180px;">
                            <div class="d-flex justify-content-between text-xs mb-2">
                                <span class="fw-bold text-neutral-800">${progress}% Complete</span>
                                <span class="text-neutral-400">${completed}/${total} Tasks</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px; background: var(--neutral-100);">
                                <div class="progress-bar ${barClass} rounded-pill shadow-none" role="progressbar" style="width: ${progress}%"></div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    let cls = 'badge-soft-primary';
                    if (data === 'active') cls = 'badge-soft-warning';
                    if (data === 'completed') cls = 'badge-soft-success';
                    if (data === 'cancelled') cls = 'badge-soft-danger';
                    return `<span class="badge ${cls} text-capitalize px-3 py-2 rounded-pill">${data}</span>`;
                }
            },
            { 
                data: null,
                render: function(data) {
                    const deadline = data.deadline ? new Date(data.deadline).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }) : 'N/A';
                    return `
                        <div class="d-flex align-items-center">
                            <div class="me-3 p-2 rounded-pill bg-neutral-50 text-neutral-400">
                                <i class="far fa-clock text-xs"></i>
                            </div>
                            <div class="text-xs">
                                <div class="text-neutral-400">Deadline</div>
                                <div class="text-neutral-900 fw-bold">${deadline}</div>
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
                    const isAdmin = '<?= $_SESSION['user_role'] ?>' === 'admin';
                    return `
                        <div class="dropdown">
                            <button class="btn btn-light rounded-circle p-0" style="width: 38px; height: 38px;" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-neutral-500"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-xl border-0 rounded-4 p-2">
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= url('/tasks?project_id=') ?>${data.id}"><i class="fas fa-list-check me-2 text-info"></i>Task Board</a></li>
                                ${isAdmin ? `
                                    <li><hr class="dropdown-divider opacity-50"></li>
                                    <li><a class="dropdown-item rounded-3 py-2 edit-project" href="javascript:void(0)"><i class="fas fa-edit me-2 text-primary"></i>Update</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2 text-danger delete-project" href="javascript:void(0)" data-id="${data.id}" data-name="${data.project_name}"><i class="fas fa-trash-alt me-2"></i>Terminate</a></li>
                                ` : ''}
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        order: [[4, 'desc']],
        dom: '<"d-flex justify-content-between align-items-center p-4 border-bottom border-light"f<"d-flex"l>>t<"d-flex justify-content-between align-items-center p-4"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search projects",
            lengthMenu: "_MENU_ per page",
            paginate: {
                previous: '<i class="fas fa-chevron-left text-xs"></i>',
                next: '<i class="fas fa-chevron-right text-xs"></i>'
            }
        }
    });

    // Search input custom styling
    $('.dataTables_filter input').addClass('form-control border-0 bg-neutral-50 rounded-pill px-3 py-1').css({'min-width': '240px', 'font-size': '0.75rem'});

    $('#filter_role, #filter_status').on('change', () => table.ajax.reload());
    $('#resetFilters').on('click', () => { $('#filterForm')[0].reset(); table.ajax.reload(); });

    handleFormSubmit('#addProjectForm', () => { $('#addProjectModal').modal('hide'); $('#addProjectForm')[0].reset(); table.ajax.reload(); });
    handleFormSubmit('#editProjectForm', () => { $('#editProjectModal').modal('hide'); table.ajax.reload(); });

    $(document).on('click', '.edit-project', function() {
        const data = table.row($(this).closest('tr')).data();
        $('#edit_id').val(data.id);
        $('#edit_project_name').val(data.project_name);
        $('#edit_client_name').val(data.client_name);
        $('#edit_description').val(data.description);
        $('#edit_role_id').val(data.role_id);
        $('#edit_status').val(data.status);
        $('#edit_start_date').val(data.start_date);
        $('#edit_deadline').val(data.deadline);
        $('#editProjectModal').modal('show');
    });

    $(document).on('click', '.delete-project', function() {
        const id = $(this).data('id');
        $.post('<?= url('/api/projects/delete') ?>', { id: id }, (res) => {
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
.progress-bar {
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
}

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
</style>
