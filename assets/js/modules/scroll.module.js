/**
 * Scroll Module
 * Handles scroll-related functionality
 */

const ScrollModule = (function () {
    'use strict';

    let $goTopBtn;

    /**
     * Initialize scroll functionality
     */
    function init() {
        $goTopBtn = $("#goTopBtn");
        bindEvents();
    }

    /**
     * Bind scroll events
     */
    function bindEvents() {
        $(window).on('scroll', handleScroll);
        $goTopBtn.on('click', scrollToTop);
    }

    /**
     * Handle scroll event
     */
    function handleScroll() {
        if ($(window).scrollTop() > 100) {
            showButton();
        } else {
            hideButton();
        }
    }

    /**
     * Show go to top button
     */
    function showButton() {
        $goTopBtn.removeClass("opacity-0 pointer-events-none")
            .addClass("opacity-100 pointer-events-auto");
    }

    /**
     * Hide go to top button
     */
    function hideButton() {
        $goTopBtn.addClass("opacity-0 pointer-events-none")
            .removeClass("opacity-100 pointer-events-auto");
    }

    /**
     * Scroll to top of page
     */
    function scrollToTop() {
        $("html, body").animate({ scrollTop: 0 }, 600);
    }

    // Public API
    return {
        init: init
    };
})();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ScrollModule;
}
