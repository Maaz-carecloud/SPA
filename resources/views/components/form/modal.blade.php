@props([
    'id' => 'modal',
    'title' => 'Modal Title',
    'size' => 'modal-lg', // modal-sm, modal-lg, modal-xl, modal-fullscreen
    'backdrop' => 'true', // true, false, static
    'keyboard' => 'true',
    'centered' => false,
    'scrollable' => false,
    'closeButton' => true,
    'footerClass' => 'd-flex justify-content-end gap-2',
    'wireCloseMethod' => null, // Optional Livewire method to call on close
])

<div 
    class="modal fade modal-right-bottom" 
    id="{{ $id }}" 
    tabindex="-1" 
    aria-labelledby="{{ $id }}Label" 
    aria-hidden="true"
    data-bs-backdrop="{{ $backdrop }}"
    data-bs-keyboard="{{ $keyboard }}"
    wire:ignore.self
>
    <div class="modal-dialog-right modal-dialog {{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }}">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">
                    {{ $title }}
                </h5>
                @if($closeButton)
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @if($wireCloseMethod) wire:click="{{ $wireCloseMethod }}" @endif></button>
                @endif
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                {{ $slot }}
            </div>
            
            <!-- Modal Footer -->
            @isset($footer)
                <div class="modal-footer {{ $footerClass }}">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('wire:init', function() {
        // Initialize modal events
        const modalElement = document.getElementById('{{ $id }}');
        
        if (modalElement) {
            modalElement.addEventListener('shown.bs.modal', function (event) {
                // Focus on first input when modal opens
                const firstInput = modalElement.querySelector('input, textarea, select');
                if (firstInput) {
                    firstInput.focus();
                }
            });
            
            modalElement.addEventListener('hidden.bs.modal', function (event) {
                // Clear form data when modal closes (optional)
                const forms = modalElement.querySelectorAll('form');
                forms.forEach(form => {
                    if (form.hasAttribute('data-clear-on-close')) {
                        form.reset();
                    }
                });
            });
        }
    });
    
    // Livewire events for modal control
    window.addEventListener('show-modal-{{ $id }}', event => {
        const modal = new bootstrap.Modal(document.getElementById('{{ $id }}'));
        modal.show();
    });
    
    window.addEventListener('hide-modal-{{ $id }}', event => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('{{ $id }}'));
        if (modal) {
            modal.hide();
        }
    });
</script>
@endpush
