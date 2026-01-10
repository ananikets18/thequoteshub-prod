<aside class="flex-none w-full sm:w-1/3 lg:w-1/4 px-4 py-0 space-y-4">
  
  <!-- Trending Categories -->
  <div class="p-4 border bg-white border-gray-300 rounded-lg h-fit trending_categories_container">
    <h2 class="text-sm md:text-md lg:text-xl font-medium text-gray-800 mb-4 flex items-center">
      <span>🔥</span>
      <span class="ml-2">Trending Categories</span>
    </h2>
    
    <div class="space-y-2">
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
          <div class="category-card bg-gray-200 p-2 md:p-4 rounded-md flex flex-row items-center justify-between">
            <div>
              <a class="hover:text-green-700 capitalize font-medium text-gray-700 text-sm md:text-md"
                 href="<?php echo $baseUrl ?>category/<?php echo urlencode(decodeAndCleanText($category['category_name'])); ?>">
                #<?= decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name'])); ?>
              </a>
              <p class="text-xs md:text-sm text-gray-500">
                <span><?= $category['quote_count']; ?></span> Quotes
              </p>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="mt-4 text-center">
          <a href="<?php echo $baseUrl ?>categories"
             class="hover:bg-emerald-700 p-2 rounded-md border hover:text-gray-100 text-green-700 text-xs md:text-sm font-medium">
            View More Categories
          </a>
        </div>

      <?php else: ?>
        <li>No categories available.</li>
      <?php endif; ?>
    </div>
  </div>
  
  <hr />

  <!-- Top Contributors -->
  <div class="top_contributors_container p-4 border bg-white border-gray-300 rounded-lg h-fit">
    <h2 class="text-md lg:text-xl font-medium mb-4 flex items-center">
      <span role="img" title="Top Contributors based on quotes created, likes and saves" aria-label="Top Contributors" class="mr-2">🏆</span>
      <span>Top Contributors</span>
    </h2>

    <div class="space-y-2">
      <?php foreach ($topUsers as $index => $user): ?>
        <div class="user-card flex flex-row items-center justify-between space-x-3">
          
          <!-- Profile Image -->
          <div class="flex-none w-12 h-12">
            <a href="<?php echo $baseUrl; ?><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($user['username'])); ?>">
              <?php if (!empty($user['user_img'])): ?>
                <img class="w-10 h-10 rounded-full object-cover border shadow-md" 
                     src="<?php echo $baseUrl ?>public/uploads/users/<?php echo htmlspecialchars($user['user_img']); ?>" 
                     alt="<?php echo htmlspecialchars($user['name']); ?>'s profile image" 
                     title="<?php echo htmlspecialchars($user['name']); ?>'s profile image" 
                     aria-label="<?php echo htmlspecialchars($user['name']); ?>'s profile image">
              <?php else: ?>
                <img src="<?php echo $baseUrl ?>public/uploads/authors_images/placeholder.png" 
                     alt="Default profile image" 
                     class="w-10 h-10 rounded-full object-cover border shadow-md" 
                     title="Default profile image" 
                     aria-label="Default profile image">
              <?php endif; ?>
            </a>
          </div>

          <!-- User Name and Verified Badge -->
          <div class="flex-grow flex items-center space-x-1">
            <a href="<?php echo $baseUrl; ?><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($user['username'])); ?>" 
               class="flex items-center space-x-1">
              <h3 class="text-sm md:text-md font-normal capitalize" title="<?php echo htmlspecialchars($user['name']); ?>">
                <?php echo htmlspecialchars($user['name']); ?>
              </h3>
              <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" title="Verified User">
                <circle cx="12" cy="12" r="12" fill="#00cc44"/>
                <path fill="white" d="M9.5 16.5l-3-3 1.41-1.41L9.5 13.67l6.09-6.09L17 8.5l-7.5 7.5z"/>
              </svg>
            </a>
          </div>

          <!-- Medal -->
          <div class="flex-none">
            <?php if ($index === 0): ?>
              <span class="text-yellow-500" title="Gold Medal" aria-label="Gold Medal" role="img">🥇</span>
            <?php elseif ($index === 1): ?>
              <span class="text-gray-400" title="Silver Medal" aria-label="Silver Medal" role="img">🥈</span>
            <?php elseif ($index === 2): ?>
              <span class="text-bronze-700" title="Bronze Medal" aria-label="Bronze Medal" role="img">🥉</span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-4 text-center">
      <a href="<?php echo $baseUrl ?>top-users" 
         class="view-more hover:bg-emerald-700 p-2 rounded-md border hover:text-gray-100 text-green-700 text-xs md:text-sm font-medium">
        View Top Users
      </a>
    </div>
  </div>

  <hr/>

  <!-- Footer Links -->
  <div class="footer_container bg-white border border-gray-300 rounded-lg p-4">
    <div class="text-xs md:text-sm font-normal">
      <ul class="space-y-2">
        <li><a href="<?php echo $baseUrl; ?>about" class="hover:text-emerald-600 transition">About Us</a></li>
        <li><a href="<?php echo $baseUrl; ?>guidelines" class="hover:text-emerald-600 transition">GuideLines</a></li>
        <li><a href="<?php echo $baseUrl; ?>blogs" class="hover:text-emerald-600 transition">Blog</a></li>
        <li><a href="<?php echo $baseUrl; ?>feedback" class="hover:text-emerald-600 transition">Feedback</a></li>
        <li><a href="<?php echo $baseUrl; ?>contact" class="hover:text-emerald-600 transition">Contact Us</a></li>
        <li><a href="<?php echo $baseUrl; ?>terms" class="hover:text-emerald-600 transition">Terms of Service</a></li>
        <li><a href="<?php echo $baseUrl; ?>disclaimer" class="hover:text-emerald-600 transition">Disclaimer</a></li>
        <li><a href="<?php echo $baseUrl; ?>privacy" class="hover:text-emerald-600 transition">Privacy Policy</a></li>
      </ul>
    </div>

    <a href="<?php echo getBaseUrl(); ?>" class="flex items-center space-x-2 mt-4">
      <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="Website Logo" class="h-6 w-auto" />
      <p class="text-gray-500 text-xs md:text-sm">&copy; <span><?php echo date("Y"); ?></span> Quoteshub</p>
    </a>
  </div>
  
</aside>
