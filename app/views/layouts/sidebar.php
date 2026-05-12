<aside class="sidebar" id="sidebar">
    <div class="sidebar-header p-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="logo-box me-2">
                <i class="fas fa-layer-group text-primary fs-4"></i>
            </div>
            <h4 class="fw-bold text-neutral-900 mb-0 logo-text">Taskly</h4>
        </div>
        <button class="btn btn-sm btn-light d-md-none" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-content px-3 flex-grow-1">
        <?php $prefix = (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') ? 'admin' : 'staff'; ?>
        <div class="sidebar-label px-3 mb-2">MAIN MENU</div>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item mb-1">
                <a href="<?= url("/$prefix/dashboard") ?>" class="nav-link <?= $active_page == 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-house me-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="<?= url("/$prefix/projects") ?>" class="nav-link <?= $active_page == 'projects' ? 'active' : '' ?>">
                    <i class="fas fa-folder me-3"></i>
                    <span>Projects</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="<?= url("/$prefix/tasks") ?>" class="nav-link <?= $active_page == 'tasks' ? 'active' : '' ?>">
                    <i class="fas fa-check-square me-3"></i>
                    <span>Tasks</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="<?= url("/$prefix/profile") ?>" class="nav-link <?= $active_page == 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user-circle me-3"></i>
                    <span>My Profile</span>
                </a>
            </li>
        </ul>

        <?php if ($prefix == 'admin'): ?>
        <div class="sidebar-label px-3 mt-4 mb-2">ADMINISTRATION</div>
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a href="<?= url('/admin/staff') ?>" class="nav-link <?= $active_page == 'staff' ? 'active' : '' ?>">
                    <i class="fas fa-users me-3"></i>
                    <span>Team Members</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="<?= url('/admin/kpi') ?>" class="nav-link <?= $active_page == 'kpi' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line me-3"></i>
                    <span>KPI Management</span>
                </a>
            </li>
        </ul>
        <?php endif; ?>
    </div>
    
    <div class="sidebar-footer p-3 mt-auto">
        <div class="user-info-card d-flex align-items-center p-3 rounded-4">
            <div class="position-relative">
                <img src="<?= isset($_SESSION['user_avatar']) ? $_SESSION['user_avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode(isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User') . '&background=8b5cf6&color=fff' ?>" alt="" width="40" height="40" class="rounded-circle shadow-sm">
                <span class="status-indicator"></span>
            </div>
            <div class="ms-3 overflow-hidden">
                <h6 class="user-name mb-0 text-truncate fw-bold"><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User' ?></h6>
                <p class="user-role mb-0 text-xs text-neutral-500 text-capitalize"><?= isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Member' ?></p>
            </div>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-link text-neutral-400 p-0" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-xl border-0 rounded-4">
                        <li><a class="dropdown-item py-2" href="<?= url("/$prefix/profile") ?>"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="<?= url('/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
.sidebar {
    background: #ffffff !important;
    border-right: 1px solid #f1f5f9;
}

.sidebar-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.05em;
}

.sidebar .nav-link {
    color: #64748b;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.sidebar .nav-link i {
    font-size: 1.1rem;
    color: #94a3b8;
    width: 24px;
    text-align: center;
}

.sidebar .nav-link:hover {
    background: #f8fafc;
    color: var(--primary-600);
}

.sidebar .nav-link:hover i {
    color: var(--primary-600);
}

.sidebar .nav-link.active {
    background: #f5f3ff !important;
    color: var(--primary-700) !important;
    box-shadow: none !important;
}

.sidebar .nav-link.active i {
    color: var(--primary-600) !important;
}

.logo-box {
    width: 36px;
    height: 36px;
    background: #f5f3ff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-info-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
}

.status-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    background: #22c55e;
    border: 2px solid #ffffff;
    border-radius: 50%;
}

.user-name {
    color: #1e293b;
    font-size: 0.9rem;
}
</style>
