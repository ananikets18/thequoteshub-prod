/**
 * Quotes Index Page - Main Application
 * Initializes all modules for the quotes index page
 */

(function () {
  'use strict';

  /**
   * Initialize application
   */
  function initApp() {
    // Initialize all modules when DOM is ready
    $(document).ready(function () {
      // Initialize scroll functionality
      if (typeof ScrollModule !== 'undefined') {
        ScrollModule.init();
      }

      // Initialize like functionality
      if (typeof LikeModule !== 'undefined') {
        LikeModule.init();
      }

      // Initialize save functionality
      if (typeof SaveModule !== 'undefined') {
        SaveModule.init();
      }

      // Initialize modal functionality
      if (typeof ModalModule !== 'undefined') {
        ModalModule.init();
      }

      // Initialize notification functionality
      if (typeof NotificationModule !== 'undefined') {
        NotificationModule.init();
      }

      console.log('Quotes Index App initialized successfully');
    });
  }

  // Start the application
  initApp();
})();
