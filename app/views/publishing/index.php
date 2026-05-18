<?php require_once ROOT_PATH . '/app/views/layouts/topbar.php'; ?>

<main class="main-content">
    <div class="container-fluid animate-fade-up">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
            <div>
                <h3 class="fw-bold text-neutral-900 mb-1" id="report-title-display">Publishing Report</h3>
                <p class="text-neutral-500 mb-0">Track content production and publishing status</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <?php if ($_SESSION['user_role'] == 'admin'): ?>
                <select id="select-month" class="form-select form-select-sm rounded-pill" style="width: 120px;">
                    <?php
                    $months = [
                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                    ];
                    $currentMonth = date('n');
                    foreach ($months as $val => $name):
                    ?>
                        <option value="<?= $val ?>" <?= $val == $currentMonth ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
                        
                <select id="select-year" class="form-select form-select-sm rounded-pill" style="width: 100px;">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear - 1; $y <= $currentYear + 2; $y++):
                    ?>
                        <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>

                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-load-report">
                    <i class="fas fa-sync me-1"></i> Load
                </button>

                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" id="btn-create-month">
                    <i class="fas fa-plus me-1"></i> Create Month
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btn-edit-title">
                    <i class="fas fa-pen me-1"></i> Title
                </button>
                <?php endif; ?>

                <button type="button" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm ms-2" id="btn-save-report">
                    <i class="fas fa-save me-2"></i> Save Report
                </button>
            </div>
        </div>

        <!-- Color Legend -->
        <div class="glass-card mb-4 p-3">
            <div class="d-flex align-items-center gap-4">

                <div class="d-flex align-items-center gap-2">
                    <div class="color-dot bg-yellow"></div>
                    <span class="text-sm text-neutral-700">Production</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="color-dot bg-orange"></div>
                    <span class="text-sm text-neutral-700">Approval</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="color-dot bg-green"></div>
                    <span class="text-sm text-neutral-700">Published</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="color-dot bg-empty"></div>
                    <span class="text-sm text-neutral-700">Empty</span>
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div id="report-container">
            <!-- Sections will be rendered here -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-neutral-500">Loading report data...</p>
            </div>
        </div>
    </div>
</main>

