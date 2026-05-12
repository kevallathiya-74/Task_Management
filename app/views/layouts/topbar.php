<div class="top-bar">
    <div class="d-flex align-items-center w-100">
        <button class="sidebar-toggler me-4 btn btn-light d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars-staggered"></i>
        </button>
        
        <div class="page-title d-none d-sm-block">
            <h5 class="fw-bold mb-0 text-neutral-900"><?= $title ?? 'Dashboard' ?></h5>
            <p class="mb-0 text-xs text-neutral-400">Welcome back, <?= explode(' ', $_SESSION['user_name'])[0] ?>!</p>
        </div>
    </div>
</div>

<style>
.sidebar-toggler {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}
</style>
