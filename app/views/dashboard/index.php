<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Statistics Grid -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 border-start border-primary border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary-soft rounded-4 me-3">
                            <i class="fas fa-project-diagram text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-neutral-900 mb-0"><?= $stats['total_projects'] ?></h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Total Projects</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 border-start border-warning border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-warning-soft rounded-4 me-3">
                            <i class="fas fa-tasks text-warning fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-neutral-900 mb-0"><?= $stats['active_tasks'] ?></h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Active Tasks</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 border-start border-success border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-success-soft rounded-4 me-3">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-neutral-900 mb-0"><?= $stats['completed_projects'] ?></h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 border-start border-info border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-info-soft rounded-4 me-3">
                            <i class="fas fa-users text-info fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-neutral-900 mb-0"><?= $stats['total_staff'] ?></h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Team Members</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- MIDDLE LEFT: Task Priority Card -->
            <div class="col-xl-4 col-lg-5">
                <div class="glass-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-neutral-900 mb-0">Task Priority</h5>
                        <i class="fas fa-fire-alt text-danger"></i>
                    </div>
                    <div class="p-4">
                        <div class="priority-item glass-card mb-3 p-3 cursor-pointer hover-scale transition-all border-start border-danger border-4" onclick="showPriorityTasks('high')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="bg-danger rounded-circle me-3" style="width: 10px; height: 10px;"></span>
                                    <span class="fw-bold text-neutral-800">High Priority</span>
                                </div>
                                <span class="badge bg-danger-soft text-danger rounded-pill px-3" id="high-count">0</span>
                            </div>
                        </div>
                        <div class="priority-item glass-card mb-3 p-3 cursor-pointer hover-scale transition-all border-start border-warning border-4" onclick="showPriorityTasks('medium')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="bg-warning rounded-circle me-3" style="width: 10px; height: 10px;"></span>
                                    <span class="fw-bold text-neutral-800">Medium Priority</span>
                                </div>
                                <span class="badge bg-warning-soft text-warning rounded-pill px-3" id="medium-count">0</span>
                            </div>
                        </div>
                        <div class="priority-item glass-card p-3 cursor-pointer hover-scale transition-all border-start border-primary border-4" onclick="showPriorityTasks('low')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="bg-primary rounded-circle me-3" style="width: 10px; height: 10px;"></span>
                                    <span class="fw-bold text-neutral-800">Low Priority</span>
                                </div>
                                <span class="badge bg-primary-soft text-primary rounded-pill px-3" id="low-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MIDDLE RIGHT: Recent Assignments / Analytics Summary -->
            <div class="col-xl-8 col-lg-7">
                <div class="glass-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-neutral-900 mb-0">Recent Assignments</h5>
                        <a href="<?= url('admin/tasks') ?>" class="btn btn-xs btn-primary-soft rounded-pill px-3">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th class="ps-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase">Task & Project</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase">Assignee</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase">Deadline</th>
                                    <th class="pe-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_tasks as $task): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-neutral-800 text-sm mb-0"><?= $task['title'] ?></div>
                                        <div class="text-xs text-neutral-400"><?= $task['project_name'] ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assigned_to_name']) ?>&background=random&size=32" class="rounded-circle me-2" width="28">
                                            <span class="text-xs fw-semibold text-neutral-700"><?= $task['assigned_to_name'] ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs text-neutral-600 fw-medium">
                                            <i class="far fa-clock me-1 text-neutral-400"></i>
                                            <?= date('d M', strtotime($task['due_date'])) ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <?php 
                                            $badgeClass = 'bg-primary-soft text-primary';
                                            if ($task['status'] == 'completed') $badgeClass = 'bg-success-soft text-success';
                                            if ($task['status'] == 'in_progress') $badgeClass = 'bg-warning-soft text-warning';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-1 text-xs text-capitalize">
                                            <?= str_replace('_', ' ', $task['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM: Growth Analysis Graph -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold text-neutral-900 mb-1">Growth Analysis</h5>
                            <p class="text-xs text-neutral-400 mb-0">Weekly task productivity trends</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="dropdown">
                                Last 7 Days <i class="fas fa-chevron-down ms-2 text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div style="height: 350px;">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Priority Tasks Modal -->
<div class="modal fade" id="priorityTasksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 overflow-hidden">
            <div class="modal-header border-bottom border-light px-4 py-3 bg-neutral-50/50">
                <h5 class="fw-bold text-neutral-900 mb-0 d-flex align-items-center">
                    <i class="fas fa-layer-group text-primary me-3"></i>
                    <span id="priority-modal-title">Priority Tasks</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="priority-tasks-list" class="max-h-[500px] overflow-y-auto p-4">
                    <!-- Tasks will be loaded here -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Alert Popup Container -->
<div id="admin-alert-container" class="position-fixed bottom-0 end-0 p-4" style="z-index: 9999;"></div>

<script>
$(document).ready(function() {
    loadChartData();
    pollAlerts();
    setInterval(pollAlerts, 10000); // Poll every 10 seconds
});

function loadChartData() {
    $.get('<?= url('/api/dashboard/charts') ?>', function(res) {
        // Update Priority Counts
        res.tasks.forEach(t => {
            $(`#${t.priority}-count`).text(t.count);
        });

        // Initialize Growth Chart
        const ctx = document.getElementById('growthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: res.growth.map(g => moment(g.date).format('DD MMM')),
                datasets: [{
                    label: 'Tasks Created',
                    data: res.growth.map(g => g.count),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8b5cf6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
}

function showPriorityTasks(priority) {
    $('#priority-modal-title').text(priority.toUpperCase() + ' Priority Tasks');
    $('#priorityTasksModal').modal('show');
    $('#priority-tasks-list').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');

    $.get('<?= url('/api/dashboard/priority-tasks') ?>', { priority: priority }, function(res) {
        if (res.success && res.data.length > 0) {
            let html = '';
            res.data.forEach(task => {
                html += `
                    <div class="glass-card mb-3 p-4 border-start border-4 ${priority === 'high' ? 'border-danger' : (priority === 'medium' ? 'border-warning' : 'border-primary')}">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="text-xs text-neutral-400 fw-bold text-uppercase mb-1">${task.project_name}</div>
                                <h6 class="fw-bold text-neutral-900 mb-2">${task.title}</h6>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(task.staff_name)}&background=random" class="rounded-circle me-2" width="24">
                                    <span class="text-xs fw-semibold text-neutral-600">${task.staff_name}</span>
                                    <span class="mx-2 text-neutral-300">|</span>
                                    <span class="text-xs text-neutral-500"><i class="far fa-calendar-alt me-1"></i>${moment(task.due_date).format('DD MMM')}</span>
                                </div>
                            </div>
                            <div class="col-md-5 text-end">
                                <div class="d-flex justify-content-end align-items-center gap-3 mt-3 mt-md-0">
                                    <div class="form-check custom-checkbox success">
                                        <input class="form-check-input" type="checkbox" ${task.is_completed ? 'checked' : ''} onchange="updateTaskStatus('${task.id}', 'complete', this.checked)">
                                        <label class="form-check-label text-xs fw-bold text-neutral-500">Complete</label>
                                    </div>
                                    <div class="form-check custom-checkbox danger">
                                        <input class="form-check-input" type="checkbox" ${task.is_incomplete ? 'checked' : ''} onchange="updateTaskStatus('${task.id}', 'incomplete', this.checked)">
                                        <label class="form-check-label text-xs fw-bold text-neutral-500">Incomplete</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#priority-tasks-list').html(html);
        } else {
            $('#priority-tasks-list').html('<div class="text-center py-5 text-neutral-400">No tasks found for this priority.</div>');
        }
    });
}

function updateTaskStatus(id, type, checked) {
    if (!checked) return; // Only trigger on check
    
    // Uncheck the other checkbox in the same row
    const row = $(event.target).closest('.row');
    row.find('input[type="checkbox"]').not(event.target).prop('checked', false);

    $.post('<?= url('/api/tasks/update-status') ?>', { id: id, type: type }, function(res) {
        if (res.success) {
            toastr.success(res.message);
            loadChartData(); // Refresh counts
            if (type === 'complete') {
                // If marked complete, uncheck incomplete if it was checked (UI logic)
            }
        } else {
            toastr.error(res.message);
        }
    });
}

function pollAlerts() {
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
    $.get('<?= url('/api/dashboard/alerts') ?>', function(res) {
        if (res.success && res.data.length > 0) {
            res.data.forEach(alert => {
                if ($(`#alert-${alert.id}`).length === 0) {
                    showAdminPopup(alert);
                }
            });
        }
    });
    <?php endif; ?>
}

function showAdminPopup(alert) {
    const html = `
        <div id="alert-${alert.id}" class="glass-card mb-3 p-4 animate-slide-in-right shadow-xl border-start border-danger border-4" style="min-width: 350px;">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="badge bg-danger-soft text-danger px-3 py-1 rounded-pill text-xs fw-bold">TASK INCOMPLETE ALERT</div>
                <button onclick="dismissAlert('${alert.id}')" class="btn-close text-xs"></button>
            </div>
            <p class="text-sm text-neutral-800 mb-3">
                Task <span class="fw-bold text-primary">"${alert.task_title}"</span> assigned to 
                <span class="fw-bold">${alert.staff_name}</span> is marked incomplete.
            </p>
            <div class="d-flex gap-2">
                <button onclick="reassignTask('${alert.task_id}', '${alert.id}')" class="btn btn-primary btn-sm rounded-pill px-3 text-xs flex-grow-1">Reassign Task</button>
                <button onclick="dismissAlert('${alert.id}')" class="btn btn-light btn-sm rounded-pill px-3 text-xs flex-grow-1">Dismiss</button>
            </div>
        </div>
    `;
    $('#admin-alert-container').prepend(html);
}

function dismissAlert(id) {
    $.post('<?= url('/api/dashboard/alerts/read') ?>', { id: id }, function(res) {
        if (res.success) {
            $(`#alert-${id}`).addClass('animate-fade-out');
            setTimeout(() => $(`#alert-${id}`).remove(), 300);
        }
    });
}

function reassignTask(taskId, alertId) {
    window.location.href = `<?= url('/tasks') ?>?edit=${taskId}`;
}
</script>

<style>
.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-primary-soft { background: rgba(139, 92, 246, 0.1); }
.bg-warning-soft { background: rgba(245, 158, 11, 0.1); }
.bg-success-soft { background: rgba(16, 185, 129, 0.1); }
.bg-info-soft { background: rgba(6, 182, 212, 0.1); }
.bg-danger-soft { background: rgba(239, 68, 68, 0.1); }

.hover-scale:hover {
    transform: scale(1.02);
}

.custom-checkbox.success .form-check-input:checked { background-color: #10b981; border-color: #10b981; }
.custom-checkbox.danger .form-check-input:checked { background-color: #ef4444; border-color: #ef4444; }

.animate-slide-in-right {
    animation: slideInRight 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
}

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.animate-fade-out {
    animation: fadeOut 0.3s forwards;
}

@keyframes fadeOut {
    from { opacity: 1; transform: scale(1); }
    to { opacity: 0; transform: scale(0.9); }
}

.max-h-\[500px\] {
    max-height: 500px;
}
</style>
