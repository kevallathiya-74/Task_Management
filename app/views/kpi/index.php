<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- KPI Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1">KPI Management</h3>
                <p class="text-neutral-500 mb-0">Daily performance tracking and team analytic</p>
            </div>
        </div>

        <!-- Analytics Overview -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden border-start border-primary border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary-soft rounded-4 me-3">
                            <i class="fas fa-chart-line text-primary"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-neutral-900 mb-0 font-outfit"><?= number_format($analytics['team_avg'] ?? 0, 1) ?>%</h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0 ls-1">Team Average KPI</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden border-start border-success border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-success-soft rounded-4 me-3">
                            <i class="fas fa-award text-success"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-neutral-900 mb-0 text-truncate font-outfit"><?= $analytics['highest']['full_name'] ?? 'Establishing...' ?></h6>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0 ls-1">Top Performance (<?= number_format($analytics['highest']['avg_score'] ?? 0, 1) ?>%)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden border-start border-danger border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-danger-soft rounded-4 me-3">
                            <i class="fas fa-triangle-exclamation text-danger"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-neutral-900 mb-0 text-truncate font-outfit"><?= $analytics['lowest']['full_name'] ?? 'All Stable' ?></h6>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0 ls-1">Low Performer (<?= number_format($analytics['lowest']['avg_score'] ?? 0, 1) ?>%)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden border-start border-info border-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-info-soft rounded-4 me-3">
                            <i class="fas fa-microchip text-info"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-neutral-900 mb-0 font-outfit"><?= count($staff) ?></h3>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0 ls-1">Total Team Members</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- DAILY SCORING INTERFACE -->
            <div class="col-xl-5">
                <div class="glass-card p-5 h-100 position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-5">
                        <div>
                            <h5 class="fw-bold text-neutral-900 mb-1">Daily KPI Scoring</h5>
                        </div>
                        <div class="badge bg-primary-soft text-primary rounded-pill px-3 py-2 text-xs fw-bold border border-primary/10" id="kpi-status-badge">READY FOR INPUT</div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Select Member</label>
                            <div class="input-group bg-neutral-50 rounded-4">
                                <span class="input-group-text ps-3">
                                    <i class="fas fa-user-tie text-neutral-300"></i>
                                </span>
                                <select class="form-select text-sm fw-bold h-100" id="kpi-staff-select">
                                    <option value="">Choose Member Profile</option>
                                    <?php foreach ($staff as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?> (<?= $s['role_name'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Audit Date</label>
                            <div class="input-group bg-neutral-50 rounded-4">
                                <span class="input-group-text ps-3">
                                    <i class="fas fa-calendar-day text-neutral-300"></i>
                                </span>
                                <input type="date" class="form-control text-sm fw-bold h-100" id="kpi-date-select" max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                            </div>
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

                        <div class="mb-5">
                            <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase ms-1 mb-2">Audit Insight (Notes)</label>
                            <textarea class="form-control border-0 bg-neutral-50 rounded-4 p-4" id="kpi-notes" rows="2" placeholder="Record key observations or performance drivers..."></textarea>
                        </div>

                        <button class="btn btn-primary w-100 py-4 shadow-primary" id="btn-save-daily-kpi">
                            <i class="fas fa-cloud-arrow-up me-2"></i> Commit Daily Metrics
                        </button>
                    </div>

                    <div id="kpi-form-placeholder" class="text-center py-5">
                        <div class="icon-shape bg-primary-soft rounded-circle mb-4 mx-auto" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-pen text-primary fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-neutral-900 mb-2">Operational Context Required</h6>
                        <p class="text-xs text-neutral-400 px-5">Please select a team member and audit date to access the scoring console.</p>
                    </div>
                </div>
            </div>

            <!-- STAFF PERFORMANCE RANKING -->
            <div class="col-xl-7">
                <div class="glass-card h-100 overflow-hidden">
                    <div class="p-5 border-bottom border-light d-flex justify-content-between align-items-center bg-neutral-50/30">
                        <div>
                            <h5 class="fw-bold text-neutral-900 mb-1">Merit Rankings</h5>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-xs fw-bold text-neutral-400 text-uppercase">Period:</span>
                            <select class="form-select border-0 bg-neutral-100 rounded-pill text-xs fw-bold" id="rank-month-select" style="width: auto;">
                                <?php for ($m=1; $m<=12; $m++): ?>
                                    <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-5">Team Member</th>
                                    <th class="text-center">Avg KPI</th>
                                    <th class="text-center">Status</th>
                                    <th class="pe-5 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="performance-ranking-body">
                                <?php foreach ($staff as $s): ?>
                                <tr data-staff-id="<?= $s['id'] ?>">
                                    <td class="ps-5">
                                        <div class="d-flex align-items-center py-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($s['full_name']) ?>&background=8b5cf6&color=fff" class="rounded-circle shadow-sm border border-2 border-white me-3" width="42" height="42">
                                            <div>
                                                <div class="fw-bold text-neutral-800 font-outfit text-sm"><?= $s['full_name'] ?></div>
                                                <div class="text-xs text-neutral-400 fw-bold text-uppercase" style="font-size: 0.65rem;"><?= $s['role_name'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-bold text-neutral-900 font-outfit h6 mb-0" id="avg-kpi-<?= $s['id'] ?>">--</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-neutral-100 text-neutral-400 rounded-pill px-3 py-2 text-xs" id="status-badge-<?= $s['id'] ?>">Pending</span>
                                    </td>
                                    <td class="pe-5 text-end">
                                        <a href="<?= url('/admin/kpi/staff-report') ?>?id=<?= $s['id'] ?>" class="action-btn-sm bg-neutral-50 mx-auto">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
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

    $('.kpi-range-input').on('input', function() {
        const val = $(this).val();
        $(this).closest('.kpi-score-item').find('.score-display').text(parseFloat(val).toFixed(1)).toggleClass('text-primary', val > 0);
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
        if ((res.status === 'success' || res.success) && res.data) {
            const d = res.data;
            $('#kpi-status-badge').text('AMENDING RECORD').removeClass('bg-primary-soft text-primary').addClass('bg-warning-soft text-warning');
            
            setRangeValue('productivity', d.productivity_score / 30 * 10);
            setRangeValue('quality', d.quality_score / 25 * 10);
            setRangeValue('discipline', d.discipline_score / 15 * 10);
            setRangeValue('communication', d.communication_score / 15 * 10);
            setRangeValue('growth', d.growth_score / 15 * 10);
            
            $('#kpi-notes').val(d.admin_notes);
        } else {
            $('#kpi-status-badge').text('NEW ENTRY READY').removeClass('bg-warning-soft text-warning').addClass('bg-primary-soft text-primary');
            $('.kpi-range-input').val(0);
            $('.score-display').text('0.0').removeClass('text-primary');
            $('#kpi-notes').val('');
        }
        calculateDailyKpi();
    });
}

function setRangeValue(kpi, val) {
    const item = $(`.kpi-score-item[data-kpi="${kpi}"]`);
    item.find('.kpi-range-input').val(val);
    item.find('.score-display').text(val.toFixed(1)).addClass('text-primary');
}

function calculateDailyKpi() {
    let totalScore = 0;
    $('.kpi-score-item').each(function() {
        const weight = parseFloat($(this).data('weight'));
        const score = parseFloat($(this).find('.kpi-range-input').val()) || 0;
        const weightedResult = (score / 10) * weight;
        $(this).find('.result-cell').text(weightedResult.toFixed(1) + '%');
        totalScore += weightedResult;
    });
    $('#display-daily-total').text(totalScore.toFixed(1) + '%');
}

function saveDailyKpi() {
    const data = {
        user_id: $('#kpi-staff-select').val(),
        kpi_date: $('#kpi-date-select').val(),
        productivity_score: parseFloat($('.kpi-score-item[data-kpi="productivity"] .result-cell').text()),
        quality_score: parseFloat($('.kpi-score-item[data-kpi="quality"] .result-cell').text()),
        discipline_score: parseFloat($('.kpi-score-item[data-kpi="discipline"] .result-cell').text()),
        communication_score: parseFloat($('.kpi-score-item[data-kpi="communication"] .result-cell').text()),
        growth_score: parseFloat($('.kpi-score-item[data-kpi="growth"] .result-cell').text()),
        weighted_total_score: parseFloat($('#display-daily-total').text()),
        salary_approval_percentage: (parseFloat($('#display-daily-total').text()) * 0.75).toFixed(2),
        performance_status: getPerformanceStatus(parseFloat($('#display-daily-total').text())),
        admin_notes: $('#kpi-notes').val()
    };

    const btn = $('#btn-save-daily-kpi');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Encrypting & Saving...');

    $.post('<?= url('/api/admin/kpi/save-daily') ?>', data, function(res) {
        btn.prop('disabled', false).html('<i class="fas fa-cloud-arrow-up me-2"></i>Commit Daily Metrics');
        if (res.status === 'success' || res.success) {
            toastr.success(res.message);
            loadRankings();
        } else {
            toastr.error(res.message);
        }
    }).fail(function(xhr) {
        btn.prop('disabled', false).html('<i class="fas fa-cloud-arrow-up me-2"></i>Commit Daily Metrics');
        toastr.error(xhr.responseJSON?.message || 'Failed to save KPI');
    });
}

function getPerformanceStatus(score) {
    if (score >= 90) return 'Elite';
    if (score >= 75) return 'Proficient';
    if (score >= 60) return 'Functional';
    if (score >= 40) return 'Developing';
    return 'Critical';
}

function loadRankings() {
    const month = $('#rank-month-select').val();
    const year = new Date().getFullYear();

    $('#performance-ranking-body tr').each(function() {
        const staffId = $(this).data('staff-id');
        $.get('<?= url('/api/admin/kpi/monthly-report') ?>', { user_id: staffId, month: month, year: year }, function(res) {
            if ((res.status === 'success' || res.success) && res.stats.days_recorded > 0) {
                const avg = parseFloat(res.stats.avg_total).toFixed(1);
                $(`#avg-kpi-${staffId}`).text(avg + '%');
                
                const status = getPerformanceStatus(avg);
                const badge = $(`#status-badge-${staffId}`);
                badge.text(status).removeClass().addClass('badge rounded-pill px-3 py-2 text-xs font-outfit fw-bold');
                
                if (status === 'Elite') badge.addClass('bg-success-soft text-success border border-success/10');
                else if (status === 'Proficient') badge.addClass('bg-primary-soft text-primary border border-primary/10');
                else if (status === 'Functional') badge.addClass('bg-info-soft text-info border border-info/10');
                else if (status === 'Developing') badge.addClass('bg-warning-soft text-warning border border-warning/10');
                else badge.addClass('bg-danger-soft text-danger border border-danger/10');
            } else {
                $(`#avg-kpi-${staffId}`).text('--');
                $(`#status-badge-${staffId}`).text('No Audit Data').removeClass().addClass('badge bg-neutral-50 text-neutral-400 border rounded-pill px-3 py-2 text-xs font-outfit');
            }
        });
    });
}
</script>

<style>
.icon-shape { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.ls-1 { letter-spacing: 0.5px; }

.kpi-score-item { position: relative; }
.form-range::-webkit-slider-runnable-track { background: var(--neutral-100); height: 6px; border-radius: 10px; }
.form-range::-webkit-slider-thumb { margin-top: -6px; background: var(--primary-500); width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.2s ease; }
.form-range::-webkit-slider-thumb:active { transform: scale(1.2); }

.score-display { min-width: 32px; text-align: right; }

.action-btn-sm {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: none;
    color: var(--neutral-400);
    transition: all 0.3s ease;
}
.action-btn-sm:hover { background: var(--primary-50) !important; color: var(--primary-600); transform: translateX(3px); }

.font-outfit { font-family: 'Outfit', sans-serif; }
</style>
