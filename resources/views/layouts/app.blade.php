<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" data-navigate-once>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" data-navigate-once>
        <link rel="preconnect" href="https://fonts.googleapis.com" data-navigate-once>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin data-navigate-once>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" data-navigate-once>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" data-navigate-once>
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" data-navigate-once>
        <link rel="stylesheet" href="{{ asset('css/datatable-custom.css') }}" data-navigate-once>
        <title>{{ $title ?? 'Laravel' }}</title>
        @livewireStyles
        @stack('styles')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
            body {
                font-family: "Outfit", sans-serif;
                background-color: #f8f9fa;
                font-weight: 400;
            }
            .theme-filled-btn,
            .theme-unfilled-btn:hover,
            .theme-filled-btn:hover {
                background-color: #C72127;
                border: 1px solid #C72127;
                color: #ffffff;
            }
            
            .theme-unfilled-btn {
                background-color: transparent;
                border: 1px solid #C72127;
                color: #C72127;
            }
            .action-items span {
                width: 30px;
                height: 30px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 100%;
                background-color: #c721271f;
                margin: 0 0.25rem;
                cursor: pointer;
            }
                
            .action-items span i {
                color: #C72127;
            }
        </style>
    </head>
    <body>
        <div class="container mt-3">
            <nav class="mb-4">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link{{ request()->is('/') || request()->is('posts') ? ' active' : '' }}" href="/" wire:navigate>Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->is('users') ? ' active' : '' }}" href="/users" wire:navigate>Users</a>
                    </li>
                </ul>
            </nav>
            {{ $slot }}
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" data-navigate-once></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" data-navigate-once></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" data-navigate-once></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js" data-navigate-once></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js" data-navigate-once></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js" data-navigate-once></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" data-navigate-once></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" data-navigate-once></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" data-navigate-once></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireScripts
        @stack('scripts')
        <script>
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
        </script>
    </body>
</html>
