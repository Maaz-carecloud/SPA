<div>
    <form wire:submit.prevent="updateSection" id="edit-section-form">
        <x-form.select2 
            name="class_id"
            label="Class"
            wire:model="class_id"
            :options="$classes->pluck('name', 'id')->toArray()"
            option-label="name"
            option-value="id"
            placeholder="Select Class..."
            id="editSectionClassSelect"
            wire:ignore
            required />
        
        <x-form.input 
            name="name" 
            label="Section Name"
            wire:model="name" 
            placeholder="Enter section name"
            required />
        
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
    </form>

    <script>
        // Function to ensure modal cleanup
        function cleanupModalBackdrop() {
            try {
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
            } catch (error) {
                console.error('Error during modal cleanup:', error);
                // Force remove common backdrop elements as fallback
                try {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                } catch (fallbackError) {
                    console.error('Fallback cleanup also failed:', fallbackError);
                }
            }
        }

        // Function to register edit section event listeners
        function registerSectionEditListeners() {
            // Clear any existing listeners for this component
            if (window.sectionEditListenersRegistered) {
                return;
            }

            Livewire.on('submit-edit-form', () => {
                @this.call('updateSection');
            });
            
            // Listen for section data loaded event
            Livewire.on('section-data-loaded', () => {
                // Wait a bit for Livewire to update the DOM, then trigger select2 reinit
                setTimeout(() => {
                    // Trigger livewire:updated event to make sure select2 component reinitializes
                    document.dispatchEvent(new CustomEvent('livewire:updated'));
                    
                    // Also manually trigger select2 update if needed
                    const $select = $('#editSectionClassSelect');
                    if ($select.length && $select.hasClass('select2-hidden-accessible')) {
                        // Update the selected value
                        $select.val(@this.class_id).trigger('change.select2');
                    }
                }, 200);
            });
            
            // Listen for the load-section-for-edit event
            Livewire.on('load-section-for-edit', (event) => {
                // Directly call the loadSection method with the sectionId
                if (event.sectionId) {
                    @this.call('loadSection', event.sectionId);
                }
            });

            // Listen for modal closing events and clean up backdrop
            Livewire.on('sectionUpdated', () => {
                console.log('Section updated event received');
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

            Livewire.on('close-editSection', () => {
                console.log('Close edit section event received');
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

            window.sectionEditListenersRegistered = true;
        }

        // Register on Livewire init and navigation
        document.addEventListener('livewire:init', registerSectionEditListeners);
        document.addEventListener('livewire:navigated', function() {
            // Force cleanup any remaining modal backdrops and body styles
            cleanupModalBackdrop();
            
            window.sectionEditListenersRegistered = false;
            registerSectionEditListeners();
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
