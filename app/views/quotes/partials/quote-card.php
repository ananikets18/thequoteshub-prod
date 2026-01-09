<div class="quote_item p-4 bg-white border border-2 rounded-lg mx-auto border-gray-300 shadow-lg hover:shadow-xl transition duration-300">
  
  <!-- Card Header -->
  <div class="flex items-center mb-4">
    <a href="<?php echo $baseUrl; ?><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['user__name'])); ?>">
      <?php $imageSrc = getUserImageSrc($quote['user_img'], $baseUrl); ?>
      <div class="relative inline-block">
        <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" 
             alt="User" 
             class="user-image w-12 h-12 rounded-full mr-4 object-cover border-2" />
        <?php if ($quote['user_status']): ?>
          <span title="Active" class="absolute bottom-0 left-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
        <?php endif; ?>
      </div>
    </a>
    <div>
      <a class="capitalize font-medium text-sm md:text-md lg:text-normal" 
         href="<?php echo $baseUrl; ?><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['user__name'])); ?>">
        <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['user_name'])); ?>
      </a>
      <p class="text-xs md:text-sm text-gray-400">
        <?php echo convertUtcToIstHuman($quote['created_at']); ?>
      </p>
    </div>
  </div>

  <!-- Quote Box -->
  <a href="<?php echo $baseUrl; ?>quote/<?php echo urlencode($quote['id']); ?>" 
     class="block mb-4 rounded-lg bg-emerald-100">
    <div class="quote-box mb-4 py-3 px-3 lg:py-5 lg:px-6">
      <p class="text-lg font-semibold italic text-gray-800 mb-2" id="quoteText-<?php echo $quote['id']; ?>">
        " <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?> "
      </p>
      <p class="text-sm text-gray-500 capitalize" id="authorName-<?php echo $quote['id']; ?>">
        - <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?>
      </p>
    </div>
  </a>

  <!-- Card Footer -->
  <div class="flex items-center justify-between text-gray-600">
    <div class="flex space-x-4">
      
      <!-- Like Button -->
      <button title="Like" 
              data-quote-id="<?php echo $quote['id']; ?>" 
              class="flex items-center space-x-2 text-xs text-red-500 transition hover:text-red-600 like-button">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
        </svg>
        <span class="like-count text-xs font-semibold">
          <?php echo isset($likeCounts[$quote['id']]) ? $likeCounts[$quote['id']] : 0; ?> Likes
        </span>
        <div class="heart-container" id="heartContainer"></div>
      </button>

      <!-- Share Button -->
      <button title="Share" 
              data-quote="<?php echo $quote['id']; ?>" 
              class="flex items-center space-x-2 text-xs text-yellow-500 transition hover:text-yellow-600 shareButton">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
          <path fill-rule="evenodd" d="M15.75 4.5a3 3 0 1 1 .825 2.066l-8.421 4.679a3.002 3.002 0 0 1 0 1.51l8.421 4.679a3 3 0 1 1-.729 1.31l-8.421-4.678a3 3 0 1 1 0-4.132l8.421-4.679a3 3 0 0 1-.096-.755Z" clip-rule="evenodd"/>
        </svg>
        <span class="text-xs font-semibold">Share</span>
      </button>
    </div>

    <!-- Save Button -->
    <button title="Save" 
            data-quote-id="<?php echo $quote['id']; ?>" 
            class="flex items-center space-x-2 text-sm text-blue-500 hover:text-blue-600 transition save-button">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
        <path fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z" clip-rule="evenodd"/>
      </svg>
      <span class="save-count font-semibold text-blue-500 text-xs">
        <?php echo isset($saveCounts[$quote['id']]) ? $saveCounts[$quote['id']] : 0; ?> Saves
      </span>
    </button>
  </div>
</div>
