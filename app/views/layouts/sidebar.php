<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-wrapper">
            <img src="<?= asset('image/logo.png') ?>" alt="Logo" width="32" height="32">
            <h4 class="mb-0 brand-text" style="letter-spacing: -0.5px; font-size: 1.2rem;"><b>Deckoid</b><span style="color: #6366f1;">Tasks</span></h4>
        </div>
        <button class="sidebar-toggle-btn d-none d-md-flex" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="btn btn-sm btn-light d-md-none border-0" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-content px-2 flex-grow-1">
        <?php $prefix = (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') ? 'admin' : 'staff'; ?>
        
        <div class="sidebar-label px-4 mb-2 mt-2">Workspace</div>
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
        </ul>

        <?php if ($prefix == 'admin'): ?>
        <div class="sidebar-label px-4 mb-2 mt-4">Management</div>
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

        <div class="sidebar-label px-4 mb-2 mt-4">Personal</div>
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
    
    <div class="sidebar-footer p-3 border-top border-light">
        <div class="user-profile-card-combined d-flex align-items-center gap-3 p-2 rounded-4">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'User') ?>&background=8b5cf6&color=fff" class="rounded-circle shadow-sm" width="38" height="38">
            <div class="flex-grow-1 overflow-hidden brand-name-container">
                <h6 class="mb-0 text-sm fw-bold text-neutral-900 text-truncate"><?= $_SESSION['user_name'] ?? 'User' ?></h6>
                <p class="mb-0 text-xs text-neutral-500 text-capitalize"><?= $_SESSION['user_role'] ?? 'Member' ?></p>
            </div>
            <a href="<?= url('/logout') ?>" class="logout-icon-btn text-neutral-400 hover-text-danger transition-all" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</aside>

<style>
:root {
    --nav-icon-size: 42px;
    --sidebar-transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar {
    background: white !important;
    border-right: 1px solid var(--neutral-100);
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    z-index: 1050;
    transition: width var(--sidebar-transition), transform var(--sidebar-transition);
    display: flex;
    flex-direction: column;
    box-shadow: 10px 0 30px rgba(0,0,0,0.02);
}

.sidebar-header {
    height: var(--topbar-height);
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: padding var(--sidebar-transition);
}

.logo-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: var(--nav-icon-size);
    justify-content: center;
}

.sidebar-content {
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: none;
    flex-grow: 1;
    padding-top: 0.5rem;
}

.sidebar-content::-webkit-scrollbar { display: none; }

.sidebar-label {
    padding: 1.5rem 1.75rem 0.75rem;
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--neutral-400);
    transition: opacity 0.3s ease, padding var(--sidebar-transition);
}

.sidebar .nav-item {
    padding: 0 0.75rem;
    margin-bottom: 4px;
}

.sidebar .nav-link {
    height: 48px;
    padding: 0;
    display: flex;
    align-items: center;
    color: var(--neutral-600);
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.2s ease;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
}

.nav-icon-container {
    width: 56px; /* Slightly wider than icon for better visual centering in expanded */
    min-width: 56px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: min-width var(--sidebar-transition), width var(--sidebar-transition);
}

.nav-link i {
    font-size: 1.15rem;
    transition: all 0.2s ease;
}

.nav-link span {
    opacity: 1;
    white-space: nowrap;
    transition: opacity 0.3s ease;
}

.sidebar .nav-link:hover {
    background: #f8fafc;
    color: var(--primary-600);
}

.sidebar .nav-link.active {
    background: var(--grad-primary) !important;
    color: white !important;
    box-shadow: 0 8px 16px -4px rgba(139, 92, 246, 0.3);
}

.sidebar .nav-link.active i { color: white !important; }

/* --- Collapsed State --- */
.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar.collapsed .sidebar-header {
    flex-direction: column;
    height: auto;
    padding: 1.5rem 0;
    gap: 15px;
}

.sidebar.collapsed .sidebar-toggle-btn {
    display: flex !important;
    margin: 0 auto;
}

.sidebar.collapsed .brand-text,
.sidebar.collapsed .sidebar-label,
.sidebar.collapsed .nav-link span,
.sidebar.collapsed .brand-name-container,
.sidebar.collapsed .logout-icon-btn {
    opacity: 0;
    pointer-events: none;
    position: absolute; /* Take out of flow to avoid layout shifts */
}

.sidebar.collapsed .nav-item {
    padding: 0 0.5rem;
}

.sidebar.collapsed .nav-link {
    justify-content: center;
}

.sidebar.collapsed .nav-icon-container {
    width: 100%;
    min-width: 100%;
}

.sidebar.collapsed .sidebar-toggle-btn i {
    transform: rotate(180deg);
}

/* --- Footer Section --- */
.sidebar-footer {
    padding: 1.25rem 0.75rem;
    border-top: 1px solid var(--neutral-100);
}

.user-profile-card-combined {
    background: #f8fafc;
    border-radius: 14px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar.collapsed .user-profile-card-combined {
    padding: 0.5rem;
    justify-content: center;
    background: transparent;
}

.sidebar.collapsed .sidebar-footer {
    padding: 1.25rem 0.5rem;
}

.logout-icon-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: var(--neutral-400);
    transition: all 0.2s ease;
}

.logout-icon-btn:hover {
    background: #fee2e2;
    color: #ef4444 !important;
}

/* Specific Icon Colors - When NOT active */
.nav-link:not(.active)[href*="dashboard"] i { color: #8b5cf6; }
.nav-link:not(.active)[href*="projects"] i { color: #64748b; }
.nav-link:not(.active)[href*="tasks"] i { color: #3b82f6; }
.nav-link:not(.active)[href*="staff"] i { color: #0ea5e9; }
.nav-link:not(.active)[href*="kpi"] i { color: #10b981; }
.nav-link:not(.active)[href*="leaves"] i { color: #f59e0b; }
.nav-link:not(.active)[href*="profile"] i { color: #475569; }

.sidebar-toggle-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f8fafc;
    color: var(--neutral-500);
    transition: all 0.3s ease;
}

.sidebar-toggle-btn:hover {
    background: #f1f5f9;
    color: var(--primary-600);
}
</style>
