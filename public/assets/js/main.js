/**
 * Main JS - Task Management System
 */

$(document).ready(function() {
    // Global AJAX Setup for Session Timeout
    $.ajaxSetup({
        statusCode: {
            401: function() {
                toastr.error('Session expired. Redirecting to login...');
                setTimeout(() => {
                    window.location.href = BASE_URL + '/login';
                }, 1500);
            }
        }
    });

    // Handle Mobile/Collapsible Sidebar
    const sidebar = $('#sidebar');
    const body = $('body');
    const toggler = $('.sidebar-toggle, #sidebarToggle, #mobileSidebarToggle, #logoToggle');
    const closeBtn = $('#sidebarClose');

    // Tooltip management for Sidebar
    function updateSidebarTooltips() {
        const isCollapsed = body.hasClass('sidebar-collapsed');
        const tooltips = $('[data-bs-toggle="tooltip"]');
        
        // Safety check for Bootstrap tooltip plugin
        if (typeof $.fn.tooltip !== 'function') return;

        if (isCollapsed && $(window).width() > 992) {
            tooltips.tooltip('enable');
        } else if (!isCollapsed && $(window).width() > 992) {
            // Disable only sidebar tooltips in expanded mode
            sidebar.find('[data-bs-toggle="tooltip"]').tooltip('disable');
        }
    }

    // Toggle Sidebar Action
    if (toggler.length) {
        toggler.on('click', function() {
            // Immediately hide any active tooltips to prevent "sticking" during animation
            if (typeof $.fn.tooltip === 'function') {
                $('[data-bs-toggle="tooltip"]').tooltip('hide');
            }
            
            if ($(window).width() > 992) {
                body.toggleClass('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', body.hasClass('sidebar-collapsed'));
                
                // Recalculate DataTables layout if it exists
                if (typeof table !== 'undefined') {
                    setTimeout(() => {
                        table.columns.adjust().draw();
                    }, 300); 
                }
                
                // Small delay to re-evaluate tooltips after animation starts/finishes
                setTimeout(updateSidebarTooltips, 300);
            } else {
                sidebar.toggleClass('active');
                if (sidebar.hasClass('active')) {
                    body.css('overflow', 'hidden');
                } else {
                    body.css('overflow', '');
                }
            }
        });
    }

    if (closeBtn.length) {
        closeBtn.on('click', function() {
            sidebar.removeClass('active');
            body.css('overflow', '');
        });
    }

    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
        if ($(window).width() <= 992) {
            if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0 && !toggler.is(e.target) && toggler.has(e.target).length === 0) {
                sidebar.removeClass('active');
                body.css('overflow', '');
            }
        }
    });

    // Initial tooltip setup
    updateSidebarTooltips();

    // DataTables Default Configuration
    if ($.fn.dataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "_MENU_ per page",
            },
            pageLength: 10,
            responsive: true,
            dom: '<"d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-4"f l>rt<"d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mt-4"i p>',
        });
    }
    // Modal Reset - Fix for "Processing..." stuck state
    $('.modal').on('show.bs.modal', function() {
        const $submitBtn = $(this).find('button[type="submit"]');
        if ($submitBtn.length && $submitBtn.data('original-html')) {
            $submitBtn.prop('disabled', false).html($submitBtn.data('original-html'));
        }
    });

    $('.modal').on('hidden.bs.modal', function() {
        const $form = $(this).find('form');
        if ($form.length && !$form.hasClass('no-reset')) {
            $form[0].reset();
            // Also reset hidden progress inputs if any
            $form.find('input[type="hidden"]').val(function(i, val) {
                return $(this).hasClass('keep-val') ? val : '';
            });
        }
    });

    // --- Action Dropdown Overflow Fix for DataTables (Portal Pattern) ---
    let $activePortalMenu = null;
    let $activePortalParent = null;

    $('body').on('show.bs.dropdown', '.table-responsive', function (e) {
        let $dropdownBtn = $(e.target);
        let $menu = $dropdownBtn.next('.dropdown-menu');
        
        if (!$menu.length) {
            $menu = $dropdownBtn.parent().find('.dropdown-menu');
        }

        if ($menu.length) {
            $activePortalParent = $menu.parent();
            $activePortalMenu = $menu;
            
            // Save original row reference for action handlers
            $menu.data('original-tr', $dropdownBtn.closest('tr'));
            
            // Move to body to escape overflow clipping
            $menu.addClass('dropdown-portal').appendTo('body');
            
            // Force display to calculate dimensions
            $menu.css({ display: 'block', visibility: 'hidden' });
            
            let btnOffset = $dropdownBtn.offset();
            let btnHeight = $dropdownBtn.outerHeight();
            let btnWidth = $dropdownBtn.outerWidth();
            let menuHeight = $menu.outerHeight();
            let menuWidth = $menu.outerWidth();
            let windowHeight = $(window).height();
            let scrollTop = $(window).scrollTop();
            
            // Default placement: below, right-aligned
            let topPos = btnOffset.top + btnHeight + 4;
            let leftPos = btnOffset.left - menuWidth + btnWidth;
            
            // Smart positioning: flip upwards if out of bounds (not enough bottom space)
            if ((btnOffset.top - scrollTop + btnHeight + menuHeight + 10) > windowHeight) {
                topPos = btnOffset.top - menuHeight - 4;
            }
            
            // Prevent going off left screen edge
            if (leftPos < 0) {
                leftPos = btnOffset.left;
            }
            
            $menu.css({
                position: 'absolute',
                top: topPos + 'px',
                left: leftPos + 'px',
                right: 'auto',
                bottom: 'auto',
                transform: 'none', // Override Popper.js completely
                zIndex: 1060, // Keep above all UI elements
                visibility: 'visible'
            });
        }
    });

    $('body').on('hide.bs.dropdown', '.table-responsive', function (e) {
        if ($activePortalMenu && $activePortalParent) {
            $activePortalMenu.css({
                position: '',
                top: '',
                left: '',
                right: '',
                bottom: '',
                transform: '',
                zIndex: '',
                display: '',
                visibility: ''
            }).removeClass('dropdown-portal');
            
            $activePortalParent.append($activePortalMenu);
            
            $activePortalMenu = null;
            $activePortalParent = null;
        }
    });
});

