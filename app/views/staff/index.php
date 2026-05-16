<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1">Team Directory</h3>
                <p class="text-neutral-500 mb-0">Manage workforce, departments & system access levels</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                <i class="fas fa-plus me-2"></i> Add Member
            </button>
        </div>

        <!-- Filters Section -->
        <div class="glass-card mb-5 p-4">
            <form id="filterForm" class="row g-4 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-3 ms-1">Department Filter</label>
                    <div class="input-group bg-neutral-50 rounded-pill">
                        <span class="input-group-text ps-3">
                            <i class="fas fa-briefcase text-neutral-300"></i>
                        </span>
                        <select class="form-select text-sm fw-bold h-100" name="filter_role" id="filter_role">
                            <option value="">All Departments</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="form-label text-xs fw-bold text-uppercase text-neutral-400 mb-3 ms-1">Status Type</label>
                    <div class="input-group bg-neutral-50 rounded-pill">
                        <span class="input-group-text ps-3">
                            <i class="fas fa-toggle-on text-neutral-300"></i>
                        </span>
                        <select class="form-select text-sm fw-bold h-100" name="filter_status" id="filter_status">
                            <option value="">All Active/Inactive</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <button type="button" id="resetFilters" class="btn btn-secondary border-0 bg-neutral-50 w-100 rounded-pill h-100 text-xs fw-bold text-neutral-600">
                        <i class="fas fa-filter-circle-xmark me-2"></i> Clear All Filters
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
                            <th>Join Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-4">
                <div>
                    <h4 class="fw-bold text-neutral-900 mb-1">New Member</h4>
                    <p class="text-xs text-neutral-400 mb-0">Complete the details to create a new account</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStaffForm" action="<?= url('/api/staff') ?>" method="POST">
                <div class="modal-body py-0">
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Full Name</label>
                        <input type="text" class="form-control rounded-4" name="full_name" placeholder="Enter full name" required>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Username</label>
                            <input type="text" class="form-control rounded-4" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Password</label>
                            <input type="password" class="form-control rounded-4" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Email Address</label>
                        <input type="email" class="form-control rounded-4" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="row g-4 mb-2">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Department Role</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="role_id" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Status</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-5 gap-3">
                    <button type="button" class="btn btn-secondary flex-grow-1 py-3" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-4">
                <div>
                    <h4 class="fw-bold text-neutral-900 mb-1">Update Member</h4>
                    <p class="text-xs text-neutral-400 mb-0">Modify team member profile and access level</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStaffForm" action="<?= url('/api/staff/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body py-0">
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Full Name</label>
                        <input type="text" class="form-control rounded-4" name="full_name" id="edit_full_name" required>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Username</label>
                            <input type="text" class="form-control rounded-4" name="username" id="edit_username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Update Password (Optional)</label>
                            <input type="password" class="form-control rounded-4" name="password" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Email Address</label>
                        <input type="email" class="form-control rounded-4" name="email" id="edit_email" required>
                    </div>
                    <div class="row g-4 mb-2">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Department Role</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="role_id" id="edit_role_id" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Status</label>
                            <select class="form-select border-0 bg-neutral-50 rounded-4 text-sm fw-bold" name="status" id="edit_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-5 gap-3">
                    <button type="button" class="btn btn-secondary flex-grow-1 py-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
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
                        <div class="d-flex align-items-center py-2">
                            <div class="avatar-group me-3">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.full_name)}&background=8b5cf6&color=fff" width="48" height="48" class="rounded-circle shadow-sm border border-2 border-white">
                                ${data.status === 'active' ? '<div class="status-indicator active"></div>' : '<div class="status-indicator inactive"></div>'}
                            </div>
                            <div>
                                <div class="fw-bold text-neutral-900 mb-0 font-outfit">${data.full_name}</div>
                                <div class="text-xs text-neutral-400">${data.email}</div>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'username',
                render: function(data) {
                    return `<span class="badge bg-neutral-50 text-neutral-600 border px-3 py-2">@${data}</span>`;
                }
            },
            { 
                data: 'role_name',
                render: function(data) {
                    return `<span class="fw-bold text-neutral-700 text-xs"><i class="fas fa-tag text-primary opacity-50 me-2"></i>${data}</span>`;
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    const cls = data === 'active' ? 'bg-success-soft' : 'bg-danger-soft';
                    return `<span class="badge ${cls} text-capitalize px-3 py-2">${data}</span>`;
                }
            },
            { 
                data: 'created_at',
                render: function(data) {
                    return `<span class="text-neutral-500 text-xs fw-bold">${moment(data).format('DD MMM, YYYY')}</span>`;
                }
            },
            {
                data: null,
                className: 'text-end pe-4',
                orderable: false,
                render: function(data) {
                    return `
                        <div class="dropdown">
                            <button class="action-btn-sm" data-bs-toggle="dropdown" data-bs-display="static">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-deep border-0 rounded-4 p-2">
                                <li><a class="dropdown-item rounded-3 py-2 edit-staff" href="javascript:void(0)"><i class="fas fa-user-pen me-2 text-primary"></i>Update Profile</a></li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li><a class="dropdown-item rounded-3 py-2 text-danger delete-staff" href="javascript:void(0)" data-id="${data.id}" data-name="${data.full_name}"><i class="fas fa-user-minus me-2"></i>Remove Access</a></li>
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
            searchPlaceholder: "Search team directory...",
            lengthMenu: "_MENU_ per page",
            info: "Showing _START_ to _END_ of _TOTAL_ members",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });

    // Custom Styling for DataTable search
    $('.dataTables_filter input').addClass('form-control border-0 bg-neutral-50 rounded-pill px-4').attr('placeholder', 'Search members...').css({'height': '45px'});

    $('#filter_role, #filter_status').on('change', function() { table.ajax.reload(); });
    $('#resetFilters').on('click', function() { $('#filterForm')[0].reset(); table.ajax.reload(); });

    handleFormSubmit('#addStaffForm', function() {
        $('#addStaffModal').modal('hide');
        $('#addStaffForm')[0].reset();
        table.ajax.reload();
    });

    handleFormSubmit('#editStaffForm', function() {
        $('#editStaffModal').modal('hide');
        table.ajax.reload();
    });

    $(document).on('click', '.edit-staff', function() {
        const $tr = $(this).closest('.dropdown-menu').data('original-tr') || $(this).closest('tr');
        const staff = table.row($tr).data();
        $('#edit_id').val(staff.id);
        $('#edit_full_name').val(staff.full_name);
        $('#edit_username').val(staff.username);
        $('#edit_email').val(staff.email);
        $('#edit_role_id').val(staff.role_id);
        $('#edit_status').val(staff.status);
        $('#editStaffModal').modal('show');
    });

    $(document).on('click', '.delete-staff', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This member's access will be revoked!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Yes, remove them!',
            borderRadius: '1.25rem'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/api/staff/delete') ?>', { id: id }, function(res) {
                    if (res.status === 'success' || res.success) {
                        toastr.success(res.message);
                        table.ajax.reload(null, false);
                    } else {
                        toastr.error(res.message);
                    }
                }).fail(function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to remove access');
                });
            }
        });
    });
});
</script>

<style>
.avatar-group { position: relative; }
.status-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}
.status-indicator.active { background: #10b981; box-shadow: 0 0 5px rgba(16, 185, 129, 0.5); }
.status-indicator.inactive { background: #94a3b8; }

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

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--neutral-100) !important;
    color: var(--primary-600) !important;
}

.font-outfit { font-family: 'Outfit', sans-serif; }
</style>
