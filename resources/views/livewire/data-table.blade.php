<div class="table-theme" wire:ignore.self>
    <div class="datatable-toolbar d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <div class="datatable-search flex-grow-1"></div>
        <div class="datatable-export">
            <div id="customExportDropdown-{{ $tableId }}" class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown-{{ $tableId }}"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Export
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown-{{ $tableId }}">
                    <li><a class="dropdown-item" href="#" id="export-csv-{{ $tableId }}">CSV</a></li>
                    <li><a class="dropdown-item" href="#" id="export-excel-{{ $tableId }}">Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="export-pdf-{{ $tableId }}">PDF</a></li>
                    <li><a class="dropdown-item" href="#" id="export-print-{{ $tableId }}">Print</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="">
        <table class="table table-striped table-borderless mb-0" id="{{ $tableId }}">
            <thead>
                <tr>
                    @foreach($columns as $col)
                    <th>{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('scripts')

<script>
    function initDataTable_{{ $tableId }}() {
    let tableSelector = '#' + @json($tableId);
    let exportDropdown = '#customExportDropdown-' + @json($tableId);

    // Full cleanup: destroy DataTable and restore original table HTML if needed
    if ($.fn.DataTable.isDataTable(tableSelector)) {
        $(tableSelector).DataTable().destroy();
        // Remove DataTable wrappers by replacing with original table
        var $table = $(tableSelector);
        if ($table.closest('.dataTables_wrapper').length) {
            var original = $table.clone(false);
            $table.closest('.dataTables_wrapper').replaceWith(original);
        }
    }
    // Remove any duplicate export dropdowns
    $('.custom-export-dropdown').find(exportDropdown).remove();

    var table = $(tableSelector).DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        deferRender: true,
        dom:
            "<'datatable-toolbar-row'<'datatable-search-container'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-4'l><'col-sm-4'i><'col-sm-4'p>>",
        ajax: {
            url: @json($ajaxUrl),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        },
        buttons: [
            { extend: 'csv', className: 'buttons-csv d-none', exportOptions: { modifier: { page: 'all' } } },
            { extend: 'excel', className: 'buttons-excel d-none', exportOptions: { modifier: { page: 'all' } } },
            { extend: 'pdf', className: 'buttons-pdf d-none', exportOptions: { modifier: { page: 'all' } } },
            { extend: 'print', className: 'buttons-print d-none', exportOptions: { modifier: { page: 'all' } } }
        ],
        columns: (function() {
            var cols = @json($columns);
            return cols.map(function(col, idx, arr) {
                var obj = { data: idx };
                if (idx === arr.length - 1) {
                    obj.orderable = false;
                    obj.searchable = false;
                }
                return obj;
            });
        })(),
        language: {
            emptyTable: 'No data available in table',
            lengthMenu: 'Show _MENU_ entries'
        },
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]
    });
    // Move DataTable search box into our toolbar
    $('.datatable-search').empty();
    $('.datatable-search-container').appendTo('.datatable-search');
    // Export actions
    $('#export-csv-' + @json($tableId)).off('click').on('click', function(e) { e.preventDefault(); table.button('.buttons-csv').trigger(); });
    $('#export-excel-' + @json($tableId)).off('click').on('click', function(e) { e.preventDefault(); table.button('.buttons-excel').trigger(); });
    $('#export-pdf-' + @json($tableId)).off('click').on('click', function(e) { e.preventDefault(); table.button('.buttons-pdf').trigger(); });
    $('#export-print-' + @json($tableId)).off('click').on('click', function(e) { e.preventDefault(); table.button('.buttons-print').trigger(); });

    // No need to re-append export dropdown on draw since it's outside DataTable
}

document.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable on page load
    initDataTable_{{ $tableId }}();

    // Listen for custom event to re-initialize DataTable
    window.addEventListener('datatable-reinit', function () {
        setTimeout(() => {
            initDataTable_{{ $tableId }}();
        }, 100); // slight delay to ensure DOM is ready
    });
});

// Re-initialize DataTable on Livewire SPA navigation
document.addEventListener('livewire:navigated', function () {
    // Initialize DataTable on page load
    initDataTable_{{ $tableId }}();
    
    window.addEventListener('datatable-reinit', function () {
        setTimeout(() => {
            initDataTable_{{ $tableId }}();
        }, 100); // slight delay to ensure DOM is ready
    });
});
</script>
@endpush