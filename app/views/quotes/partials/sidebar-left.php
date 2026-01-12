<aside class="flex-none w-full sm:w-1/3 lg:w-1/4 px-4 py-0 space-y-4">

  <?php if ($isLoggedIn): ?>
    
    <!-- User Profile Card -->
    <?php if (isset($userDetails)): ?>
      <div class="profile_card_wrapper bg-white p-6 border border-gray-300 rounded-lg flex flex-col space-y-12">
        <div class="bg_image_color bg-emerald-200 w-full h-16 relative border border-1 border-emerald-400 rounded-t-lg">
          <div class="user_profile_icon absolute top-4 left-6">
            <?php $imageSrc = getUserImageSrc($userDetails['user_img'], $baseUrl); ?>
            <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="<?php echo safeOutput($userDetails['name']); ?>"
                 class="w-20 h-20 rounded-full object-cover border" />
          </div>
        </div>
        
        <div class="user_info pl-2">
          <div class="flex flex-col space-y-0.5">
            <div class="flex flex-row items-center justify-between">
              <span class="font-semibold text-xl user_name capitalize">
                <?php echo safeOutput($userDetails['name']); ?>
              </span>
              
              <!-- Joined Date for Tablet & Mobile -->
              <div class="flex items-center space-x-1 text-xs md:text-sm text-gray-500 lg:hidden mt-1">
                <i class="fas fa-calendar-alt"></i>
                <span class="font-normal text-xs md:text-sm">
                  <?php echo formatDate(htmlspecialchars($userDetails['created_at'])); ?>
                </span>
              </div>
            </div>
        
            <span class="text-sm text-gray-600">
              @<?php echo safeOutput($userDetails['username']); ?>
            </span>
          </div>

          <!-- Joined Date for Desktop -->
          <div class="flex items-center space-x-1 text-sm text-gray-400 hidden lg:flex mt-1">
            <i class="fas fa-calendar-alt text-xs"></i>
            <span class="font-normal text-xs text-gray-600">
              <?php echo formatDate(htmlspecialchars($userDetails['created_at'])); ?>
            </span>
          </div>
          
          <p class="user_bio text-gray-700 font-normal text-sm mt-2">
            <?php echo safeOutput($userDetails['bio']); ?>
          </p>
        </div>
      </div>
    <?php endif; ?>

    <!-- User Stats Card -->
    <div class="user_profile_quotes_interaction bg-white p-6 border border-gray-300 rounded-lg profile_card_wrapper">
      <div class="flex flex-col space-y-5 w-full text-sm font-medium text-gray-600">
        <div class="w-full flex items-center justify-between hover:text-emerald-600">
          <label for="quotes_created">
            <span class="font-medium">Quotes Created</span>
          </label>
          <span><?php echo isset($quoteCount) ? $quoteCount : ''; ?></span>
        </div>
        
        <a href="<?php echo url('saved-quotes'); ?>" class="hover:text-blue-600 flex items-center justify-between space-x-2">
          <div class="flex items-center space-x-2.5">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z" clip-rule="evenodd" />
              </svg>
            </span>
            <span>Saved Quotes</span>
          </div>
          <span><?php echo isset($saveCount) ? $saveCount : ''; ?></span>
        </a>
        
        <a href="<?php echo url('liked-quotes'); ?>" class="hover:text-red-600 flex items-center justify-between space-x-2">
          <div class="flex items-center space-x-2.5">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
              </svg>
            </span>
            <span>Liked Quotes</span>
          </div>
          <span><?php echo isset($likeCount) ? $likeCount : ''; ?></span>
        </a>
      </div>
    </div>

  <?php else: ?>
    
    <!-- Guest Profile Card -->
    <div class="profile_card_wrapper bg-white p-6 border border-gray-300 rounded-lg hidden md:flex flex-col items-center space-y-6">
      <div class="border border-1 border-gray-300 w-full h-16 bg-yellow-50 relative border rounded-t-lg">
        <div class="user_profile_icon absolute top-4 left-6">
          <img class="w-20 h-20 rounded-full object-cover border shadow-md"
               src="<?php echo $baseUrl; ?>public/uploads/authors_images/placeholder.png" 
               alt="default_user_profile" />
        </div>
      </div>

      <div class="user_info text-center">
        <span class="font-medium text-gray-800">Guest</span>
        <p class="text-gray-700 font-normal text-sm mt-1">
          Please log in to view your profile.
        </p>
      </div>

      <div class="flex space-x-4">
        <a href="login" class="px-4 py-2 md:text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-600 transition">
          Log In
        </a>
        <a href="register" class="px-4 py-2 md:text-sm text-white bg-emerald-600 rounded-lg hover:bg-green-600 transition">
          Sign Up
        </a>
      </div>
    </div>
    
  <?php endif; ?>

</aside>
