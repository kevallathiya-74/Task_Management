<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="row g-4">
            <!-- Profile Overview Card -->
            <div class="col-xl-4 col-lg-5">
                <div class="glass-card text-center p-5 sticky-top" style="top: 100px;">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=8b5cf6&color=fff&size=128" 
                            class="rounded-circle border border-4 border-white shadow-xl" width="120">
                    <h3 class="fw-bold text-neutral-900 mb-1"><?= $user['full_name'] ?></h3>
                    <p class="text-primary fw-semibold text-sm mb-4">@<?= $user['username'] ?></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge badge-soft-primary px-3 py-2 rounded-pill">
                            <i class="fas fa-id-badge me-2"></i>Staff Member
                        </span>
                    </div>

                    <hr class="opacity-10 my-4">

                    <div class="row text-start g-4">
                        <div class="col-12">
                            <label class="text-xs text-neutral-400 text-uppercase fw-bold mb-1 d-block">Email Address</label>
                            <p class="text-neutral-700 text-sm mb-0"><?= $user['email'] ?></p>
                        </div>
                       
                    </div>
                </div>
            </div>

            <!-- Detailed Analytics & Projects -->
            <div class="col-xl-8 col-lg-7">
                <!-- Floating Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="glass-card p-4 d-flex align-items-center">
                            <div class="p-3 rounded-4 bg-success-soft me-4">
                                <i class="fas fa-check-double text-success fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-neutral-900 mb-0"><?= $staffStats['completed_tasks'] ?></h3>
                                <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Tasks Completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card p-4 d-flex align-items-center">
                            <div class="p-3 rounded-4 bg-primary-50 me-4">
                                <i class="fas fa-folder-open text-primary fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-neutral-900 mb-0"><?= count($staffStats['active_projects']) ?></h3>
                                <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Active Projects</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Projects Grid -->
                <div class="glass-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-neutral-900 mb-0">Ongoing Projects</h5>
                        <a href="<?= url('/projects') ?>" class="text-xs text-primary fw-bold text-decoration-none">View All</a>
                    </div>
                    <div class="row g-3">
                        <?php if (empty($staffStats['active_projects'])): ?>
                            <div class="col-12 text-center py-4">
                                <p class="text-neutral-400 text-sm mb-0">No active project assignments.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($staffStats['active_projects'] as $p): ?>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 border border-light bg-neutral-50 hover-shadow transition-all">
                                        <div class="fw-bold text-neutral-900 text-sm mb-1"><?= $p['project_name'] ?></div>
                                        <div class="text-xs text-neutral-400 mb-2"><?= $p['client_name'] ?></div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-soft-warning text-xs px-2 py-1"><?= $p['status'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold text-neutral-900 mb-4">Recent Activity</h5>
                    <div class="timeline-ui">
                        <?php if (empty($staffStats['recent_activity'])): ?>
                            <p class="text-neutral-400 text-sm mb-0">No recent task updates.</p>
                        <?php else: ?>
                            <?php foreach ($staffStats['recent_activity'] as $activity): ?>
                                <div class="timeline-item d-flex pb-4">
                                    <div class="timeline-marker position-relative pe-4">
                                        <div class="bg-primary rounded-circle shadow-primary" style="width: 12px; height: 12px;"></div>
                                        <div class="timeline-line bg-neutral-100 position-absolute start-50 top-0 h-100" style="width: 2px; margin-left: -1px; z-index: -1;"></div>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="text-xs text-neutral-400 fw-bold text-uppercase mb-1">
                                            <?= date('d M, Y \a\t H:i', strtotime($activity['updated_at'])) ?>
                                        </div>
                                        <p class="text-sm text-neutral-800 mb-0">
                                            Updated task <span class="fw-bold text-primary">"<?= $activity['title'] ?>"</span> 
                                            in <span class="fw-semibold"><?= $activity['project_name'] ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="glass-card p-4">
                    <h5 class="fw-bold text-neutral-900 mb-4">Account Security</h5>
                    <form id="staffSecurityForm">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">New Password</label>
                                <input type="password" class="form-control py-2" name="new_password" placeholder="At least 8 characters">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Confirm New Password</label>
                                <input type="password" class="form-control py-2" name="confirm_password" placeholder="Confirm your password">
                            </div>
                            <div class="col-12 text-end pt-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 text-xs shadow-primary">
                                    <i class="fas fa-lock me-2"></i>Secure Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    $('#staffSecurityForm').on('submit', function(e) {
        e.preventDefault();
        const pass = $('[name="new_password"]').val();
        const confirm = $('[name="confirm_password"]').val();

        if (pass && pass.length < 8) {
            toastr.error('Password must be at least 8 characters');
            return;
        }

        if (pass !== confirm) {
            toastr.error('Passwords do not match');
            return;
        }

        $.post('<?= url('/api/profile/update') ?>', $(this).serialize(), function(res) {
            if (res.status === 'success' || res.success) {
                toastr.success(res.message);
                $('#staffSecurityForm')[0].reset();
            } else {
                toastr.error(res.message);
            }
        }, 'json').fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to update profile');
        });
    });
});
</script>
