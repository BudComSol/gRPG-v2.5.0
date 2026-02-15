/**
 * Cookie Consent Banner
 * Displays a cookie consent banner and manages user consent
 */
(function() {
    'use strict';

    // Check if user has already accepted cookies
    function hasAcceptedCookies() {
        return localStorage.getItem('cookieConsent') === 'accepted';
    }

    // Set cookie consent
    function acceptCookies() {
        localStorage.setItem('cookieConsent', 'accepted');
        hideBanner();
    }

    // Hide the banner
    function hideBanner() {
        var banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    }

    // Show the banner
    function showBanner() {
        var banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'block';
        }
    }

    // Initialize
    function init() {
        if (hasAcceptedCookies()) {
            hideBanner();
        } else {
            showBanner();
        }

        // Add click handler for accept button
        var acceptBtn = document.getElementById('cookie-consent-accept');
        if (acceptBtn) {
            acceptBtn.addEventListener('click', function(e) {
                e.preventDefault();
                acceptCookies();
            });
        }
    }

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
