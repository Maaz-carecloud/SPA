<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="icon" href="{{ asset('assets/images/bgs-logo-monogram.webp') }}" type="image/x-icon">

        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" data-navigate-once>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" data-navigate-once>
        <link rel="preconnect" href="https://fonts.googleapis.com" data-navigate-once>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin data-navigate-once>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" data-navigate-once>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" data-navigate-once>
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" data-navigate-once>
        <link rel="stylesheet" href="{{ asset('css/datatable-custom.css') }}" data-navigate-once>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" data-navigate-once>
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" data-navigate-once>
        <title>{{ $title ?? 'BGS Grammar School' }}</title>
        @livewireStyles
        @stack('styles')
    </head>
    <body class="overflow-x-hidden">
        {{ $slot }}
        <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}" data-navigate-once></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" data-navigate-once></script>
        <script data-navigate-once src="{{ asset('assets/js/chart.js') }}"></script>
        <script data-navigate-once src="{{ asset('assets/js/main.js') }}"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" data-navigate-once></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js" data-navigate-once></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js" data-navigate-once></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js" data-navigate-once></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" data-navigate-once></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" data-navigate-once></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" data-navigate-once></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-navigate-once></script>
        <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js" data-navigate-once></script>

        @livewireScripts
        @stack('scripts')

        <script>
            //This global function handled Closing the modal from the component event $this->dispatch('hide-modal')
            function registerUserModalAndTableEvents() {
                Livewire.on('hide-modal', function () {
                    var modalEl = document.getElementById('createModal');
                    if (modalEl) {
                        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.hide();
                    }
                });
            }
            document.addEventListener('DOMContentLoaded', registerUserModalAndTableEvents);
            document.addEventListener('livewire:navigated', registerUserModalAndTableEvents);

            // Delegate click for delete with SweetAlert2
            document.body.addEventListener('click', function(e) {
                var delBtn = e.target.closest('.delete-swal');
                if (delBtn) {
                    e.preventDefault();
                    var id = delBtn.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to delete this record!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#C72127',
                        cancelButtonColor: '#757575',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('delete-record', { id: id });
                        }
                    });
                }
            });
            //This function is responsible for showing the success/error alerts
            document.removeEventListener('error', window.__notyfErrorHandler);
            document.removeEventListener('success', window.__notyfSuccessHandler);

            var notyf = new Notyf({
                duration: 5000, // Increased from 2500ms to 5000ms (5 seconds)
                position: {
                    x: 'right',
                    y: 'top',
                },
                dismissible: true, // Allow manual dismissal
                ripple: true
            });

            window.__notyfErrorHandler = function(event) {
                notyf.error(event.detail.message);
            };
            window.__notyfSuccessHandler = function(event) {
                notyf.success(event.detail.message);
            };

            document.addEventListener('error', window.__notyfErrorHandler);
            document.addEventListener('success', window.__notyfSuccessHandler);
        </script>
    </body>
</html>
