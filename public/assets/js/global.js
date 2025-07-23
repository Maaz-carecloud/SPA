/**
 * BGS Portal - Global JavaScript Functions
 * Contains all global functions for toast notifications, modal management, and app initialization
 */

// Global variables - use window object for better accessibility
window.notyf = null;

/**
 * Initialize Notyf notification system
 */
function initializeNotyf() {
    if (typeof Notyf !== 'undefined') {
        window.notyf = new Notyf({
            duration: 5000, // 5 seconds
            position: {
                x: 'right',
                y: 'top',
            },
            dismissible: true, // Allow manual dismissal
            ripple: true
        });
        // console.log('Notyf initialized successfully');
    } else {
        console.error('Notyf library not loaded');
    }
}

/**
 * Global notification handlers
 */
window.__notyfErrorHandler = function(event) {
    if (window.notyf) {
        const message = event.detail.message || 'An error occurred';
        window.notyf.error(message);
        console.log('Error notification shown:', message);
    } else {
        console.error('Notyf not initialized for error:', event.detail);
    }
};

window.__notyfSuccessHandler = function(event) {
    if (window.notyf) {
        const message = event.detail.message || 'Operation completed successfully';
        window.notyf.success(message);
        // console.log('Success notification shown:', message);
    } else {
        console.error('Notyf not initialized for success:', event.detail);
    }
};

/**
 * Initialize global event listeners for notifications
 */
function initializeNotificationListeners() {
    // Remove any existing event listeners to prevent duplicates
    document.removeEventListener('error', window.__notyfErrorHandler);
    document.removeEventListener('success', window.__notyfSuccessHandler);
    
    // Listen directly to Livewire events
    if (typeof Livewire !== 'undefined') {
        // Only attach listeners once using a flag
        if (!window.__livewireListenersAttached) {
            window.__livewireListenersAttached = true;
            
            // Create listeners
            window.__livewireSuccessListener = function(data) {
                if (window.notyf) {
                    const message = data[0]?.message || data?.message || 'Operation completed successfully';
                    window.notyf.success(message);
                    // console.log('Success notification shown:', message);
                }
            };
            
            window.__livewireErrorListener = function(data) {
                if (window.notyf) {
                    const message = data[0]?.message || data?.message || 'An error occurred';
                    window.notyf.error(message);
                    console.log('Error notification shown:', message);
                }
            };
            
            // Add Livewire listeners
            Livewire.on('success', window.__livewireSuccessListener);
            Livewire.on('error', window.__livewireErrorListener);
            
            // console.log('Livewire notification listeners attached');
        } else {
            // console.log('Livewire listeners already attached, skipping...');
        }
    } else {
        console.warn('Livewire not available, falling back to custom events');
        // Fallback to custom events
        document.addEventListener('error', window.__notyfErrorHandler);
        document.addEventListener('success', window.__notyfSuccessHandler);
    }
}

/**
 * Initialize global modal focus management to prevent aria-hidden warnings
 */
function initializeModalFocusManagement() {
    // Add event listeners to all modals for proper focus management
    document.addEventListener('hide.bs.modal', function(event) {
        const modal = event.target;
        // Remove focus from any element inside the modal before it's hidden
        const focusedElement = modal.querySelector(':focus');
        if (focusedElement) {
            focusedElement.blur();
        }
    });
    
    // Ensure focus returns to a safe place when modal is completely hidden
    document.addEventListener('hidden.bs.modal', function(event) {
        // Return focus to the body or a main content area
        if (document.activeElement && document.activeElement.tagName === 'BODY') {
            return; // Focus is already on body, we're good
        }
        // If focus is still trapped somewhere, move it to body
        document.body.focus();
    });
}

/**
 * Global modal helper functions
 */
window.modalHelper = {
    show: function(modalId, delay = 100) {
        setTimeout(() => {
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.show();
            }
        }, delay);
    },
    
    hide: function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            // Focus management is handled by global event listeners
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.hide();
        }
    },
    
    // Function to safely call Livewire methods with error handling
    callLivewire: function(componentId, method, params = [], delay = 100) {
        setTimeout(() => {
            try {
                const livewireComponent = Livewire.find(componentId);
                if (livewireComponent) {
                    if (params.length > 0) {
                        livewireComponent.call(method, ...params);
                    } else {
                        livewireComponent.call(method);
                    }
                }
            } catch (error) {
                console.error('Error calling Livewire method:', method, error);
            }
        }, delay);
    }
};

/**
 * Initialize page loading state
 */
function initializePageLoading() {
    setTimeout(function() {
        // Remove loading class from body
        document.body.classList.remove('loading');
    }, 800);
}

/**
 * Initialize DataTable for activity index page
 */
function initializeDataTable() {
    if (typeof $ !== 'undefined' && $.fn.dataTable) {
        $('.data-table').dataTable({
            stateSave: true,
            fixedHeader: true,
            buttons: [
                'copy', 'excel', 'pdf'
            ],
            layout: {
                topStart: 'buttons'
            }
        });
    }
}

/**
 * Main initialization function - called when DOM is ready
 */
function initializeGlobalScripts() {
    // console.log('Initializing global scripts...');
    initializeNotyf();
    initializeNotificationListeners();
    initializeModalFocusManagement();
    initializePageLoading();
    initializeDataTable();
    // console.log('Global scripts initialized');
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeGlobalScripts);

// Fallback initialization in case DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeGlobalScripts);
} else {
    // DOM already loaded, initialize immediately
    initializeGlobalScripts();
}

// Re-initialize on Livewire navigation (for SPA-like behavior)
document.addEventListener('livewire:navigated', function() {
    // console.log('Livewire navigated, re-initializing...');
    // Re-initialize Notyf in case it was lost
    if (!window.notyf) {
        initializeNotyf();
    }
    // Only re-initialize listeners if they weren't already attached
    if (!window.__livewireListenersAttached) {
        initializeNotificationListeners();
    }
    initializeDataTable();
});
