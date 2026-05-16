<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <div class="row g-4">
            <!-- Profile Overview Card -->
            <div class="col-xl-4 col-lg-5">
                <div class="glass-card text-center p-5 sticky-top" style="top: 100px;">
                    <div class="position-relative d-inline-block mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=8b5cf6&color=fff&size=128" 
                             class="rounded-circle border border-4 border-white shadow-xl" width="120">
                        <span class="position-absolute bottom-0 end-0 bg-success border border-4 border-white rounded-circle p-2 shadow-sm"></span>
                    </div>
                    <h3 class="fw-bold text-neutral-900 mb-1"><?= $user['full_name'] ?></h3>
                    <p class="text-primary fw-semibold text-sm mb-4">@<?= $user['username'] ?></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge badge-soft-primary px-3 py-2 rounded-pill">
                            <i class="fas fa-shield-alt me-2"></i>System Administrator
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

            <!-- Admin Analytics & Security -->
            <div class="col-xl-8 col-lg-7">
                <!-- Analytics Section -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="glass-card p-4 text-center border-bottom border-primary border-4">
                            <div class="p-3 rounded-4 bg-primary-50 d-inline-block mb-3">
                                <i class="fas fa-layer-group text-primary fs-4"></i>
                            </div>
                            <h2 class="fw-bold text-neutral-900 mb-1"><?= $stats['total_projects'] ?></h2>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Total Projects</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 text-center border-bottom border-success border-4">
                            <div class="p-3 rounded-4 bg-success-soft d-inline-block mb-3">
                                <i class="fas fa-tasks text-success fs-4"></i>
                            </div>
                            <h2 class="fw-bold text-neutral-900 mb-1"><?= $stats['total_tasks'] ?></h2>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Active Tasks</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 text-center border-bottom border-info border-4">
                            <div class="p-3 rounded-4 bg-info-soft d-inline-block mb-3">
                                <i class="fas fa-users text-info fs-4"></i>
                            </div>
                            <h2 class="fw-bold text-neutral-900 mb-1"><?= $stats['total_staff'] ?></h2>
                            <p class="text-neutral-400 text-xs fw-bold text-uppercase mb-0">Team Members</p>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="glass-card overflow-hidden">
                    <div class="p-4 border-bottom border-light">
                        <h5 class="fw-bold text-neutral-900 mb-0">Security & Settings</h5>
                    </div>
                    <div class="p-4">
                        <form id="securityForm">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Username</label>
                                    <input type="text" class="form-control bg-neutral-50 border-0 py-2" value="<?= $user['username'] ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Role Access</label>
                                    <input type="text" class="form-control bg-neutral-50 border-0 py-2" value="Super Administrator" readonly>
                                </div>
                                <div class="col-12">
                                    <hr class="opacity-10">
                                    <h6 class="fw-bold text-neutral-900 mb-3">Change Password</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">New Password</label>
                                    <input type="password" class="form-control py-2" name="new_password" placeholder="Min. 8 characters">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Confirm Password</label>
                                    <input type="password" class="form-control py-2" name="confirm_password" placeholder="Confirm new password">
                                </div>
                                <div class="col-12 text-end pt-3">
                                    <button type="submit" class="btn btn-primary px-4 py-2 text-xs shadow-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    $('#securityForm').on('submit', function(e) {
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
                $('#securityForm')[0].reset();
            } else {
                toastr.error(res.message);
            }
        }, 'json').fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to update profile');
        });
    });
});
</script>
