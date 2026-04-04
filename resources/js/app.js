import './bootstrap';

// Page Transition Logic
document.addEventListener('DOMContentLoaded', () => {
    // 2. Intercept clicks on local links to trigger the fade-out effect
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', e => {
            // Check if link is local, not a hash link to the same page, and not opening in a new tab
            if (
                link.hostname === window.location.hostname &&
                link.pathname !== window.location.pathname &&
                link.target !== '_blank' &&
                !link.hasAttribute('download') &&
                !e.ctrlKey && !e.metaKey && !e.shiftKey
            ) {
                e.preventDefault(); // Prevent immediate navigation
                
                // Add fade-out transition
                document.body.classList.add('page-transition-leave');
                
                // Wait for the animation to finish before actually navigating
                setTimeout(() => {
                    window.location.href = link.href;
                }, 280); // Corresponds to the 0.3s leave animation duration
            }
        });
    });
});
