/**
 * Notification Module
 * Handles notification bar functionality
 */

const NotificationModule = (function () {
    'use strict';

    const STORAGE_KEY = 'notificationDismissed';
    let $notificationBar;

    /**
     * Initialize notification functionality
     */
    function init() {
        $notificationBar = $("#notification-bar");
        checkNotificationStatus();
        bindEvents();
    }

    /**
     * Bind notification events
     */
    function bindEvents() {
        // Add dismiss button handler if needed
        $notificationBar.find('.dismiss-btn').on('click', dismissNotification);
    }

    /**
     * Check notification status
     */
    function checkNotificationStatus() {
        if (!$notificationBar.length) {
            return;
        }

        if (isDismissed()) {
            hideNotification();
        } else {
            showNotification();
        }
    }

    /**
     * Check if notification was dismissed
     */
    function isDismissed() {
        return localStorage.getItem(STORAGE_KEY) === "true";
    }

    /**
     * Show notification
     */
    function showNotification() {
        $notificationBar.addClass("show");
    }

    /**
     * Hide notification
     */
    function hideNotification() {
        $notificationBar.removeClass("show");
    }

    /**
     * Dismiss notification
     */
    function dismissNotification() {
        localStorage.setItem(STORAGE_KEY, "true");
        hideNotification();
    }

    /**
     * Reset notification (for testing)
     */
    function resetNotification() {
        localStorage.removeItem(STORAGE_KEY);
        showNotification();
    }

    // Public API
    return {
        init: init,
        dismiss: dismissNotification,
        reset: resetNotification
    };
})();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NotificationModule;
}
