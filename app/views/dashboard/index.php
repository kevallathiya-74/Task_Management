<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1">Project Dashboard</h3>
                <p class="text-neutral-500 mb-0">Real-time overview of your workspace performance</p>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-primary-soft rounded-3">
                            <i class="fas fa-layer-group text-primary"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-neutral-900 mb-1"><?= $stats['total_projects'] ?></h2>
                    <p class="text-neutral-500 text-xs fw-bold text-uppercase mb-0 ls-1">Total Projects</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-warning-soft rounded-3">
                            <i class="fas fa-list-check text-warning"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-neutral-900 mb-1"><?= $stats['active_tasks'] ?></h2>
                    <p class="text-neutral-500 text-xs fw-bold text-uppercase mb-0 ls-1">Active Tasks</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-success-soft rounded-3">
                            <i class="fas fa-circle-check text-success"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-neutral-900 mb-1"><?= $stats['completed_projects'] ?></h2>
                    <p class="text-neutral-500 text-xs fw-bold text-uppercase mb-0 ls-1">Finished Projects</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-info-soft rounded-3">
                            <i class="fas fa-users text-info"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-neutral-900 mb-1"><?= $stats['total_staff'] ?></h2>
                    <p class="text-neutral-500 text-xs fw-bold text-uppercase mb-0 ls-1">Team Strength</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Task Priority -->
            <div class="col-xl-4">
                <div class="glass-card h-100">
                    <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center bg-neutral-50/30">
                        <h6 class="fw-bold text-neutral-900 mb-0">Task Distribution</h6>
                        <span class="text-xs text-neutral-400">By Priority</span>
                    </div>
                    <div class="p-4">
                        <div class="priority-card mb-3 p-3 rounded-4 cursor-pointer transition-all border border-light bg-white" onclick="showPriorityTasks('high')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dot bg-danger shadow-danger"></div>
                                    <span class="fw-bold text-neutral-800 text-sm">High Priority</span>
                                </div>
                                <span class="badge bg-danger-soft text-danger px-3" id="high-count">0</span>
                            </div>
                        </div>
                        <div class="priority-card mb-3 p-3 rounded-4 cursor-pointer transition-all border border-light bg-white" onclick="showPriorityTasks('medium')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dot bg-warning shadow-warning"></div>
                                    <span class="fw-bold text-neutral-800 text-sm">Medium Priority</span>
                                </div>
                                <span class="badge bg-warning-soft text-warning px-3" id="medium-count">0</span>
                            </div>
                        </div>
                        <div class="priority-card p-3 rounded-4 cursor-pointer transition-all border border-light bg-white" onclick="showPriorityTasks('low')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dot bg-primary shadow-primary"></div>
                                    <span class="fw-bold text-neutral-800 text-sm">Low Priority</span>
                                </div>
                                <span class="badge bg-primary-soft text-primary px-3" id="low-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="col-xl-8">
                <div class="glass-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center bg-neutral-50/30">
                        <h6 class="fw-bold text-neutral-900 mb-0">Recent Assignments</h6>
                        <a href="<?= url('admin/tasks') ?>" class="text-primary text-xs fw-bold text-decoration-none">View All Tasks <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Task Details</th>
                                    <th>Assigned To</th>
                                    <th>Deadline</th>
                                    <th class="pe-4 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_tasks as $task): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-neutral-800 mb-0"><?= $task['title'] ?></div>
                                        <div class="text-xs text-neutral-400"><?= $task['project_name'] ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assigned_to_name']) ?>&background=8b5cf6&color=fff" class="rounded-circle" width="28">
                                            <span class="text-xs fw-bold text-neutral-700"><?= $task['assigned_to_name'] ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs text-neutral-600 fw-bold">
                                            <i class="far fa-calendar text-neutral-300 me-2"></i><?= date('d M, Y', strtotime($task['due_date'])) ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <?php 
                                            $badgeClass = 'bg-primary-soft';
                                            if ($task['status'] == 'completed') $badgeClass = 'bg-success-soft';
                                            if ($task['status'] == 'in_progress') $badgeClass = 'bg-warning-soft';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> px-3"><?= str_replace('_', ' ', $task['status']) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth Graph -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="glass-card p-5">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h5 class="fw-bold text-neutral-900 mb-1">Growth Analysis</h5>
                            <p class="text-xs text-neutral-400 mb-0">Visualizing team productivity and task completion velocity</p>
                        </div>
                        <select class="form-select border-0 bg-neutral-50 rounded-pill" style="width: auto; height: 38px; min-height: 38px;">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                        </select>
                    </div>
                    <div style="height: 380px;">
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
        <div class="modal-content glass-card border-0 p-2">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-bold text-neutral-900 mb-0" id="priority-modal-title">Tasks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-4">
                <div id="priority-tasks-list">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="admin-alert-container" class="position-fixed bottom-0 end-0 p-4" style="z-index: 9999;"></div>

<script>
$(document).ready(function() {
    loadChartData();
    pollAlerts();
    setInterval(pollAlerts, 10000);
});

function loadChartData() {
    $.get('<?= url('/api/dashboard/charts') ?>', function(res) {
        if (res.status === 'success' || res.success) {
            res.tasks.forEach(t => {
                $(`#${t.priority}-count`).text(t.count);
            });

            const ctx = document.getElementById('growthChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(139, 92, 246, 0.15)');
            gradient.addColorStop(1, 'rgba(139, 92, 246, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: res.growth.map(g => moment(g.date).format('ddd, DD')),
                    datasets: [{
                        label: 'Tasks Created',
                        data: res.growth.map(g => g.count),
                        borderColor: '#8b5cf6',
                        backgroundColor: gradient,
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#8b5cf6',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#8b5cf6',
                        pointHoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                            ticks: { color: '#94a3b8', font: { weight: '600' } }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { weight: '600' } }
                        }
                    }
                }
            });
        }
    });
}

