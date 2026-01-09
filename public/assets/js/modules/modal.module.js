/**
 * Modal Module
 * Handles all modal functionality (login, share, etc.)
 */

const ModalModule = (function () {
    'use strict';

    let $loginModal;
    let $shareModal;

    /**
     * Initialize modal functionality
     */
    function init() {
        $loginModal = $("#loginModal");
        $shareModal = $(".shareModal");
        bindEvents();
    }

    /**
     * Bind modal events
     */
    function bindEvents() {
        // Login modal events
        $(".modal-close, #loginModal").on("click", handleLoginModalClick);

        // Share modal events
        $(".closeModal").on("click", hideShareModal);
        $(".shareButton").on("click", showShareModal);

        // Keyboard events
        $(document).on("keyup", handleEscapeKey);

        // Social share events
        $("#facebookShare").on("click", handleFacebookShare);
        $("#twitterShare").on("click", handleTwitterShare);
        $("#linkedinShare").on("click", handleLinkedinShare);
    }

    /**
     * Show login modal
     */
    function showLoginModal() {
        $loginModal.removeClass("hidden");
    }

    /**
     * Hide login modal
     */
    function hideLoginModal() {
        $loginModal.addClass("hidden");
    }

    /**
     * Handle login modal click
     */
    function handleLoginModalClick(e) {
        if (e.target === this) {
            hideLoginModal();
        }
    }

    /**
     * Show share modal
     */
    function showShareModal() {
        $shareModal.removeClass("hidden");
    }

    /**
     * Hide share modal
     */
    function hideShareModal() {
        $shareModal.addClass("hidden");
    }

    /**
     * Handle escape key press
     */
    function handleEscapeKey(e) {
        if (e.key === "Escape") {
            hideLoginModal();
            hideShareModal();
        }
    }

    /**
     * Handle Facebook share
     */
    function handleFacebookShare(e) {
        e.preventDefault();
        const quoteText = encodeURIComponent(this.getAttribute('data-quote'));
        const authorName = encodeURIComponent(this.getAttribute('data-author'));
        const pageUrl = encodeURIComponent(window.location.href);
        const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${pageUrl}&quote=${quoteText}%20-%20${authorName}`;

        openShareWindow(facebookUrl);
    }

    /**
     * Handle Twitter share
     */
    function handleTwitterShare(e) {
        e.preventDefault();
        const quoteText = encodeURIComponent(this.getAttribute('data-quote') || '');
        const authorName = encodeURIComponent(this.getAttribute('data-author') || '');
        const twitterUrl = `https://twitter.com/intent/tweet?text=${quoteText}%20-%20${authorName}`;

        openShareWindow(twitterUrl);
    }

    /**
     * Handle LinkedIn share
     */
    function handleLinkedinShare(e) {
        e.preventDefault();
        const pageUrl = encodeURIComponent(window.location.href);
        const linkedinUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${pageUrl}`;

        openShareWindow(linkedinUrl);
    }

    /**
     * Open share window
     */
    function openShareWindow(url) {
        window.open(url, '_blank', 'width=600,height=400');
    }

    // Public API
    return {
        init: init,
        showLoginModal: showLoginModal,
        hideLoginModal: hideLoginModal,
        showShareModal: showShareModal,
        hideShareModal: hideShareModal
    };
})();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModalModule;
}
