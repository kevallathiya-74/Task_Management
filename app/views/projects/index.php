<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1">Project Management</h3>
                <p class="text-neutral-500 mb-0">Track project life cycles and client milestones</p>
            </div>
            <?php if ($_SESSION['user_role'] == 'admin'): ?>
            <button type="button" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                <i class="fas fa-plus me-2"></i> New Project
            </button>
            <?php endif; ?>
        </div>

        <!-- Smart Filters -->
        <div class="glass-card mb-5 p-4">
            <form id="filterForm" class="row g-4 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-3 ms-1">Department</label>
                    <div class="input-group bg-neutral-50 rounded-pill">
                        <span class="input-group-text ps-3">
                            <i class="fas fa-building-user text-neutral-300"></i>
                        </span>
                        <select class="form-select text-sm fw-bold h-100" name="role_id" id="filter_role">
                            <option value="">All Departments</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-3 ms-1">Lifecycle Status</label>
                    <div class="input-group bg-neutral-50 rounded-pill">
                        <span class="input-group-text ps-3">
                            <i class="fas fa-chart-pie text-neutral-300"></i>
                        </span>
                        <select class="form-select text-sm fw-bold h-100" name="status" id="filter_status">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <button type="button" id="resetFilters" class="btn btn-secondary border-0 bg-neutral-50 w-100 rounded-pill h-100 text-xs fw-bold text-neutral-600">
                        <i class="fas fa-filter me-2"></i> Reset Filters
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="projectsTable" style="min-width: 1000px;">
                <thead>
                    <tr>
                        <th class="ps-4">Project & Client</th>
                        <th>Department</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</main>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-4">
                <div>
                    <h4 class="fw-bold text-neutral-900 mb-1">New Project</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addProjectForm" action="<?= url('/api/projects') ?>" method="POST">
                <div class="modal-body py-0">
                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Project Name</label>
                            <input type="text" class="form-control rounded-4" name="project_name" required placeholder="e.g. Phoenix E-commerce Revamp">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Client Name</label>
                            <input type="text" class="form-control rounded-4" name="client_name" required placeholder="Client Name">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Description</label>
                        <textarea class="form-control rounded-4" name="description" rows="3" placeholder="Detail the core objectives and deliverables..."></textarea>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Department Role</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="role_id" required>
                                <option value="">Select Department</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Status</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="status">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-4 mb-2">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Start Date</label>
                            <input type="date" class="form-control rounded-4" name="start_date" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Final Deadline</label>
                            <input type="date" class="form-control rounded-4" name="deadline" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-5 gap-3">
                    <button type="button" class="btn btn-secondary flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-4">
                <div>
                    <h4 class="fw-bold text-neutral-900 mb-1">Edit Project Details</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProjectForm" action="<?= url('/api/projects/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body py-0">
                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Project Name</label>
                            <input type="text" class="form-control rounded-4" name="project_name" id="edit_project_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Client Name</label>
                            <input type="text" class="form-control rounded-4" name="client_name" id="edit_client_name" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Description</label>
                        <textarea class="form-control rounded-4" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Department</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="role_id" id="edit_role_id" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">State</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="status" id="edit_status">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-4 mb-2">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Start Date</label>
                            <input type="date" class="form-control rounded-4" name="start_date" id="edit_start_date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Final Deadline</label>
                            <input type="date" class="form-control rounded-4" name="deadline" id="edit_deadline">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-5 gap-3">
                    <button type="button" class="btn btn-secondary flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Update Project</button>
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
        scrollX: true,
        autoWidth: false,
        columns: [
            { 
                data: 'project_name',
                render: function(data, type, row) {
                    return `
                        <div class="py-2">
                            <div class="fw-bold text-neutral-900 mb-1 font-outfit fs-6">${data}</div>
                            <div class="d-flex align-items-center text-xs text-primary fw-bold mb-2">
                                <i class="fas fa-building-circle-check me-2 opacity-50"></i>${row.client_name}
                            </div>
                            <div class="text-xs text-neutral-400 text-truncate font-medium" style="max-width: 320px;">${row.description || 'Project objective and core scope details pending.'}</div>
                        </div>
                    `;
                }
            },
            { 
                data: 'role_name',
                render: function(data) {
                    return `<span class="badge bg-neutral-50 text-neutral-600 border px-3 py-2 font-outfit fw-bold">${data}</span>`;
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
                    if(progress < 30 && total > 0) barClass = 'bg-danger';

                    return `
                        <div style="width: 200px;">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="text-xs fw-bold text-neutral-800 font-outfit">${progress}% Velocity</span>
                                <span class="text-xs text-neutral-400 fw-bold">${completed}/${total} Tasks</span>
                            </div>
                            <div class="progress rounded-pill overflow-visible" style="height: 6px; background: var(--neutral-100);">
                                <div class="progress-bar ${barClass} rounded-pill position-relative" role="progressbar" style="width: ${progress}%">
                                    <div class="progress-glow"></div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    let cls = 'bg-primary-soft';
                    if (data === 'active') cls = 'bg-warning-soft';
                    if (data === 'completed') cls = 'bg-success-soft';
                    if (data === 'cancelled') cls = 'bg-danger-soft';
                    return `<span class="badge ${cls} text-capitalize px-3 py-2">${data}</span>`;
                }
            },
            { 
                data: 'deadline',
                render: function(data) {
                    const d = moment(data);
                    const isOverdue = d.isBefore(moment()) && data !== 'completed';
                    return `
                        <div class="d-flex align-items-center gap-3">
                            <div class="timeline-dot ${isOverdue ? 'bg-danger' : 'bg-primary'}"></div>
                            <div class="text-xs">
                                <div class="text-neutral-400 fw-bold text-uppercase" style="font-size: 0.6rem;">Expiring On</div>
                                <div class="text-neutral-900 fw-bold">${moment(data).format('DD MMM, YYYY')}</div>
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
                            <button class="action-btn-sm" data-bs-toggle="dropdown" data-bs-display="static">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-deep border-0 rounded-4 p-2">
                                <li><a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="<?= url('/' . ($_SESSION['user_role'] == 'admin' ? 'admin' : 'staff') . '/tasks?project_id=') ?>${data.id}"><i class="fas fa-arrow-right-to-bracket text-primary"></i>Go to Task Board</a></li>
                                ${isAdmin ? `
                                    <li><hr class="dropdown-divider opacity-50"></li>
                                    <li><a class="dropdown-item rounded-3 py-2 edit-project" href="javascript:void(0)"><i class="fas fa-pen-to-square text-neutral-600"></i>Update Lifecycle</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2 text-danger delete-project" href="javascript:void(0)" data-id="${data.id}" data-name="${data.project_name}"><i class="fas fa-ban"></i>Terminate Project</a></li>
                                ` : ''}
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        order: [[4, 'desc']],
        dom: '<"d-flex justify-content-between align-items-center p-4"f<"d-flex gap-3"l>>t<"d-flex justify-content-between align-items-center p-4 border-top border-light"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search projects by name, client...",
            lengthMenu: "_MENU_ per page",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });

    $('.dataTables_filter input').addClass('form-control border-0 bg-neutral-50 rounded-pill px-4').attr('placeholder', 'Search projects...').css({'height': '45px'});

    $('#filter_role, #filter_status').on('change', () => table.ajax.reload());
    $('#resetFilters').on('click', () => { $('#filterForm')[0].reset(); table.ajax.reload(); });

    handleFormSubmit('#addProjectForm', () => { $('#addProjectModal').modal('hide'); $('#addProjectForm')[0].reset(); table.ajax.reload(); });
    handleFormSubmit('#editProjectForm', () => { $('#editProjectModal').modal('hide'); table.ajax.reload(); });

    $(document).on('click', '.edit-project', function() {
        const $tr = $(this).closest('.dropdown-menu').data('original-tr') || $(this).closest('tr');
        const data = table.row($tr).data();
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
        Swal.fire({
            title: 'Terminate Project?',
            text: "This action will archive the project and all associated tasks!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Yes, terminate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/api/projects/delete') ?>', { id: id }, (res) => {
                    if (res.status === 'success' || res.success) { 
                        toastr.success(res.message); 
                        table.ajax.reload(null, false); 
                    } else { 
                        toastr.error(res.message); 
                    }
                }).fail((xhr) => {
                    toastr.error(xhr.responseJSON?.message || 'Failed to terminate project');
                });
            }
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
.action-btn-sm:hover { background: var(--neutral-100); color: var(--primary-600); }

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

.font-outfit { font-family: 'Outfit', sans-serif; }
</style>
