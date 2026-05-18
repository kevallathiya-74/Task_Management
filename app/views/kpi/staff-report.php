<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/kpi') ?>" class="text-primary text-decoration-none">KPI Management</a></li>
                    <li class="breadcrumb-item active">Staff Report</li>
                </ol>
            </nav>
        </div>

        <div id="report-printable-area">
            <!-- Staff Header Card -->
            <div class="glass-card p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-auto">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&size=128&background=8b5cf6&color=fff" class="rounded-4 shadow-sm" width="100">
                    </div>
                    <div class="col-md">
                        <h3 class="fw-bold text-neutral-900 mb-1"><?= $user['full_name'] ?></h3>
                        <p class="text-neutral-500 mb-2"><?= $user['role_name'] ?> • Employee Performance Report</p>
                        <div class="d-flex gap-3">
                            <div class="d-flex align-items-center text-xs fw-bold text-neutral-400">
                                <i class="fas fa-envelope me-2"></i><?= $user['email'] ?>
                            </div>
                            <div class="d-flex align-items-center text-xs fw-bold text-neutral-400">
                                <i class="fas fa-calendar-check me-2"></i>Joined <?= date('M Y', strtotime($user['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto text-md-end mt-3 mt-md-0">
                        <div class="text-xs fw-bold text-neutral-400 text-uppercase mb-2">Report Filter</div>
                        <div class="d-flex gap-2 justify-content-md-end d-print-none">
                            <select class="form-select border-0 bg-neutral-100 rounded-pill" id="report-duration-select">
                                <option value="7days">Last 7 Days</option>
                                <option value="monthly" selected>Monthly (30 Days)</option>
                                <option value="3months">Last 3 Months</option>
                                <option value="6months">Last 6 Months</option>
                                <option value="12months">Last 12 Months</option>
                            </select>
                        </div>
                        <div class="d-none d-print-block fw-bold text-neutral-900" id="print-period-display"></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- KPI Analytics Summary -->
                <div class="col-xl-4 col-lg-5">
                    <div class="glass-card p-4 h-100">
                        <h5 class="fw-bold text-neutral-900 mb-4">Performance Summary</h5>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-xs fw-bold text-neutral-400">AVG KPI SCORE</span>
                                <span class="fw-bold text-primary" id="summary-avg-total">0%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-primary" id="summary-progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-3 rounded-4 border border-light text-center">
                                    <div class="text-xs text-neutral-400 fw-bold mb-1">SALARY APPROVE</div>
                                    <h4 class="fw-bold text-neutral-900 mb-0" id="summary-salary-app">0%</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 border border-light text-center">
                                    <div class="text-xs text-neutral-400 fw-bold mb-1">STATUS</div>
                                    <h5 class="fw-bold text-neutral-900 mb-0" id="summary-status">-</h5>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-neutral-900 mb-3">Performance Indicators</h6>
                        <div id="category-breakdown" class="space-y-3">
                            <!-- Categories injected by JS -->
                        </div>
                    </div>
                </div>

                <!-- Monthly Trend Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="glass-card p-4 h-100">
                        <h5 class="fw-bold text-neutral-900 mb-4">Daily Performance Trend</h5>
                        <div style="height: 300px;">
                            <canvas id="dailyTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily History Table -->
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-bottom border-light">
                    <h5 class="fw-bold text-neutral-900 mb-0">Detailed Daily Logs</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="ps-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase">Date</th>
                                <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Score</th>
                                <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Status</th>
                                <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase">Observations</th>
                                <th class="pe-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase text-end">Salary %</th>
                            </tr>
                        </thead>
                        <tbody id="daily-history-body">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let trendChart = null;

$(document).ready(function() {
    loadReportData();

    $('#report-duration-select').on('change', function() {
        loadReportData();
    });
});

function loadReportData() {
    const userId = '<?= $user['id'] ?>';
    const duration = $('#report-duration-select').val();
    const durationLabel = $('#report-duration-select option:selected').text();
    
    $('#print-period-display').text(`Period: ${durationLabel}`);

    $.get('<?= url('/api/admin/kpi/staff-report-data') ?>', { user_id: userId, duration: duration }, function(res) {
        if (res.status === 'success' || res.success) {
            updateSummary(res.stats);
            updateDailyHistory(res.history);
            updateTrendChart(res.history);
        }
    });
}

function updateSummary(stats) {
    const avg = parseFloat(stats.avg_total || 0).toFixed(1);
    const salary = parseFloat(stats.avg_salary || 0).toFixed(2);
    const status = getPerformanceStatus(avg);

    $('#summary-avg-total').text(avg + '%');
    $('#summary-progress-bar').css('width', avg + '%');
    $('#summary-salary-app').text(salary + '%');
    $('#summary-status').text(status);

    const categories = [
        { label: 'Productivity', val: stats.avg_productivity, weight: 30 },
        { label: 'Quality', val: stats.avg_quality, weight: 25 },
        { label: 'Discipline', val: stats.avg_discipline, weight: 15 },
        { label: 'Communication', val: stats.avg_communication, weight: 15 },
        { label: 'Growth', val: stats.avg_growth, weight: 15 }
    ];

    let catHtml = '';
    categories.forEach(cat => {
        const perc = (cat.val / (cat.weight / 10 * 10) * 10).toFixed(0); // Normalized to 100% of category max
        // Actually cat.val is already weighted result (e.g. 30 is max).
        // perc should be (cat.val / weight) * 100
        const categoryPerc = (cat.val / cat.weight * 100).toFixed(0);
        
        catHtml += `
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs fw-semibold text-neutral-600">${cat.label}</span>
                    <span class="text-xs fw-bold text-neutral-800">${categoryPerc}%</span>
                </div>
                <div class="progress rounded-pill" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: ${categoryPerc}%"></div>
                </div>
            </div>
        `;
    });
    $('#category-breakdown').html(catHtml);
}

function updateDailyHistory(history) {
    let html = '';
    if (history.length === 0) {
        html = '<tr><td colspan="5" class="text-center py-5 text-neutral-400">No data found for this period.</td></tr>';
    } else {
        history.forEach(log => {
            const status = getPerformanceStatus(log.weighted_total_score);
            html += `
                <tr>
                    <td class="ps-4 fw-bold text-neutral-700">${moment(log.kpi_date).format('DD MMM YYYY')}</td>
                    <td class="text-center fw-bold text-primary">${parseFloat(log.weighted_total_score).toFixed(1)}%</td>
                    <td class="text-center">
                        <span class="badge ${getStatusBadgeClass(status)} rounded-pill px-3 py-1 text-xs">${status}</span>
                    </td>
                    <td class="text-xs text-neutral-500 max-w-[200px] text-truncate" title="${log.admin_notes || ''}">
                        ${log.admin_notes || '<span class="text-neutral-300">No notes added</span>'}
                    </td>
                    <td class="pe-4 text-end fw-bold text-neutral-700">${parseFloat(log.salary_approval_percentage).toFixed(2)}%</td>
                </tr>
            `;
        });
    }
    $('#daily-history-body').html(html);
}

function updateTrendChart(history) {
    const ctx = document.getElementById('dailyTrendChart').getContext('2d');
    if (trendChart) trendChart.destroy();

    const data = [...history].reverse();

    trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => moment(d.kpi_date).format('DD MMM')),
            datasets: [{
                label: 'KPI Score %',
                data: data.map(d => d.weighted_total_score),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#8b5cf6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
}

function getPerformanceStatus(score) {
    if (score >= 90) return 'Excellent';
    if (score >= 75) return 'Good';
    if (score >= 60) return 'Average';
    if (score >= 40) return 'Needs Improvement';
    return 'Critical';
}

function getStatusBadgeClass(status) {
    if (status === 'Excellent') return 'bg-success-soft text-success';
    if (status === 'Good') return 'bg-primary-soft text-primary';
    if (status === 'Average') return 'bg-info-soft text-info';
    if (status === 'Needs Improvement') return 'bg-warning-soft text-warning';
    return 'bg-danger-soft text-danger';
}
</script>

<style>
@media print {
    .sidebar, .navbar, .d-print-none { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; }
    .glass-card { border: 1px solid #eee !important; box-shadow: none !important; background: white !important; break-inside: avoid; }
    body { background: white !important; }
}

.bg-primary-soft { background: rgba(139, 92, 246, 0.1); }
.bg-success-soft { background: rgba(16, 185, 129, 0.1); }
.bg-warning-soft { background: rgba(245, 158, 11, 0.1); }
.bg-danger-soft { background: rgba(239, 68, 68, 0.1); }
.bg-info-soft { background: rgba(6, 182, 212, 0.1); }
</style>
