/**
 * Dashboard Visibility Fix Script
 * 
 * This script forcefully ensures the dashboard content is visible
 * after login, overriding any CSS or JS that might hide it.
 * It runs immediately and doesn't wait for any events.
 */

// Self-executing function to run immediately
(function() {
    // Direct DOM manipulation to ensure dashboard visibility
    function forceDashboardVisibility() {
        // List of selectors to target for visibility fixes
        const elementSelectors = [
            '#appWrapper',
            '#guestWrapper',
            '.Dashboard', 
            '.content-wrapper',
            '.dashoard-card',
            '#contentWrapper',
            '#dashboardContent',
            '#dashboardRoot'
        ];
        
        // Force visibility on all these elements
        elementSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            if (elements.length > 0) {
                elements.forEach(el => {
                    // Remove any classes that might hide the element
                    el.classList.remove('preload-hidden');
                    
                    // Force inline styles to ensure visibility
                    el.style.opacity = '1';
                    el.style.visibility = 'visible';
                    el.style.display = el.tagName.toLowerCase() === 'div' ? 'block' : '';
                    
                    console.log('Dashboard fix applied to:', selector);
                });
            }
        });
        
        // Remove body class that might disable scrolling
        document.body.classList.remove('preloading');
        
        // Hide any preloaders that might still be visible
        const preloaders = document.querySelectorAll('.bgs-preloader, .loading-spinner');
        preloaders.forEach(preloader => {
            preloader.classList.add('fade-out');
            preloader.style.display = 'none';
        });
    }
    
    // Run immediately
    forceDashboardVisibility();
    
    // Also run after a short delay to catch any late DOM changes
    setTimeout(forceDashboardVisibility, 200);
    setTimeout(forceDashboardVisibility, 500);
    setTimeout(forceDashboardVisibility, 1000);
    
    // Also run after window load event
    window.addEventListener('load', function() {
        forceDashboardVisibility();
    });
    
    // Run after any Livewire navigations
    document.addEventListener('livewire:navigated', function() {
        forceDashboardVisibility();
    });
})();