<!-- Edit Report Title Modal -->
<div class="modal fade" id="editTitleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-4">
            <div class="modal-header border-0 pb-3">
                <h5 class="fw-bold text-neutral-900 mb-0">Edit Report Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-0">
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-neutral-400 text-uppercase">Title</label>
                    <input type="text" class="form-control rounded-4 title-input" id="input-report-title" placeholder="Enter report title">
                </div>
            </div>
            <div class="modal-footer border-0 pt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-save-title">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const isAdmin = '<?= $_SESSION['user_role'] ?>' === 'admin';
    const userId = '<?= $_SESSION['user_id'] ?>';
    const allUsers = <?= json_encode($users ?? []) ?>;
    
    let reportState = {
        report: {},
        sections: [],
        rows: [],
        cells: []
    };
    
    let isSaving = false;

    function fetchReport() {
        const month = $('#select-month').val();
        const year = $('#select-year').val();
        
        $.get('<?= url('/api/publishing/fetch-report') ?>', { month: month, year: year }, function(res) {
            if (res.status === 'success') {
                reportState = res.data;
                
                if (!reportState.report || !reportState.report.id) {
                    $('#report-title-display').text('No Report Found');
                    let html = '<div class="text-center py-5 text-neutral-500"><p>No report found for selected month.</p>';
                    if (isAdmin) {
                        html += '<button class="btn btn-success rounded-pill px-4" id="btn-create-month-inline"><i class="fas fa-plus me-2"></i>Create Month</button>';
                    }
                    html += '</div>';
                    $('#report-container').html(html);
                    
                    // Add click handler for inline button
                    $('#btn-create-month-inline').on('click', function() {
                        createMonth();
                    });
                    return;
                }
                
                $('#report-title-display').text(reportState.report.title);
                $('#input-report-title').val(reportState.report.title);
                
                renderReport();
            } else {
                toastr.error(res.message || 'Failed to fetch report');
            }
        }).fail(function() {
            toastr.error('Server error while fetching report');
        });
    }

    function createMonth() {
        const month = $('#select-month').val();
        const year = $('#select-year').val();
        
        $.post('<?= url('/api/publishing/create-month') ?>', { month: month, year: year }, function(res) {
            if (res.status === 'success') {
                toastr.success(res.message);
                fetchReport(); // Reload to show the new report
            } else {
                toastr.error(res.message);
            }
        });
    }

    function renderReport() {
        const $container = $('#report-container');
        $container.empty();

        if (reportState.sections.length === 0) {
            const msg = isAdmin ? 'No sections found.' : 'No Publishing Tasks Assigned';
            $container.html(`<div class="text-center py-5 text-neutral-500">${msg}</div>`);
            return;
        }

        reportState.sections.forEach(section => {
            const totalDays = reportState.report.total_days || 15;
            const $sectionCard = $('<div>').addClass('glass-card mb-5 overflow-hidden');
            const $sectionHeader = $('<div>').addClass('p-4 border-bottom border-light bg-neutral-50 d-flex justify-content-between align-items-center')
                .html(`<h5 class="fw-bold text-neutral-900 mb-0">${section.section_name}</h5>`);
            
            if (isAdmin) {
                const $addRowBtn = $('<button>').addClass('btn btn-sm btn-outline-primary rounded-pill px-3')
                    .html('<i class="fas fa-plus me-1"></i> Add Row')
                    .on('click', () => addRow(section.id));
                $sectionHeader.append($addRowBtn);
            }
            
            $sectionCard.append($sectionHeader);

            const $tableResponsive = $('<div>').addClass('table-responsive');
            const $table = $('<table>').addClass('table table-bordered align-middle mb-0').css('min-width', '1200px');
            
            // Header (Generic top headers)
            const $thead = $('<thead>').addClass('bg-neutral-50');
            const $headerRow = $('<tr>');
            $headerRow.append('<th class="ps-4" style="min-width: 200px; width: 200px;">Title</th>');
            $headerRow.append(`<th colspan="16" class="text-center">Publishing Days</th>`);
            if (isAdmin) {
                $headerRow.append('<th class="text-center" style="width: 150px;">Assignment</th>');
                $headerRow.append('<th class="text-center" style="width: 80px;">Actions</th>');
            }
            $thead.append($headerRow);
            $table.append($thead);

            // Body
            const $tbody = $('<tbody>');
            const sectionRows = reportState.rows.filter(r => r.section_id === section.id);

            if (sectionRows.length === 0) {
                const colspan = 18;
                $tbody.append(`<tr><td colspan="${colspan}" class="text-center py-4 text-neutral-400">No rows in this section</td></tr>`);
            } else {
                sectionRows.forEach(row => {
                    // Row 1: Header Day 1 -> 15
                    const $tr1 = $('<tr>');
                    
                    // Title (rowspan=4)
                    const $titleTd = $('<td>').addClass('ps-4').attr('rowspan', 4);
                    if (isAdmin) {
                        const $input = $('<input>').addClass('form-control form-control-sm title-input')
                            .val(row.title)
                            .on('change', (e) => {
                                row.title = e.target.value;
                            });
                        $titleTd.append($input);
                    } else {
                        $titleTd.html(`<span class="fw-bold text-neutral-800">${row.title}</span>`);
                    }
                    $tr1.append($titleTd);

                    // Day 1..15 Headers
                    for (let i = 1; i <= 15; i++) {
                        $tr1.append(`<th class="text-center bg-neutral-50 text-xs fw-bold" style="width: 60px;">Day ${i}</th>`);
                    }
                    // 16th column blank header
                    $tr1.append(`<th class="bg-neutral-50" style="width: 60px;"></th>`);

                    // 16th column in Row 1 (blank)
                    $tr1.append($('<td>').addClass('bg-neutral-50/50'));

                    if (isAdmin) {
                        // Assignment (rowspan=4)
                        const $assignTd = $('<td>').addClass('text-center').attr('rowspan', 4);
                        const $select = $('<select>').addClass('form-control form-control-sm select2-assign')
                            .attr('multiple', 'multiple')
                            .css('width', '100%');
                            
                        let assignedUsers = [];
                        try {
                            assignedUsers = JSON.parse(row.assigned_users_json) || [];
                        } catch(e) {
                            assignedUsers = [];
                        }
                        
                        allUsers.forEach(u => {
                            const isSelected = assignedUsers.includes(u.id);
                            const $option = $('<option>')
                                .val(u.id)
                                .text(`${u.full_name} (${u.role_name || 'Staff'})`)
                                .prop('selected', isSelected);
                            $select.append($option);
                        });
                        
                        $select.on('change', function() {
                            const selectedIds = $(this).val() || [];
                            row.assigned_users_json = JSON.stringify(selectedIds);
                        });
                        
                        $assignTd.append($select);
                        $tr1.append($assignTd);

                        // Actions (rowspan=4)
                        const $actionsTd = $('<td>').addClass('text-center').attr('rowspan', 4);
                        const $deleteBtn = $('<button>').addClass('btn btn-sm btn-link text-danger p-0')
                            .html('<i class="fas fa-trash-can"></i>')
                            .on('click', () => deleteRow(row.id));
                        $actionsTd.append($deleteBtn);
                        $tr1.append($actionsTd);
                    }

                    $tbody.append($tr1);

                    // Row 2: Data Day 1 -> 15
                    const $trD1 = $('<tr>');
                    for (let i = 1; i <= 15; i++) {
                        const cell = reportState.cells.find(c => c.row_id === row.id && parseInt(c.day_number) === i) || { row_id: row.id, day_number: i, status_color: 'empty', cell_value: '' };
                        
                        const $cellTd = $('<td>').addClass('text-center p-0').css('height', '50px');
                        const $cellDiv = $('<div>').addClass(`cell-box bg-${cell.status_color}`)
                            .attr('data-row-id', row.id)
                            .attr('data-day', i)
                            .attr('contenteditable', 'true')
                            .html(cell.cell_value || '');
                        
                        $cellDiv.on('dblclick', function() { cycleColor(cell, $cellDiv); });
                        
                        $cellDiv.on('input', function() {
                            const text = $(this).text();
                            const stateCellIndex = reportState.cells.findIndex(c => c.row_id === cell.row_id && parseInt(c.day_number) === i);
                            if (stateCellIndex !== -1) {
                                reportState.cells[stateCellIndex].cell_value = text;
                            } else {
                                cell.cell_value = text;
                                reportState.cells.push(cell);
                            }
                        });
                        
                        $cellTd.append($cellDiv);
                        $trD1.append($cellTd);
                    }
                    $trD1.append($('<td>').addClass('bg-neutral-50/50')); // 16th column blank
                    $tbody.append($trD1);

                    // Row 3: Header Day 16 -> 31
                    const $trH2 = $('<tr>');
                    for (let i = 16; i <= 31; i++) {
                        if (i <= totalDays) {
                            $trH2.append(`<th class="text-center bg-neutral-50 text-xs fw-bold" style="width: 60px;">Day ${i}</th>`);
                        } else {
                            $trH2.append(`<th class="bg-neutral-50" style="width: 60px;"></th>`);
                        }
                    }
                    $tbody.append($trH2);

                    // Row 4: Data Day 16 -> 31
                    const $trD2 = $('<tr>');
                    for (let i = 16; i <= 31; i++) {
                        if (i <= totalDays) {
                            const cell = reportState.cells.find(c => c.row_id === row.id && parseInt(c.day_number) === i) || { row_id: row.id, day_number: i, status_color: 'empty', cell_value: '' };
                            
                            const $cellTd = $('<td>').addClass('text-center p-0').css('height', '50px');
                            const $cellDiv = $('<div>').addClass(`cell-box bg-${cell.status_color}`)
                                .attr('data-row-id', row.id)
                                .attr('data-day', i)
                                .attr('contenteditable', 'true')
                                .html(cell.cell_value || '');
                            
                            $cellDiv.on('dblclick', function() { cycleColor(cell, $cellDiv); });
                            
                            $cellDiv.on('input', function() {
                                const text = $(this).text();
                                const stateCellIndex = reportState.cells.findIndex(c => c.row_id === cell.row_id && parseInt(c.day_number) === i);
                                if (stateCellIndex !== -1) {
                                    reportState.cells[stateCellIndex].cell_value = text;
                                } else {
                                    cell.cell_value = text;
                                    reportState.cells.push(cell);
                                }
                            });
                            
                            $cellTd.append($cellDiv);
                            $trD2.append($cellTd);
                        } else {
                            $trD2.append($('<td>').addClass('bg-neutral-50/50'));
                        }
                    }
                    $tbody.append($trD2);
                });
            }

            $table.append($tbody);
            $tableResponsive.append($table);
            $sectionCard.append($tableResponsive);
            $container.append($sectionCard);
        });
        
        // Initialize Select2
        if (isAdmin) {
            $('.select2-assign').select2({
                placeholder: 'Assign users',
                allowClear: true
            });
        }
    }

    function cycleColor(cell, $el) {
        const colors = ['empty', 'yellow', 'orange', 'green'];
        let currentIndex = colors.indexOf(cell.status_color);
        if (currentIndex === -1) currentIndex = 0;
        
        const nextIndex = (currentIndex + 1) % colors.length;
        const nextColor = colors[nextIndex];
        
        $el.removeClass(`bg-${cell.status_color}`).addClass(`bg-${nextColor}`);
        cell.status_color = nextColor;
        
        // Update state
        const stateCellIndex = reportState.cells.findIndex(c => c.row_id === cell.row_id && c.day_number === cell.day_number);
        if (stateCellIndex !== -1) {
            reportState.cells[stateCellIndex].status_color = nextColor;
        } else {
            reportState.cells.push({ ...cell, status_color: nextColor });
        }
    }

    function addRow(sectionId) {
        const newId = 'temp-' + Math.random().toString(36).substr(2, 9);
        const newRow = {
            id: '', // Empty for backend to generate UUID
            section_id: sectionId,
            title: 'New Row',
            assigned_users_json: '[]',
            sort_order: reportState.rows.filter(r => r.section_id === sectionId).length
        };
        
        // We need a temp ID for the frontend state to map cells
        const tempRow = { ...newRow, id: newId };
        
        reportState.rows.push(tempRow);
        renderReport();
    }

    function deleteRow(rowId) {
        if (rowId.startsWith('temp-')) {
            reportState.rows = reportState.rows.filter(r => r.id !== rowId);
            reportState.cells = reportState.cells.filter(c => c.row_id !== rowId);
            renderReport();
            return;
        }

        Swal.fire({
            title: 'Delete Row?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/api/publishing/delete-row') ?>', { id: rowId }, function(res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                        reportState.rows = reportState.rows.filter(r => r.id !== rowId);
                        reportState.cells = reportState.cells.filter(c => c.row_id !== rowId);
                        renderReport();
                    } else {
                        toastr.error(res.message);
                    }
                }).fail(function() {
                    toastr.error('Failed to delete row');
                });
            }
        });
    }

    $('#btn-save-report').on('click', function() {
        if (isSaving) return;
        
        isSaving = true;
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
        
        // Send full state, backend handles temp IDs
        const dataToSend = JSON.parse(JSON.stringify(reportState));
        
        // Also map cells that belong to temp rows to have empty row_id or handle it.
        // Wait, if row_id is empty, backend won't know which row it belongs to.
        // So we should probably save rows first, get IDs, then save cells.
        // But the prompt says "transaction -> save rows -> save cells".
        // This implies the backend handles it or we send the hierarchy.
        // Let's assume the frontend sends the data, and if a row is new, the backend inserts it and uses the new ID for its cells?
        // That requires mapping.
        // Let's assume the frontend can't add cells to a row that hasn't been saved yet? No, that's not Airtable-like.
        // Let's assume the backend handles it by looking at the order or we send rows and cells associated.
        // Better: Let's assume the frontend sends the full state, and for new rows, we handle it.
        // Wait, if we send `rows` and `cells` separately, and row has no ID, how do we link cells?
        // We can use the index or temp ID in the request!
        // Let's keep the temp ID in the request, and the backend can use it to map!
        // Yes, that's clean. The backend generates a real UUID and replaces the temp ID in the cells list.
        
        $.ajax({
            url: '<?= url('/api/publishing/save-report') ?>',
            type: 'POST',
            data: JSON.stringify(dataToSend),
            contentType: 'application/json',
            success: function(res) {
                if (res.status === 'success') {
                    toastr.success(res.message);
                    fetchReport(); // Refetch to get real IDs and clean state
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                isSaving = false;
                $('#btn-save-report').prop('disabled', false).html('<i class="fas fa-save me-2"></i> Save Report');
            }
        });
    });

    $('#btn-edit-title').on('click', function() {
        $('#editTitleModal').modal('show');
    });

    $('#btn-save-title').on('click', function() {
        const newTitle = $('#input-report-title').val();
        reportState.report.title = newTitle;
        $('#report-title-display').text(newTitle);
        $('#editTitleModal').modal('hide');
    });

    $('#btn-load-report').on('click', function() {
        fetchReport();
    });

    $('#btn-create-month').on('click', function() {
        createMonth();
    });

    // Initial load
    fetchReport();
});
</script>

