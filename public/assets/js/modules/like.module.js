/**
 * Like Module
 * Handles quote liking functionality with heart animation
 */

const LikeModule = (function () {
    'use strict';

    const config = window.APP_CONFIG || {};

    /**
     * Initialize like functionality
     */
    function init() {
        bindEvents();
    }

    /**
     * Bind like button events
     */
    function bindEvents() {
        $(document).on("click", ".like-button", handleLikeClick);
    }

    /**
     * Handle like button click
     */
    function handleLikeClick(event) {
        const $button = $(this);
        const quoteId = $button.data("quote-id");

        triggerBubbleEffect(event);

        if (!config.isLoggedIn) {
            ModalModule.showLoginModal();
            return;
        }

        performLikeAction(quoteId, $button);
    }

    /**
     * Trigger heart bubble animation
     */
    function triggerBubbleEffect(event) {
        const $heartContainer = $("#heartContainer");
        $heartContainer.empty();

        for (let i = 0; i < 5; i++) {
            const $heart = $("<div class='heart'>❤️</div>");
            const buttonRect = event.currentTarget.getBoundingClientRect();
            const leftPosition = buttonRect.left + buttonRect.width / 2 + Math.random() * 60 - 30;
            const topPosition = buttonRect.top + window.scrollY;

            $heart.css({
                left: leftPosition + 'px',
                top: topPosition + 'px'
            });

            $heartContainer.append($heart);
        }

        setTimeout(() => {
            $heartContainer.empty();
        }, 1200);
    }

    /**
     * Perform AJAX like action
     */
    function performLikeAction(quoteId, $button) {
        $.ajax({
            url: config.baseUrl + "quote/" + quoteId + "/like",
            method: "POST",
            data: { 
                quote_id: quoteId,
                csrf_token: config.csrfToken
            },
            dataType: "json",
            success: function (response) {
                handleLikeSuccess(response, $button);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                handleLikeError(textStatus, errorThrown);
            }
        });
    }

    /**
     * Handle successful like response
     */
    function handleLikeSuccess(response, $button) {
        if (response.error) {
            alert(response.error);
            return;
        }

        if (response.success) {
            const isLiked = response.liked;
            const $likeCount = $button.find(".like-count");

            if ($likeCount.length > 0) {
                $likeCount.text(response.like_count + " Likes");
            }

            $button.toggleClass("liked", isLiked);
        }
    }

    /**
     * Handle like error
     */
    function handleLikeError(textStatus, errorThrown) {
        console.error("Like Error:", textStatus, errorThrown);
        alert("An error occurred while processing your request.");
    }

    // Public API
    return {
        init: init,
        triggerBubbleEffect: triggerBubbleEffect
    };
})();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LikeModule;
}
