<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1">KPI Management</h3>
                <p class="text-neutral-400 mb-0">Daily performance tracking and team analytics</p>
            </div>
        </div>

        <!-- Analytics Overview -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 border-start border-primary border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary-soft rounded-4 me-3">
                            <i class="fas fa-chart-line text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-neutral-900 mb-0"><?= number_format($analytics['team_avg'] ?? 0, 1) ?>%</h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Team Avg KPI</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 border-start border-success border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-success-soft rounded-4 me-3">
                            <i class="fas fa-trophy text-success fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-neutral-900 mb-0 text-truncate"><?= $analytics['highest']['full_name'] ?? 'N/A' ?></h6>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Top Performer (<?= number_format($analytics['highest']['avg_score'] ?? 0, 1) ?>%)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 border-start border-danger border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-danger-soft rounded-4 me-3">
                            <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-neutral-900 mb-0 text-truncate"><?= $analytics['lowest']['full_name'] ?? 'N/A' ?></h6>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Low Performer (<?= number_format($analytics['lowest']['avg_score'] ?? 0, 1) ?>%)</p>
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
                            <h3 class="fw-bold text-neutral-900 mb-0"><?= count($staff) ?></h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Total Staff</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- DAILY SCORING FORM -->
            <div class="col-xl-5 col-lg-6">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-neutral-900 mb-0">Daily KPI Scoring</h5>
                        <div class="badge bg-primary-soft text-primary rounded-pill px-3 py-1 text-xs fw-bold" id="kpi-status-badge">NEW ENTRY</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Team Member</label>
                            <select class="form-select border-0 bg-neutral-100 rounded-3 py-3" id="kpi-staff-select">
                                <option value="">Select Member</option>
                                <?php foreach ($staff as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?> (<?= $s['role_name'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Date</label>
                            <input type="date" class="form-control border-0 bg-neutral-100 rounded-3 py-3" id="kpi-date-select" max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div id="kpi-scoring-section" class="d-none">
                        <div class="table-responsive mb-4">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-xs text-neutral-400 fw-bold border-0">
                                        <th>CATEGORY</th>
                                        <th class="text-center">SCORE (0-10)</th>
                                        <th class="text-end">WEIGHTED</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-weight="30" data-kpi="productivity">
                                        <td>Productivity</td>
                                        <td><input type="number" min="0" max="10" class="form-control form-control-sm text-center kpi-input rounded-pill border-0 bg-neutral-100" value="0"></td>
                                        <td class="text-end fw-bold text-primary result-cell">0.0%</td>
                                    </tr>
                                    <tr data-weight="25" data-kpi="quality">
                                        <td>Quality</td>
                                        <td><input type="number" min="0" max="10" class="form-control form-control-sm text-center kpi-input rounded-pill border-0 bg-neutral-100" value="0"></td>
                                        <td class="text-end fw-bold text-primary result-cell">0.0%</td>
                                    </tr>
                                    <tr data-weight="15" data-kpi="discipline">
                                        <td>Discipline</td>
                                        <td><input type="number" min="0" max="10" class="form-control form-control-sm text-center kpi-input rounded-pill border-0 bg-neutral-100" value="0"></td>
                                        <td class="text-end fw-bold text-primary result-cell">0.0%</td>
                                    </tr>
                                    <tr data-weight="15" data-kpi="communication">
                                        <td>Communication</td>
                                        <td><input type="number" min="0" max="10" class="form-control form-control-sm text-center kpi-input rounded-pill border-0 bg-neutral-100" value="0"></td>
                                        <td class="text-end fw-bold text-primary result-cell">0.0%</td>
                                    </tr>
                                    <tr data-weight="15" data-kpi="growth">
                                        <td>Growth</td>
                                        <td><input type="number" min="0" max="10" class="form-control form-control-sm text-center kpi-input rounded-pill border-0 bg-neutral-100" value="0"></td>
                                        <td class="text-end fw-bold text-primary result-cell">0.0%</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="border-top border-light">
                                        <td colspan="2" class="pt-3 fw-bold text-neutral-800">DAILY TOTAL</td>
                                        <td class="pt-3 text-end fw-bold text-primary h4 mb-0" id="display-daily-total">0%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Daily Notes</label>
                            <textarea class="form-control border-0 bg-neutral-100 rounded-3" id="kpi-notes" rows="2" placeholder="Add observations..."></textarea>
                        </div>

                        <button class="btn btn-primary w-100 py-3 rounded-pill" id="btn-save-daily-kpi">
                            <i class="fas fa-save me-2"></i>Save Daily KPI
                        </button>
                    </div>
                    <div id="kpi-form-placeholder" class="text-center py-5">
                        <div class="icon-shape bg-primary-soft rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-edit text-primary fs-4"></i>
                        </div>
                        <h6 class="text-neutral-400">Select staff and date to enter scores</h6>
                    </div>
                </div>
            </div>

            <!-- STAFF PERFORMANCE TABLE -->
            <div class="col-xl-7 col-lg-6">
                <div class="glass-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-neutral-900 mb-0">Team Performance Ranking</h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm border-0 bg-neutral-100 rounded-pill px-3" id="rank-month-select">
                                <?php for ($m=1; $m<=12; $m++): ?>
                                    <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th class="ps-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase">Staff Member</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Avg KPI</th>
                                    <th class="border-0 text-xs fw-bold text-neutral-400 text-uppercase text-center">Status</th>
                                    <th class="pe-4 border-0 text-xs fw-bold text-neutral-400 text-uppercase text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="performance-ranking-body">
                                <?php foreach ($staff as $s): ?>
                                <tr data-staff-id="<?= $s['id'] ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($s['full_name']) ?>&background=random" class="rounded-circle me-3" width="36">
                                            <div>
                                                <div class="fw-bold text-neutral-800 text-sm"><?= $s['full_name'] ?></div>
                                                <div class="text-xs text-neutral-400"><?= $s['role_name'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-bold text-neutral-700" id="avg-kpi-<?= $s['id'] ?>">--</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-neutral-100 text-neutral-400 rounded-pill px-3 py-1 text-xs" id="status-badge-<?= $s['id'] ?>">Pending</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="<?= url('/admin/kpi/staff-report') ?>?id=<?= $s['id'] ?>" class="btn btn-xs btn-primary-soft rounded-pill px-3">Report</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    loadRankings();

    $('#kpi-staff-select, #kpi-date-select').on('change', function() {
        loadDailyKpi();
    });

    $('#rank-month-select').on('change', function() {
        loadRankings();
    });

    $('.kpi-input').on('input', function() {
        calculateDailyKpi();
    });

    $('#btn-save-daily-kpi').on('click', function() {
        saveDailyKpi();
    });
});

function loadDailyKpi() {
    const userId = $('#kpi-staff-select').val();
    const date = $('#kpi-date-select').val();

    if (!userId || !date) {
        $('#kpi-scoring-section').addClass('d-none');
        $('#kpi-form-placeholder').removeClass('d-none');
        return;
    }

    $('#kpi-scoring-section').removeClass('d-none');
    $('#kpi-form-placeholder').addClass('d-none');

    $.get('<?= url('/api/admin/kpi/daily-record') ?>', { user_id: userId, date: date }, function(res) {
        if (res.success && res.data) {
            const d = res.data;
            $('#kpi-status-badge').text('EDITING EXISTING').removeClass('bg-primary-soft text-primary').addClass('bg-warning-soft text-warning');
            $('tr[data-kpi="productivity"] .kpi-input').val(d.productivity_score / 30 * 10);
            $('tr[data-kpi="quality"] .kpi-input').val(d.quality_score / 25 * 10);
            $('tr[data-kpi="discipline"] .kpi-input').val(d.discipline_score / 15 * 10);
            $('tr[data-kpi="communication"] .kpi-input').val(d.communication_score / 15 * 10);
            $('tr[data-kpi="growth"] .kpi-input').val(d.growth_score / 15 * 10);
            $('#kpi-notes').val(d.admin_notes);
        } else {
            $('#kpi-status-badge').text('NEW ENTRY').removeClass('bg-warning-soft text-warning').addClass('bg-primary-soft text-primary');
            $('.kpi-input').val(0);
            $('#kpi-notes').val('');
        }
        calculateDailyKpi();
    });
}

function calculateDailyKpi() {
    let totalScore = 0;
    $('#kpi-scoring-section tbody tr').each(function() {
        const weight = parseFloat($(this).data('weight'));
        const score = parseFloat($(this).find('.kpi-input').val()) || 0;
        if (score > 10) $(this).find('.kpi-input').val(10);
        
        const weightedResult = (Math.min(score, 10) / 10) * weight;
        $(this).find('.result-cell').text(weightedResult.toFixed(1) + '%');
        totalScore += weightedResult;
    });
    $('#display-daily-total').text(totalScore.toFixed(1) + '%');
}

function saveDailyKpi() {
    const data = {
        user_id: $('#kpi-staff-select').val(),
        kpi_date: $('#kpi-date-select').val(),
        productivity_score: parseFloat($('tr[data-kpi="productivity"] .result-cell').text()),
        quality_score: parseFloat($('tr[data-kpi="quality"] .result-cell').text()),
        discipline_score: parseFloat($('tr[data-kpi="discipline"] .result-cell').text()),
        communication_score: parseFloat($('tr[data-kpi="communication"] .result-cell').text()),
        growth_score: parseFloat($('tr[data-kpi="growth"] .result-cell').text()),
        weighted_total_score: parseFloat($('#display-daily-total').text()),
        salary_approval_percentage: (parseFloat($('#display-daily-total').text()) * 0.75).toFixed(2),
        performance_status: getPerformanceStatus(parseFloat($('#display-daily-total').text())),
        admin_notes: $('#kpi-notes').val()
    };

    $('#btn-save-daily-kpi').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

    $.post('<?= url('/api/admin/kpi/save-daily') ?>', data, function(res) {
        $('#btn-save-daily-kpi').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save Daily KPI');
        if (res.success) {
            toastr.success(res.message);
            loadRankings();
        } else {
            toastr.error(res.message);
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

function loadRankings() {
    const month = $('#rank-month-select').val();
    const year = new Date().getFullYear();

    $('#performance-ranking-body tr').each(function() {
        const staffId = $(this).data('staff-id');
        $.get('<?= url('/api/admin/kpi/monthly-report') ?>', { user_id: staffId, month: month, year: year }, function(res) {
            if (res.success && res.stats.days_recorded > 0) {
                const avg = parseFloat(res.stats.avg_total).toFixed(1);
                $(`#avg-kpi-${staffId}`).text(avg + '%');
                
                const status = getPerformanceStatus(avg);
                const badge = $(`#status-badge-${staffId}`);
                badge.text(status).removeClass().addClass('badge rounded-pill px-3 py-1 text-xs');
                
                if (status === 'Excellent') badge.addClass('bg-success-soft text-success');
                else if (status === 'Good') badge.addClass('bg-primary-soft text-primary');
                else if (status === 'Average') badge.addClass('bg-info-soft text-info');
                else if (status === 'Needs Improvement') badge.addClass('bg-warning-soft text-warning');
                else badge.addClass('bg-danger-soft text-danger');
            } else {
                $(`#avg-kpi-${staffId}`).text('--');
                $(`#status-badge-${staffId}`).text('No Data').removeClass().addClass('badge bg-neutral-100 text-neutral-400 rounded-pill px-3 py-1 text-xs');
            }
        });
    });
}
</script>

<style>
.bg-primary-soft { background: rgba(139, 92, 246, 0.1); }
.bg-warning-soft { background: rgba(245, 158, 11, 0.1); }
.bg-success-soft { background: rgba(16, 185, 129, 0.1); }
.bg-danger-soft { background: rgba(239, 68, 68, 0.1); }
.bg-info-soft { background: rgba(6, 182, 212, 0.1); }

.icon-shape {
    display: flex;
    align-items: center;
    justify-content: center;
}
.kpi-input {
    width: 60px;
    margin: 0 auto;
}
</style>
