<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>
<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-neutral-800">Todo List</h1>
                <p class="text-neutral-500 text-sm mb-0">Manage lightweight daily assignments</p>
            </div>
        </div>

        <!-- Quick Create Row (Admin Only) -->
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <div class="card glass-card mb-4 border-0">
            <div class="card-body p-2">
                <form id="createTodoForm" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase mb-2">Task</label>
                        <input type="text" class="form-control glass-input text-sm" name="title" placeholder="Enter todo task..." required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase mb-2">Assign Staff</label>
                        <select class="form-select glass-input text-sm" name="assigned_to" required>
                            <option value="" selected disabled>Select Staff...</option>
                            <?php foreach ($staff as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase mb-2">Task Type</label>
                        <select class="form-select glass-input text-sm" name="is_pinned">
                            <option value="0" selected>Normal Task</option>
                            <option value="1">Pin Task</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 btn-glow">
                            <i class="fas fa-plus me-2"></i>Add Todo
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Pinned Tasks Section -->
        <div id="pinnedTasksSection" class="mb-4 d-none">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-thumbtack text-primary me-2"></i>
                    <h6 class="text-xs fw-bold text-neutral-500 text-uppercase mb-0">Pinned Tasks</h6>
                </div>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <button id="resetPinnedTasks" class="btn btn-xs btn-light-subtle btn-glow">
                        <i class="fas fa-sync-alt me-1"></i>Reset
                    </button>
                <?php endif; ?>
            </div>
            <div class="row g-3" id="pinnedTasksContainer">
                <!-- Loaded via AJAX -->
            </div>
        </div>

        <!-- Normal Tasks Section -->
        <div class="d-flex align-items-center mb-3">
            <i class="fas fa-list text-primary me-2"></i>
            <h6 class="text-xs fw-bold text-neutral-500 text-uppercase mb-0">Normal Tasks</h6>
        </div>
        <!-- Todo Table Card -->
        <div class="card glass-card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="todoTable">
                        <thead class="table-light text-xs text-uppercase text-neutral-500">
                            <tr>
                                <th class="px-4 py-3">Task</th>
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <th class="py-3">Assigned To</th>
                                <?php endif; ?>
                                <th class="py-3">Status</th>
                                <th class="py-3">Created Date</th>
                                <th class="px-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="noTodosMessage" class="text-center py-5 d-none">
                    <div class="text-neutral-400 mb-2">
                        <i class="fas fa-clipboard-list fa-3x"></i>
                    </div>
                    <p class="text-neutral-500 mb-0">No todos assigned yet</p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Edit Todo Modal (Admin Only) -->
<?php if ($_SESSION['user_role'] === 'admin'): ?>
<div class="modal fade" id="editTodoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header border-bottom-0 p-4">
                <h5 class="modal-title fw-bold text-neutral-800">Edit Todo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTodoForm">
                <div class="modal-body p-4 pt-0">
                    <input type="hidden" name="id" id="edit_todo_id">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase mb-2">Task</label>
                        <input type="text" class="form-control glass-input text-sm" name="title" id="edit_todo_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase mb-2">Assign Staff</label>
                        <select class="form-select glass-input text-sm" name="assigned_to" id="edit_todo_assigned_to" required>
                            <?php foreach ($staff as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase mb-2">Task Type</label>
                        <select class="form-select glass-input text-sm" name="is_pinned" id="edit_todo_is_pinned">
                            <option value="0">Normal Task</option>
                            <option value="1">Pin Task</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-neutral-500 text-uppercase mb-2">Status</label>
                        <select class="form-select glass-input text-sm" name="status" id="edit_todo_status">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0 justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-glow">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- JS -->

<script>
$(document).ready(function() {
    loadTodos();

    function loadTodos() {
        $.ajax({
            url: '<?= url('/api/todos') ?>',
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const todos = response.data;
                    const tbody = $('#todoTable tbody');
                    const pinnedContainer = $('#pinnedTasksContainer');
                    
                    tbody.empty();
                    pinnedContainer.empty();

                    const pinnedTodos = todos.filter(t => t.is_pinned == 1);
                    const normalTodos = todos.filter(t => t.is_pinned != 1);

                    // Handle Pinned Tasks
                    if (pinnedTodos.length > 0) {
                        $('#pinnedTasksSection').removeClass('d-none');
                        
                        let itemsHtml = '';
                        pinnedTodos.forEach(function(todo) {
                            const date = new Date(todo.created_at);
                            const formattedDate = date.toLocaleDateString('en-GB') + ' ' + date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

                            let assignedToHtml = '';
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                assignedToHtml = `<div class="text-xs text-neutral-500">Assigned to: ${todo.assigned_to_name}</div>`;
                            <?php endif; ?>

                            let checkboxHtml = '';
                            <?php if ($_SESSION['user_role'] !== 'admin'): ?>
                                checkboxHtml = `
                                    <div class="form-check">
                                        <input class="form-check-input toggle-status" type="checkbox" style="width: 1.25rem; height: 1.25rem; cursor: pointer; border: 2px solid #000 !important;" data-id="${todo.id}" ${todo.status === 'completed' ? 'checked' : ''}>
                                    </div>
                                `;
                            <?php endif; ?>

                            itemsHtml += `
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3" style="background: transparent; border-color: rgba(0,0,0,0.05);">
                                    <div>
                                        <div class="fw-bold text-neutral-800 ${todo.status === 'completed' ? 'text-decoration-line-through text-neutral-400' : ''}">${todo.title}</div>
                                        ${assignedToHtml}
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-xs text-neutral-500 fw-bold">${formattedDate}</div>
                                        ${checkboxHtml}
                                    </div>
                                </li>
                            `;
                        });

                        const boxHtml = `
                            <div class="col-12">
                                <div class="card glass-card border-0 shadow-sm" style="border-left: 4px solid #8b5cf6 !important; background: rgba(255, 255, 255, 0.8);">
                                    <div class="card-body p-0">
                                        <ul class="list-group list-group-flush">
                                            ${itemsHtml}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        `;
                        pinnedContainer.html(boxHtml);
                    } else {
                        $('#pinnedTasksSection').addClass('d-none');
                    }

                    // Handle Normal Tasks
                    if (normalTodos.length === 0) {
                        $('#todoTable').addClass('d-none');
                        $('#noTodosMessage').removeClass('d-none');
                    } else {
                        $('#todoTable').removeClass('d-none');
                        $('#noTodosMessage').addClass('d-none');

                        normalTodos.forEach(function(todo) {
                            const statusBadge = todo.status === 'completed' 
                                ? '<span class="badge bg-success-subtle text-success">Completed</span>'
                                : '<span class="badge bg-warning-subtle text-warning">Pending</span>';
                            
                            let actions = '';
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                actions = `
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <button class="btn btn-sm btn-icon btn-light-subtle edit-todo" 
                                            data-id="${todo.id}" 
                                            data-title="${todo.title}" 
                                            data-assigned_to="${todo.assigned_to}" 
                                            data-status="${todo.status}"
                                            data-is_pinned="0">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-danger-subtle delete-todo" data-id="${todo.id}"><i class="fas fa-trash"></i></button>
                                    </div>
                                `;
                            <?php else: ?>
                                actions = `
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input toggle-status" type="checkbox" style="width: 1.25rem; height: 1.25rem; cursor: pointer; border: 2px solid #000 !important;" data-id="${todo.id}" ${todo.status === 'completed' ? 'checked' : ''}>
                                        </div>
                                    </div>
                                `;
                            <?php endif; ?>

                            const row = `
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-neutral-800">${todo.title}</div>
                                    </td>
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <td>${todo.assigned_to_name}</td>
                                    <?php endif; ?>
                                    <td>${statusBadge}</td>
                                    <td>${new Date(todo.created_at).toLocaleDateString('en-GB')} ${new Date(todo.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}</td>
                                    <td class="px-4 text-end">${actions}</td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    }
                }
            }
        });
    }

    // Create Todo
    $('#createTodoForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= url('/api/todos/create') ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#createTodoForm')[0].reset();
                    loadTodos();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Toggle Status
    $(document).on('change', '.toggle-status', function() {
        const id = $(this).data('id');
        const status = this.checked ? 'completed' : 'pending';
        $.ajax({
            url: '<?= url('/api/todos/update') ?>',
            type: 'POST',
            data: { id: id, status: status },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success('Todo status updated');
                    loadTodos();
                } else {
                    toastr.error(response.message);
                    this.checked = !this.checked;
                }
            }.bind(this)
        });
    });

    // Reset Pinned Tasks
    $(document).on('click', '#resetPinnedTasks', function() {
        if (confirm('Are you sure you want to reset all pinned tasks?')) {
            $.ajax({
                url: '<?= url('/api/todos/reset_pinned') ?>',
                type: 'POST',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        loadTodos();
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }
    });

    // Delete Todo
    $(document).on('click', '.delete-todo', function() {
        if (confirm('Are you sure you want to delete this todo?')) {
            const id = $(this).data('id');
            $.ajax({
                url: '<?= url('/api/todos/delete') ?>',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        loadTodos();
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }
    });

    // Edit Todo (Admin Only)
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
    $(document).on('click', '.edit-todo', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const assigned_to = $(this).data('assigned_to');
        const status = $(this).data('status');
        const is_pinned = $(this).data('is_pinned');

        $('#edit_todo_id').val(id);
        $('#edit_todo_title').val(title);
        $('#edit_todo_assigned_to').val(assigned_to);
        $('#edit_todo_status').val(status);
        $('#edit_todo_is_pinned').val(is_pinned);

        $('#editTodoModal').modal('show');
    });

    $('#editTodoForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= url('/api/todos/update') ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#editTodoModal').modal('hide');
                    loadTodos();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
    <?php endif; ?>
});
</script>
