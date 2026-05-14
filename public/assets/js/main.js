/**
 * Main JS - Task Management System
 */

$(document).ready(function() {
    // Handle Mobile/Collapsible Sidebar
    const sidebar = $('#sidebar');
    const mainContent = $('.main-content');
    const toggler = $('.sidebar-toggle, #sidebarToggle, #mobileSidebarToggle');
    const closeBtn = $('#sidebarClose');

    // Restore state from localStorage
    if (localStorage.getItem('sidebarCollapsed') === 'true' && $(window).width() > 992) {
        sidebar.addClass('collapsed');
        mainContent.addClass('expanded');
    }

    // Tooltip management for Sidebar
    function updateSidebarTooltips() {
        const isCollapsed = sidebar.hasClass('collapsed');
        sidebar.find('[data-bs-toggle="tooltip"]').each(function() {
            if (isCollapsed && $(window).width() > 992) {
                $(this).tooltip('enable');
            } else {
                $(this).tooltip('disable').tooltip('hide');
            }
        });
    }

    if (toggler.length) {
        toggler.on('click', function() {
            // Immediately hide any active tooltips to prevent "sticking" during animation
            $('[data-bs-toggle="tooltip"]').tooltip('hide');
            
            if ($(window).width() > 992) {
                sidebar.toggleClass('collapsed');
                mainContent.toggleClass('expanded');
                $('.top-bar').toggleClass('expanded');
                localStorage.setItem('sidebarCollapsed', sidebar.hasClass('collapsed'));
                
                // Recalculate DataTables layout if it exists
                if (typeof table !== 'undefined') {
                    setTimeout(() => {
                        table.columns.adjust().draw();
                    }, 400); // Increased delay to match 0.4s transition
                }
                
                // Small delay to re-evaluate tooltips after animation starts/finishes
                setTimeout(updateSidebarTooltips, 400);
            } else {
                sidebar.toggleClass('active');
                if (sidebar.hasClass('active')) {
                    $('body').css('overflow', 'hidden');
                } else {
                    $('body').css('overflow', '');
                }
            }
        });
    }

    if (closeBtn.length) {
        closeBtn.on('click', function() {
            sidebar.removeClass('active');
            $('body').css('overflow', '');
        });
    }

    // ESC key to close mobile sidebar
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.hasClass('active')) {
            sidebar.removeClass('active');
            $('body').css('overflow', '');
        }
    });

    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
        if ($(window).width() <= 992) {
            if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0 && !toggler.is(e.target) && toggler.has(e.target).length === 0) {
                sidebar.removeClass('active');
                $('body').css('overflow', '');
            }
        }
    });

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    updateSidebarTooltips();

    // Initialize Popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    // Default Toastr Config
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right", // Changed for modern feel
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "6000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown", // Smoother transition
        "hideMethod": "fadeOut"
    };
});

/**
 * Global AJAX Form Handler
 */
function handleFormSubmit(formId, successCallback, errorCallback) {
    $(formId).on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        const showToast = form.data('no-toast') !== true;
        
        // Show loading state
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
        
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (showToast) {
                        toastr.success(response.message);
                    }
                    if (successCallback) successCallback(response);
                } else {
                    toastr.error(response.message || 'Something went wrong');
                    if (errorCallback) errorCallback(response);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'Server error occurred');
                if (errorCallback) errorCallback(response);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });
}
