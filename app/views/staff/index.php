<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h4 class="fw-bold mb-0 text-neutral-900">Team Members</h4>
                <p class="text-neutral-500 text-xs mb-0">Manage your workforce and roles.</p>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary rounded-pill px-3 py-2 text-xs" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                    <i class="fas fa-user-plus me-1"></i> Add Member
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="glass-card mb-3 p-3">
            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-2 ms-1">Department</label>
                    <select class="form-select border-0 bg-neutral-50 rounded-3 py-2 text-sm" name="filter_role" id="filter_role">
                        <option value="">All Departments</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-2 ms-1">Status</label>
                    <select class="form-select border-0 bg-neutral-50 rounded-3 py-2 text-sm" name="filter_status" id="filter_status">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12">
                    <button type="button" id="resetFilters" class="btn btn-light rounded-3 py-2 w-100 text-xs fw-bold text-neutral-600">
                        <i class="fas fa-filter me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Staff List Table -->
        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="staffTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Team Member</th>
                            <th>Username</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Joined On</th>
                            <th class="text-end pe-4">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0">Onboard Team Member</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStaffForm" action="<?= url('/api/staff') ?>" method="POST">
                <div class="modal-body py-4">
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Full Name</label>
                        <input type="text" class="form-control" name="full_name" placeholder="Enter your full name" required>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Email Address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email address" required>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Department Role</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="role_id" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Status</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-3">
                    <button type="button" class="btn btn-light flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-neutral-900 mb-0">Update Member Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStaffForm" action="<?= url('/api/staff/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body py-4">
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="edit_full_name" required>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Username</label>
                            <input type="text" class="form-control" name="username" id="edit_username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Update Password (Optional)</label>
                            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Email Address</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Department Role</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="role_id" id="edit_role_id" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Status</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" name="status" id="edit_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-3">
                    <button type="button" class="btn btn-light flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Apply Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with Premium styling
    const table = $('#staffTable').DataTable({
        ajax: {
            url: '<?= url('/api/staff') ?>',
            dataSrc: 'data',
            data: function(d) {
                d.role_id = $('#filter_role').val();
                d.status = $('#filter_status').val();
            }
        },
        columns: [
            { 
                data: null,
                render: function(data) {
                    return `
                        <div class="d-flex align-items-center ps-2 py-2">
                            <div class="position-relative">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.full_name)}&background=8b5cf6&color=fff" width="45" height="45" class="rounded-circle border border-2 border-white shadow-sm">
                                ${data.status === 'active' ? '<span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>' : ''}
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold text-neutral-900 mb-0">${data.full_name}</div>
                                <div class="text-xs text-neutral-400">${data.email}</div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'username',
                render: function(data) {
                    return `<code class="text-primary fw-semibold bg-primary-50 px-2 py-1 rounded text-xs">@${data}</code>`;
                }
            },
            { 
                data: 'role_name',
                render: function(data) {
                    return `<span class="fw-semibold text-neutral-700 text-sm"><i class="fas fa-briefcase text-neutral-300 me-2"></i>${data}</span>`;
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    const cls = data === 'active' ? 'badge-soft-success' : 'badge-soft-danger';
                    return `<span class="badge ${cls} text-capitalize px-3 py-2 rounded-pill">${data}</span>`;
                }
            },
            { 
                data: 'created_at',
                render: function(data) {
                    return `<span class="text-neutral-500 text-sm font-medium">${new Date(data).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</span>`;
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
                                <i class="fas fa-ellipsis-h text-neutral-500"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-xl border-0 rounded-4 p-2">
                                <li><a class="dropdown-item rounded-3 py-2 edit-staff" href="javascript:void(0)"><i class="fas fa-edit me-2 text-primary"></i>Update</a></li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li><a class="dropdown-item rounded-3 py-2 text-danger delete-staff" href="javascript:void(0)" data-id="${data.id}" data-name="${data.full_name}"><i class="fas fa-trash-alt me-2"></i>Archive Member</a></li>
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
            searchPlaceholder: "Search members",
            lengthMenu: "_MENU_ per page",
            info: "Showing _START_ to _END_ of _TOTAL_ members",
            paginate: {
                previous: '<i class="fas fa-chevron-left text-xs"></i>',
                next: '<i class="fas fa-chevron-right text-xs"></i>'
            }
        }
    });

    // Custom Search styling
    $('.dataTables_filter input').addClass('form-control border-0 bg-neutral-50 rounded-pill px-3 py-1').css({'min-width': '240px', 'font-size': '0.75rem'});

    // Filter Handling
    $('#filter_role, #filter_status').on('change', function() {
        table.ajax.reload();
    });

    $('#resetFilters').on('click', function() {
        $('#filterForm')[0].reset();
        table.ajax.reload();
    });

    // Form Submissions
    handleFormSubmit('#addStaffForm', function() {
        $('#addStaffModal').modal('hide');
        $('#addStaffForm')[0].reset();
        table.ajax.reload();
    });

    handleFormSubmit('#editStaffForm', function() {
        $('#editStaffModal').modal('hide');
        table.ajax.reload();
    });

    // Edit Button Handler
    $(document).on('click', '.edit-staff', function() {
        const staff = table.row($(this).closest('tr')).data();
        $('#edit_id').val(staff.id);
        $('#edit_full_name').val(staff.full_name);
        $('#edit_username').val(staff.username);
        $('#edit_email').val(staff.email);
        $('#edit_role_id').val(staff.role_id);
        $('#edit_status').val(staff.status);
        $('#editStaffModal').modal('show');
    });

    // Delete Button Handler
    $(document).on('click', '.delete-staff', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $.post('<?= url('/api/staff/delete') ?>', { id: id }, function(response) {
            if (response.success) {
                toastr.success(response.message);
                table.ajax.reload(null, false); // Reload without resetting pagination
            } else {
                toastr.error(response.message);
            }
        }, 'json');
    });
});
</script>

<style>
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--grad-primary) !important;
    color: white !important;
    border: none !important;
    border-radius: 10px !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--primary-100) !important;
    border: none !important;
    border-radius: 10px !important;
    color: var(--primary-700) !important;
}

.modal-content {
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(20px);
}
</style>
