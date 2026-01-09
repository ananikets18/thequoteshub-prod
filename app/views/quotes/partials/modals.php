<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
    <!-- Modal Header -->
    <div class="flex justify-between items-center border-b pb-3">
      <h3 class="text-xl md:text-2xl font-semibold text-gray-700">Login Required</h3>
    </div>

    <!-- Modal Body -->
    <div class="mt-4">
      <p class="text-sm md:text-base lg:text-lg text-gray-600">You need to log in to perform this action.</p>
    </div>

    <!-- Modal Footer -->
    <div class="mt-6 flex justify-end space-x-3">
      <a href="<?php echo url('login'); ?>"
         class="px-4 py-2 text-sm md:text-base lg:text-lg bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Log In
      </a>
      <button class="modal-close px-4 py-2 text-sm md:text-base lg:text-lg bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
        Close
      </button>
    </div>
  </div>
</div>

<!-- Share Modal -->
<div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden shareModal">
  <div class="bg-white p-6 rounded-lg shadow-lg w-80 relative">
    <!-- Close Button -->
    <button class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-lg focus:outline-none closeModal">
      &times;
    </button>

    <!-- Modal Content -->
    <h2 class="text-lg font-semibold mb-4 text-center">Share this Quote</h2>
    <div class="flex flex-col space-y-2 mb-4">
      
      <!-- Facebook -->
      <a href="#"
         class="flex items-center justify-center bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
         target="_blank" 
         id="facebookShare"
         data-quote="<?php echo isset($quote) ? decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])) : ''; ?>"
         data-author="<?php echo isset($quote) ? decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])) : ''; ?>">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2.4c-5.31 0-9.6 4.29-9.6 9.6 0 4.86 3.57 8.88 8.27 9.8v-6.93h-2.48v-2.48h2.48v-1.83c0-2.44 1.48-3.75 3.63-3.75 1.03 0 2.06.07 2.06.07v2.27h-1.16c-1.14 0-1.5.72-1.5 1.46v1.76h2.59l-.41 2.48h-2.18v6.93c4.71-.92 8.27-4.94 8.27-9.8 0-5.31-4.29-9.6-9.6-9.6z" />
        </svg>
        Facebook
      </a>
      
      <!-- Twitter -->
      <a href="#" 
         class="flex items-center justify-center bg-blue-400 text-white py-2 rounded hover:bg-blue-500"
         target="_blank" 
         id="twitterShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path d="M23 3a10.9 10.9 0 01-3.14.87A4.92 4.92 0 0022.4 2.1a10.9 10.9 0 01-3.14 1.2A4.9 4.9 0 0016.6 1a4.92 4.92 0 00-4.9 4.9c0 .4.05.79.1 1.17A13.9 13.9 0 011.67 2.29 4.9 4.9 0 003 8.21a4.84 4.84 0 01-2.22-.6v.06c0 2.36 1.68 4.33 3.9 4.78a4.91 4.91 0 01-2.22.08c.62 1.94 2.42 3.35 4.56 3.39A9.86 9.86 0 010 17.4a13.9 13.9 0 007.55 2.2c9.05 0 14-7.5 14-14 0-.21-.01-.42-.02-.62A10.02 10.02 0 0023 3z" />
        </svg>
        Twitter
      </a>
      
      <!-- LinkedIn -->
      <a href="#" 
         class="flex items-center justify-center bg-red-500 text-white py-2 rounded hover:bg-red-600"
         target="_blank" 
         id="linkedinShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path d="M4.98 3.5c-1.9 0-3.4 1.5-3.4 3.4 0 1.9 1.5 3.4 3.4 3.4 1.9 0 3.4-1.5 3.4-3.4 0-1.9-1.5-3.4-3.4-3.4zm-1.2 5.9h2.4v9.5H3.78v-9.5zM15.1 11.8c-.4-1.6-1.8-2.8-3.5-2.8-2 0-3.4 1.2-3.4 3v.1c0 1.9 1.3 3.2 3.2 3.2 1.7 0 3.3-1.2 3.3-3v-.1zm1.8-5.3h2.4v2.7h.1c.3-.6 1-1.4 2.1-1.4 1.5 0 2.7 1.3 2.7 3.1v5.5h-2.4v-5.1c0-1.2-.7-1.9-1.6-1.9-1 0-1.8.7-2.1 1.4-.1.2-.1.6-.1.9v5.7h-2.4v-6.5c0-.5-.1-1.1-.4-1.6-.6-1.2-1.8-1.9-3.2-1.9-2.2 0-4 1.8-4 4v6.5H1.2v-6.5c0-2.6 2.1-4.7 4.7-4.7 2.6 0 4.7 2.1 4.7 4.7v1.1c.9-1.6 2.6-2.7 4.7-2.7 3 0 5.5 2.5 5.5 5.6v6.5h-2.4v-6.5z" />
        </svg>
        LinkedIn
      </a>
      
    </div>
  </div>
</div>
