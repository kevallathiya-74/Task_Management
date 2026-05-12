<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Stats Grid -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary-100 rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                            <i class="fas fa-rocket text-primary fs-5"></i>
                        </div>
                        <div>
                            <p class="text-neutral-500 text-xs mb-0 fw-bold text-uppercase">Total Projects</p>
                            <h4 class="fw-bold mb-0"><?= $stats['total_projects'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-warning-soft rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                            <i class="fas fa-folder-open text-warning fs-5"></i>
                        </div>
                        <div>
                            <p class="text-neutral-500 text-xs mb-0 fw-bold text-uppercase">Active Projects</p>
                            <h4 class="fw-bold mb-0 text-warning"><?= $stats['active_projects'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-info-soft rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                            <i class="fas fa-users text-info fs-5"></i>
                        </div>
                        <div>
                            <p class="text-neutral-500 text-xs mb-0 fw-bold text-uppercase">Team Members</p>
                            <h4 class="fw-bold mb-0"><?= $stats['total_staff'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-success-soft rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                            <i class="fas fa-check-double text-success fs-5"></i>
                        </div>
                        <div>
                            <p class="text-neutral-500 text-xs mb-0 fw-bold text-uppercase">Completed</p>
                            <h4 class="fw-bold mb-0 text-success"><?= $stats['completed_projects'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Charts Section -->
            <div class="col-lg-8">
                <div class="glass-card h-100">
                    <div class="card-header bg-transparent border-0 p-3 pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-0">Growth Analysis</h6>
                            <p class="text-xs text-neutral-400 mb-0">Monthly performance tracking</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-pill px-3 py-1" style="font-size: 0.75rem;" data-bs-toggle="dropdown">
                                Last 30 Days <i class="fas fa-chevron-down ms-1 text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div style="height: 280px;">
                            <canvas id="projectChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card h-100">
                    <div class="card-header bg-transparent border-0 p-3 pb-0 text-center">
                        <h6 class="fw-bold mb-0">Task Priority</h6>
                        <p class="text-xs text-neutral-400 mb-0">Resources allocation</p>
                    </div>
                    <div class="card-body p-3">
                        <div style="height: 200px; position: relative;">
                            <canvas id="taskPriorityChart"></canvas>
                            <div class="chart-center-text position-absolute top-50 start-50 translate-middle text-center">
                                <h5 class="mb-0 fw-bold"><?= $stats['active_tasks'] ?></h5>
                                <span class="text-xs text-neutral-400">Total</span>
                            </div>
                        </div>
                        <div class="mt-3 px-1">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center text-xs">
                                    <span class="p-1 rounded-circle bg-danger me-2"></span> High Priority
                                </div>
                                <span class="fw-bold text-xs" id="high-priority-count">0</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center text-xs">
                                    <span class="p-1 rounded-circle bg-warning me-2"></span> Medium Priority
                                </div>
                                <span class="fw-bold text-xs" id="medium-priority-count">0</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center text-xs">
                                    <span class="p-1 rounded-circle bg-success me-2"></span> Low Priority
                                </div>
                                <span class="fw-bold text-xs" id="low-priority-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Activity -->
            <div class="col-12">
                <div class="glass-card">
                    <div class="card-header bg-transparent border-0 p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-0">Recent Assignments</h6>
                            <p class="text-xs text-neutral-400 mb-0">Latest tasks added</p>
                        </div>
                        <a href="<?= url('/tasks') ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Task</th>
                                        <th>Project</th>
                                        <th>Assigned To</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_tasks as $task): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-neutral-800" style="font-size: 0.85rem;"><?= $task['title'] ?></div>
                                            <div class="text-xs text-neutral-400">Added on <?= date('d M', strtotime($task['created_at'])) ?></div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-folder text-primary me-2" style="font-size: 0.7rem;"></i>
                                                <span class="text-xs text-neutral-600"><?= $task['project_name'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assigned_to_name']) ?>&background=random" width="24" height="24" class="rounded-circle me-2">
                                                <span class="text-xs"><?= $task['assigned_to_name'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                                $statusClass = 'badge-soft-primary';
                                                if($task['status'] == 'completed') $statusClass = 'badge-soft-success';
                                                if($task['status'] == 'in_progress') $statusClass = 'badge-soft-warning';
                                                if($task['status'] == 'pending') $statusClass = 'badge-soft-danger';
                                            ?>
                                            <span class="badge <?= $statusClass ?> rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                                                <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="fw-bold text-neutral-700 text-xs"><?= date('d M, Y', strtotime($task['due_date'])) ?></div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($recent_tasks)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-neutral-400 text-xs mb-0">No recent tasks found.</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#94a3b8';

    $.getJSON('<?= url('/api/dashboard/charts') ?>', function(data) {
        const projectCtx = document.getElementById('projectChart').getContext('2d');
        const gradient = projectCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.12)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(projectCtx, {
            type: 'line',
            data: {
                labels: data.projects.map(p => p.status.toUpperCase()),
                datasets: [{
                    label: 'Projects Status Distribution',
                    data: data.projects.map(p => p.count),
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#4f46e5',
                    borderWidth: 2.5,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1e293b',
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(226, 232, 240, 0.3)', drawBorder: false },
                        ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    }
                }
            }
        });

        const taskCtx = document.getElementById('taskPriorityChart').getContext('2d');
        const tasksByPriority = data.tasks;
        const counts = { 'high': 0, 'medium': 0, 'low': 0 };
        tasksByPriority.forEach(t => { if(counts.hasOwnProperty(t.priority)) counts[t.priority] = t.count; });

        $('#high-priority-count').text(counts.high);
        $('#medium-priority-count').text(counts.medium);
        $('#low-priority-count').text(counts.low);

        new Chart(taskCtx, {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [counts.high, counts.medium, counts.low],
                    backgroundColor: ['#f43f5e', '#f59e0b', '#10b981'],
                    hoverOffset: 12,
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                cutout: '85%'
            }
        });
    });
});
</script>

<style>
.icon-shape { box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
.bg-success-soft { background: #dcfce7; }
.bg-warning-soft { background: #fef9c3; }
.bg-info-soft { background: #e0f2fe; }
.table th { border-top: none !important; }
</style>
