<div>
    <x-form.select2 
        name="class_id"
        label="Class"
        wire:model="class_id"
        :options="$classes->pluck('name', 'id')->toArray()"
        option-label="name"
        option-value="id"
        placeholder="Select Class..."
        id="createSectionClassSelect"
        wire:ignore
        required="true" />
    
    <x-form.input 
        name="name" 
        label="Section Name"
        wire:model="name" 
        placeholder="Enter section name"
        required="true" />
    
    <x-form.input 
        name="category" 
        label="Category"
        wire:model="category" 
        placeholder="Enter category (optional)" />
    
    <x-form.input 
        name="capacity" 
        type="number"
        label="Capacity"
        wire:model="capacity" 
        placeholder="Enter capacity (optional)" />
    
    <x-form.input 
        name="note" 
        type="textarea"
        label="Note"
        wire:model="note" 
        placeholder="Enter note (optional)" />
</div>

@push('scripts')
<script>
    // Function to register create section event listeners
    function registerCreateSectionListeners() {
        if (window.createSectionListenersRegistered) {
            return;
        }

        Livewire.on('submit-create-form', () => {
            // Sync select2 value before submission
            const classSelect = $('#createSectionClassSelect');
            if (classSelect.length && classSelect.hasClass('select2-hidden-accessible')) {
                const selectedValue = classSelect.val();
                @this.set('class_id', selectedValue);
            }
            
            // Small delay to ensure the value is set
            setTimeout(() => {
                @this.call('createSection');
            }, 100);
        });

        // Listen for modal being shown to reinitialize select2
        $(document).on('shown.bs.modal', '#createSectionModal', function() {
            setTimeout(() => {
                const $classSelect = $('#createSectionClassSelect');
                if ($classSelect.length) {
                    // Destroy and reinitialize select2
                    if ($classSelect.hasClass('select2-hidden-accessible')) {
                        $classSelect.select2('destroy');
                    }
                    
                    $classSelect.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Select Class...',
                        allowClear: true,
                        dropdownParent: $('#createSectionModal')
                    });

                    // Handle change events
                    $classSelect.off('change.createSection').on('change.createSection', function() {
                        const selectedValue = $(this).val();
                        @this.set('class_id', selectedValue);
                    });
                }
            }, 200);
        });

        // Listen for section created event to reset the form
        Livewire.on('sectionCreated', () => {
            // Reset the select2 dropdown
            const $classSelect = $('#createSectionClassSelect');
            if ($classSelect.length && $classSelect.hasClass('select2-hidden-accessible')) {
                $classSelect.val(null).trigger('change.select2');
            }
        });

        window.createSectionListenersRegistered = true;
    }

    // Register on Livewire init and navigation
    document.addEventListener('livewire:init', registerCreateSectionListeners);
    document.addEventListener('livewire:navigated', function() {
        window.createSectionListenersRegistered = false;
        registerCreateSectionListeners();
    });
</script>
@endpush
