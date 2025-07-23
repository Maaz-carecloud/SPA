<div>
    <div class="row g-3">
        <div class="col-md-12">
            <x-form.input
                id="name"
                name="name"
                label="Permission Name"
                placeholder="Enter permission name"
                model="lazy:name"
                required
            />
        </div>
        <div class="col-md-12">
            <x-form.select2
                id="create-module"
                label="Module Name"
                wire:model="module"
                :options="$modules"
                placeholder="Select Module"
                required="true"
                wire:ignore
            />
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission to sync select2 values
        document.addEventListener('livewire:load', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    // Sync select2 value before submission
                    const moduleSelect = $('#create-module');
                    if (moduleSelect.length && moduleSelect.hasClass('select2-hidden-accessible')) {
                        const selectedValue = moduleSelect.val();
                        window.Livewire.find('{{ $this->getId() }}').set('module', selectedValue);
                    }
                });
            }
        });
        
        // Listen for modal being shown to reinitialize select2
        $(document).on('shown.bs.modal', '#createPermissionModal', function () {
            setTimeout(function() {
                const $moduleSelect = $('#create-module');
                if ($moduleSelect.length) {
                    // Destroy and reinitialize select2
                    if ($moduleSelect.hasClass('select2-hidden-accessible')) {
                        $moduleSelect.select2('destroy');
                    }
                    
                    $moduleSelect.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Select Module',
                        allowClear: true,
                        dropdownParent: $('#createPermissionModal')
                    });
                    
                    // Update Livewire when select2 changes
                    $moduleSelect.on('change', function() {
                        window.Livewire.find('{{ $this->getId() }}').set('module', $(this).val());
                    });
                }
            }, 100);
        });
        
        // Reset form when modal is closed
        $(document).on('hidden.bs.modal', '#createPermissionModal', function () {
            // Reset the select2 dropdown
            const $moduleSelect = $('#create-module');
            if ($moduleSelect.length && $moduleSelect.hasClass('select2-hidden-accessible')) {
                $moduleSelect.val(null).trigger('change.select2');
            }
        });
    });
</script>
@endpush
