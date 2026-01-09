/**
 * Save Module
 * Handles quote saving functionality
 */

const SaveModule = (function () {
    'use strict';

    const config = window.APP_CONFIG || {};

    /**
     * Initialize save functionality
     */
    function init() {
        bindEvents();
    }

    /**
     * Bind save button events
     */
    function bindEvents() {
        $(document).on("click", ".save-button", handleSaveClick);
    }

    /**
     * Handle save button click
     */
    function handleSaveClick() {
        const $button = $(this);
        const quoteId = $button.data("quote-id");

        if (!config.isLoggedIn) {
            ModalModule.showLoginModal();
            return;
        }

        performSaveAction(quoteId, $button);
    }

    /**
     * Perform AJAX save action
     */
    function performSaveAction(quoteId, $button) {
        $.ajax({
            url: config.baseUrl + "quote/" + quoteId + "/save",
            method: "POST",
            data: { 
                quote_id: quoteId,
                csrf_token: config.csrfToken
            },
            dataType: "json",
            success: function (response) {
                handleSaveSuccess(response, $button);
            },
            error: function () {
                handleSaveError();
            }
        });
    }

    /**
     * Handle successful save response
     */
    function handleSaveSuccess(response, $button) {
        if (response.error) {
            alert(response.error);
            return;
        }

        if (response.success) {
            const isSaved = response.saved;
            const $saveCount = $button.find(".save-count");

            if ($saveCount.length > 0) {
                $saveCount.text(response.save_count + " saves");
            }

            $button.toggleClass("saved", isSaved);
        }
    }

    /**
     * Handle save error
     */
    function handleSaveError() {
        alert("An error occurred while processing your request.");
    }

    // Public API
    return {
        init: init
    };
})();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SaveModule;
}