/**
 * Global Form Handler for AJAX Submissions
 * @param {string} selector - Form selector
 * @param {function} callback - Success callback
 */
function handleFormSubmit(selector, callback) {
    const $form = $(selector);
    if (!$form.length) return;

    // Use off().on() to prevent duplicate event listeners
    $form.off('submit').on('submit', function(e) {
        e.preventDefault();
        
        const $submitBtn = $form.find('button[type="submit"]');
        
        // Prevent double submission if already processing
        if ($submitBtn.prop('disabled')) return;

        // Basic Frontend Validation
        let isValid = true;
        let firstInvalid = null;
        $form.find('[required]').each(function() {
            if (!$(this).val() || ($(this).is('select') && $(this).val() === null)) {
                $(this).addClass('is-invalid');
                isValid = false;
                if (!firstInvalid) firstInvalid = $(this);
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            toastr.warning('Please fill in all required fields');
            if (firstInvalid) firstInvalid.focus();
            return;
        }

        const originalBtnHtml = $submitBtn.html();
        const noToast = $form.data('no-toast') === true;
        
        // Disable button and show loading state
        $submitBtn.data('original-html', originalBtnHtml);
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        $.ajax({
            url: $form.attr('action'),
            method: $form.attr('method') || 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            timeout: 15000, // 15 seconds timeout as per requirements
            success: function(response) {
                // Support both legacy .success and new .status formats
                const isSuccess = response.status === 'success' || response.success === true;
                const isValidationErr = response.status === 'validation_error';

                if (isSuccess) {
                    if (!noToast) {
                        toastr.success(response.message || 'Action completed successfully');
                    }
                    if (callback && typeof callback === 'function') {
                        callback(response);
                    }
                } else if (isValidationErr) {
                    toastr.warning(response.message || 'Please correct the validation errors');
                    // Add logic here to highlight specific fields if response.errors is provided
                } else {
                    toastr.error(response.message || 'Something went wrong');
                }
            },
            error: function(xhr, status, error) {
                let message = 'An error occurred. Please try again.';
                if (status === 'timeout') {
                    message = 'Request timed out. Please try again.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
            complete: function() {
                // ALWAYS re-enable button regardless of outcome
                setTimeout(() => {
                    $submitBtn.prop('disabled', false).html($submitBtn.data('original-html') || originalBtnHtml);
                }, 200);
            }
        });
    });
}
