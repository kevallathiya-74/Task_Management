<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-wrapper" id="logoToggle" style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-placement="right" title="Toggle Sidebar">
            <img src="<?= asset('image/logo.png') ?>" alt="Logo" width="28" height="28" class="logo-img">
            <i class="fas fa-columns sidebar-hover-icon"></i>
        </div>
        <button class="btn btn-sm btn-light d-md-none border-0" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-content flex-grow-1">
        <?php $prefix = (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') ? 'admin' : 'staff'; ?>
        
        <div class="sidebar-label">Workspace</div>
        <ul class="nav flex-column mb-3">
            <li class="nav-item">
                <a href="<?= url("/$prefix/dashboard") ?>" class="nav-link <?= $active_page == 'dashboard' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                    <div class="nav-icon-container">
                        <i class="fas fa-house"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url("/$prefix/projects") ?>" class="nav-link <?= $active_page == 'projects' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Projects">
                    <div class="nav-icon-container">
                        <i class="fas fa-folder-closed"></i>
                    </div>
                    <span>Projects</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url("/$prefix/tasks") ?>" class="nav-link <?= $active_page == 'tasks' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Tasks">
                    <div class="nav-icon-container">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <span>Tasks</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url("/$prefix/publishing") ?>" class="nav-link <?= $active_page == 'publishing' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Publishing Report">
                    <div class="nav-icon-container">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <span>Publishing Report</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url("/$prefix/todo") ?>" class="nav-link <?= $active_page == 'todo' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Todo List">
                    <div class="nav-icon-container">
                        <i class="fas fa-list"></i>
                    </div>
                    <span>Todo List</span>
                </a>
            </li>
        </ul>

        <?php if ($prefix == 'admin'): ?>
        <div class="sidebar-label">Management</div>
        <ul class="nav flex-column mb-3">
            <li class="nav-item">
                <a href="<?= url('/admin/staff') ?>" class="nav-link <?= $active_page == 'staff' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Team Members">
                    <div class="nav-icon-container">
                        <i class="fas fa-users"></i>
                    </div>
                    <span>Team Members</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('/admin/kpi') ?>" class="nav-link <?= $active_page == 'kpi' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="KPI Management">
                    <div class="nav-icon-container">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span>KPI Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('/admin/leaves') ?>" class="nav-link <?= $active_page == 'leaves_admin' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Leave Requests">
                    <div class="nav-icon-container">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <span>Leave Requests</span>
                </a>
            </li>
        </ul>
        <?php endif; ?>

        <div class="sidebar-label">Personal</div>
        <ul class="nav flex-column mb-3">
            <li class="nav-item">
                <a href="<?= url("/$prefix/profile") ?>" class="nav-link <?= $active_page == 'profile' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Account Settings">
                    <div class="nav-icon-container">
                        <i class="fas fa-circle-user"></i>
                    </div>
                    <span>Account Settings</span>
                </a>
            </li>
            <?php if ($prefix == 'staff'): ?>
            <li class="nav-item">
                <a href="<?= url("/$prefix/leaves") ?>" class="nav-link <?= $active_page == 'leaves' ? 'active' : '' ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="My Leaves">
                    <div class="nav-icon-container">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <span>My Leaves</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
    
    <div class="sidebar-footer">
        <div class="user-profile-card-combined">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'User') ?>&background=8b5cf6&color=fff" class="profile-avatar" width="32" height="32">
            <div class="brand-name-container">
                <h6 class="profile-name"><?= $_SESSION['user_name'] ?? 'User' ?></h6>
                <p class="profile-role"><?= $_SESSION['user_role'] ?? 'Member' ?></p>
            </div>
            <a href="<?= url('/logout') ?>" class="logout-icon-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</aside>
