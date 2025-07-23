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
                id="edit-module"
                label="Module Name"
                wire:model="module"
                :value="$module"
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
        // Listen for edit modal being shown to reinitialize select2
        $(document).on('shown.bs.modal', '#editPermissionModal', function () {
            setTimeout(function() {
                const $moduleSelect = $('#edit-module');
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
                        dropdownParent: $('#editPermissionModal')
                    });
                    
                    // Update Livewire when select2 changes
                    $moduleSelect.on('change', function() {
                        window.Livewire.find('{{ $this->getId() }}').set('module', $(this).val());
                    });
                }
            }, 100);
        });
        
        // Listen for Livewire updates to sync select2 value
        document.addEventListener('livewire:load', function () {
            window.addEventListener('permissionDataLoaded', function() {
                setTimeout(function() {
                    // Wait a bit for Livewire to update the DOM, then trigger select2 reinit
                    setTimeout(function() {
                        // Trigger livewire:updated event to make sure select2 component reinitializes
                        document.dispatchEvent(new CustomEvent('livewire:updated'));
                        
                        // Also manually trigger select2 update if needed
                        const $select = $('#edit-module');
                        if ($select.length && $select.hasClass('select2-hidden-accessible')) {
                            const currentValue = window.Livewire.find('{{ $this->getId() }}').get('module');
                            $select.val(currentValue).trigger('change.select2');
                        }
                    }, 300);
                }, 100);
            });
        });
    });
</script>
@endpush