function showPriorityTasks(priority) {
    $('#priority-modal-title').text(priority.charAt(0).toUpperCase() + priority.slice(1) + ' Priority Tasks');
    $('#priorityTasksModal').modal('show');
    $('#priority-tasks-list').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');

    $.get('<?= url('/api/dashboard/priority-tasks') ?>', { priority: priority }, function(res) {
        if ((res.status === 'success' || res.success) && res.data.length > 0) {
            let html = '';
            res.data.forEach(task => {
                const borderClass = priority === 'high' ? 'border-danger' : (priority === 'medium' ? 'border-warning' : 'border-primary');
                html += `
                    <div class="p-4 rounded-4 border border-light bg-neutral-50/50 mb-3 transition-all hover-translate-y">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="text-xs text-primary fw-bold text-uppercase mb-1">${task.project_name}</div>
                                <h6 class="fw-bold text-neutral-900 mb-3">${task.title}</h6>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(task.staff_name)}&background=8b5cf6&color=fff" class="rounded-circle" width="24">
                                        <span class="text-xs fw-bold text-neutral-600">${task.staff_name}</span>
                                    </div>
                                    <span class="text-xs text-neutral-400 fw-bold"><i class="far fa-calendar-alt me-1 text-neutral-300"></i>${moment(task.due_date).format('DD MMM, YYYY')}</span>
                                </div>
                            </div>
                            <div class="col-md-5 text-end">
                                <div class="d-flex justify-content-end align-items-center gap-4 mt-3 mt-md-0">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" ${task.is_completed ? 'checked' : ''} onchange="updateTaskStatus('${task.id}', 'complete', this.checked)">
                                        <label class="form-check-label text-xs fw-bold text-neutral-500">Complete</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
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
            $('#priority-tasks-list').html('<div class="text-center py-5 text-neutral-400 fw-medium">No tasks found for this priority.</div>');
        }
    }).fail(() => {
        $('#priority-tasks-list').html('<div class="text-center py-5 text-danger fw-medium">Failed to load tasks.</div>');
    });
}

function updateTaskStatus(id, type, checked) {
    if (!checked) return;
    const row = $(event.target).closest('.row');
    row.find('input[type="checkbox"]').not(event.target).prop('checked', false);

    $.post('<?= url('/api/tasks/update-status') ?>', { id: id, type: type }, function(res) {
        if (res.status === 'success' || res.success) {
            toastr.success(res.message);
            loadChartData();
        } else {
            toastr.error(res.message);
        }
    }).fail((xhr) => {
        toastr.error(xhr.responseJSON?.message || 'Update failed');
    });
}

function pollAlerts() {
    <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
    $.get('<?= url('/api/dashboard/alerts') ?>', function(res) {
        if ((res.status === 'success' || res.success) && res.data && res.data.length > 0) {
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
        <div id="alert-${alert.id}" class="glass-card mb-3 p-4 animate-slide-in-right shadow-deep border-start border-danger border-4" style="min-width: 380px;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="badge bg-danger-soft text-danger px-3 py-1 rounded-pill text-xs fw-bold">CRITICAL TASK ALERT</div>
                <button onclick="dismissAlert('${alert.id}')" class="btn-close text-xs"></button>
            </div>
            <p class="text-sm text-neutral-800 mb-4 leading-relaxed">
                Task <span class="fw-bold text-primary">"${alert.task_title}"</span> assigned to 
                <span class="fw-bold">${alert.staff_name}</span> has been marked as <span class="text-danger fw-bold">incomplete</span>.
            </p>
            <div class="d-flex gap-3">
                <button onclick="reassignTask('${alert.task_id}', '${alert.id}')" class="btn btn-primary py-2 text-xs flex-grow-1">Reassign Now</button>
                <button onclick="dismissAlert('${alert.id}')" class="btn btn-secondary py-2 text-xs flex-grow-1">Dismiss</button>
            </div>
        </div>
    `;
    $('#admin-alert-container').prepend(html);
}

function dismissAlert(id) {
    $.post('<?= url('/api/dashboard/alerts/read') ?>', { id: id }, function(res) {
        if (res.status === 'success' || res.success) {
            $(`#alert-${id}`).addClass('animate-fade-out');
            setTimeout(() => $(`#alert-${id}`).remove(), 300);
        }
    });
}

function reassignTask(taskId, alertId) {
    window.location.href = `<?= url('/admin/tasks') ?>?edit=${taskId}`;
}
</script>

<style>
.icon-shape {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.dot { width: 10px; height: 10px; border-radius: 50%; }
.shadow-danger { box-shadow: 0 0 10px rgba(244, 63, 94, 0.4); }
.shadow-warning { box-shadow: 0 0 10px rgba(245, 158, 11, 0.4); }
.shadow-primary { box-shadow: 0 0 10px rgba(139, 92, 246, 0.4); }

.ls-1 { letter-spacing: 1px; }

.priority-card:hover {
    border-color: var(--primary-300) !important;
    background: var(--primary-50) !important;
    transform: translateX(5px);
}

.hover-translate-y:hover {
    transform: translateY(-3px);
    border-color: var(--primary-200) !important;
}

.custom-checkbox .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 6px;
    cursor: pointer;
}

.custom-checkbox .form-check-input:checked {
    background-color: var(--primary-500);
    border-color: var(--primary-500);
}

.animate-slide-in-right {
    animation: slideInRight 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

@keyframes slideInRight {
    from { transform: translateX(50px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.leading-relaxed { line-height: 1.6; }
</style>

