<div>
    <form wire:submit.prevent="createClass" id="create-class-form">
        <div class="mb-3">
            <label for="create-name" class="form-label">Class Name <span class="text-danger">*</span></label>
            <input 
                type="text" 
                id="create-name" 
                wire:model.live="name" 
                class="form-control @error('name') is-invalid @enderror" 
                placeholder="Enter class name"
                autocomplete="off"
            >
            @error('name') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        <div class="mb-3">
            <label for="create-class_numeric" class="form-label">Class Numeric <span class="text-danger">*</span></label>
            <input 
                type="number" 
                id="create-class_numeric" 
                wire:model.live="class_numeric" 
                class="form-control @error('class_numeric') is-invalid @enderror" 
                placeholder="Enter class numeric (e.g., 1, 2, 3...)"
                min="1"
                autocomplete="off"
            >
            @error('class_numeric') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        <x-form.select2
            id="create-teacher_id"
            label="Class Teacher"
            wire:model="teacher_id"
            :options="$teachers->pluck('user.name', 'id')->toArray()"
            placeholder="Select Teacher"
            required="true"
            wire:ignore
        />

        <div class="text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Fields marked with <span class="text-danger">*</span> are required.
        </div>
    </form>

    <script>
        // Function to register create class event listeners
        function registerCreateClassListeners() {
            if (window.createClassListenersRegistered) {
                return;
            }

            Livewire.on('submit-create-form', () => {
                // Sync select2 value before submission
                const teacherSelect = $('#create-teacher_id');
                if (teacherSelect.length && teacherSelect.hasClass('select2-hidden-accessible')) {
                    const selectedValue = teacherSelect.val();
                    @this.set('teacher_id', selectedValue);
                }
                
                // Small delay to ensure the value is set
                setTimeout(() => {
                    @this.call('createClass');
                }, 100);
            });

            // Listen for modal being shown to reinitialize select2
            $(document).on('shown.bs.modal', '#createClassModal', function() {
                setTimeout(() => {
                    const $teacherSelect = $('#create-teacher_id');
                    if ($teacherSelect.length) {
                        // Destroy and reinitialize select2
                        if ($teacherSelect.hasClass('select2-hidden-accessible')) {
                            $teacherSelect.select2('destroy');
                        }
                        
                        $teacherSelect.select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            placeholder: 'Select Teacher',
                            allowClear: true,
                            dropdownParent: $('#createClassModal')
                        });

                        // Handle change events
                        $teacherSelect.off('change.createClass').on('change.createClass', function() {
                            const selectedValue = $(this).val();
                            @this.set('teacher_id', selectedValue);
                        });
                    }
                }, 200);
            });

            // Listen for class created event to reset the form
            Livewire.on('classCreated', () => {
                // Reset the select2 dropdown
                const $teacherSelect = $('#create-teacher_id');
                if ($teacherSelect.length && $teacherSelect.hasClass('select2-hidden-accessible')) {
                    $teacherSelect.val(null).trigger('change.select2');
                }
            });

            window.createClassListenersRegistered = true;
        }

        // Register on Livewire init and navigation
        document.addEventListener('livewire:init', registerCreateClassListeners);
        document.addEventListener('livewire:navigated', function() {
            window.createClassListenersRegistered = false;
            registerCreateClassListeners();
        });
    </script>
</div>