<style>
.color-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,0.1);
}
.bg-yellow { background-color: #fef08a !important; } /* Yellow 200 */
.bg-orange { background-color: #fed7aa !important; } /* Orange 200 */
.bg-green { background-color: #bbf7d0 !important; } /* Green 200 */
.bg-empty { background-color: #f3f4f6 !important; } /* Gray 100 */

.cell-box {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s ease;
    font-size: 0.8rem;
    font-weight: bold;
    color: #1f2937;
}
.cell-box:hover {
    filter: brightness(0.95);
}

.table th, .table td {
    padding: 0.5rem;
    vertical-align: middle;
}

.table-bordered th, .table-bordered td {
    border-color: #e5e7eb !important;
}

.title-input {
    background-color: #FFFFFF !important;
    color: #111827 !important;
    border: 1px solid #D1D5DB !important;
    border-radius: 0.375rem !important;
    padding: 12px 14px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
    width: 100% !important;
    display: block !important;
}
.title-input:focus {
    border-color: #7C3AED !important;
    box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25) !important;
    outline: none !important;
}
.title-input::placeholder {
    color: #9CA3AF !important;
    opacity: 1 !important;
}

.table th:first-child, .table td:first-child {
    position: sticky;
    left: 0;
    background-color: #FFFFFF !important;
    z-index: 2;
    box-shadow: 2px 0 5px rgba(0,0,0,0.05);
}

.table thead th:first-child {
    z-index: 3;
}
</style>
