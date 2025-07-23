<div>
    <form wire:submit.prevent="updateClass" id="edit-class-form">
        <div class="mb-3">
            <label for="edit-name" class="form-label">Class Name <span class="text-danger">*</span></label>
            <input 
                type="text" 
                id="edit-name" 
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
            <label for="edit-class_numeric" class="form-label">Class Numeric <span class="text-danger">*</span></label>
            <input 
                type="number" 
                id="edit-class_numeric" 
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
            id="edit-teacher_id"
            label="Class Teacher"
            icon="fas fa-chalkboard-teacher"
            wire:model.lazy="teacher_id"
            placeholder="Select Teacher"
            :options="$teachers->pluck('user.name', 'id')->toArray()"
            :value="$teacher_id"
            required
        />

        <div class="text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Fields marked with <span class="text-danger">*</span> are required.
        </div>
    </form>        <script>
            // Function to ensure modal cleanup
            function cleanupModalBackdrop() {
                console.log('Cleaning up modal backdrop...');
                
                // Remove all modal backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                console.log('Found backdrops:', backdrops.length);
                backdrops.forEach(backdrop => {
                    console.log('Removing backdrop:', backdrop);
                    backdrop.remove();
                });
                
                // Reset body styles
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                document.body.style.marginRight = '';
                
                // Reset modal states
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                    modal.removeAttribute('aria-modal');
                    modal.removeAttribute('role');
                });
                
                // Force remove any remaining overlay elements
                document.querySelectorAll('[class*="backdrop"], [class*="overlay"]').forEach(el => {
                    if (el.style.backgroundColor === 'rgba(0, 0, 0, 0.5)' || 
                        el.style.backgroundColor === 'black' || 
                        el.classList.contains('modal-backdrop')) {
                        console.log('Removing overlay element:', el);
                        el.remove();
                    }
                });
                
                console.log('Modal cleanup completed');
            }

            // Function to register edit class event listeners
            function registerClassEditListeners() {
                // Clear any existing listeners for this component
                if (window.classEditListenersRegistered) {
                    return;
                }

                Livewire.on('submit-edit-form', () => {
                    @this.call('updateClass');
                });
                
                // Listen for class data loaded event
                Livewire.on('class-data-loaded', () => {
                    // Wait a bit for Livewire to update the DOM, then trigger select2 reinit
                    setTimeout(() => {
                        // Trigger livewire:updated event to make sure select2 component reinitializes
                        document.dispatchEvent(new CustomEvent('livewire:updated'));
                        
                        // Also manually trigger select2 update if needed
                        const $select = $('#edit-teacher_id');
                        if ($select.length && $select.hasClass('select2-hidden-accessible')) {
                            // Update the selected value
                            $select.val(@this.teacher_id).trigger('change.select2');
                        }
                    }, 200);
                });
                
                // Listen for the load-class-for-edit event
                Livewire.on('load-class-for-edit', (event) => {
                    // Directly call the loadClass method with the classId
                    if (event.classId) {
                        @this.call('loadClass', event.classId);
                    }
                });

                // Listen for modal closing events and clean up backdrop
                Livewire.on('classUpdated', () => {
                    console.log('Class updated event received');
                    // Immediate cleanup without timeout
                    cleanupModalBackdrop();
                    // Additional cleanup after animation
                    setTimeout(() => {
                        cleanupModalBackdrop();
                    }, 100);
                    setTimeout(() => {
                        cleanupModalBackdrop();
                    }, 300);
                });

                Livewire.on('close-editClass', () => {
                    console.log('Close edit class event received');
                    // Immediate cleanup without timeout
                    cleanupModalBackdrop();
                    // Additional cleanup after animation
                    setTimeout(() => {
                        cleanupModalBackdrop();
                    }, 100);
                    setTimeout(() => {
                        cleanupModalBackdrop();
                    }, 300);
                });

                window.classEditListenersRegistered = true;
            }

            // Register on Livewire init and navigation
            document.addEventListener('livewire:init', registerClassEditListeners);
            document.addEventListener('livewire:navigated', function() {
                // Force cleanup any remaining modal backdrops and body styles
                cleanupModalBackdrop();
                
                window.classEditListenersRegistered = false;
                registerClassEditListeners();
            });

            // Cleanup modals before navigation
            document.addEventListener('livewire:navigate', function() {
                cleanupModalBackdrop();
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                cleanupModalBackdrop();
            });

            // Make cleanup function globally available for manual debugging
            window.forceCleanupModals = cleanupModalBackdrop;
        </script>
</div>
