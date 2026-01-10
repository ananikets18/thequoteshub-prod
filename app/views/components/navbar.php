<?php
/**
 * Main Navigation Bar
 * Logic for notifications and user session should be handled before including this
 */
?>
<header id="mainHeader" class="bg-white shadow-lg w-full h-auto border-b-2 border-emerald-200/30 sticky top-0 z-50 transition-all duration-300">
  <!--Ad section-->
  <div data-banner-id="1434386"></div>
  <!----> 
  <nav class="container mx-auto px-4 py-3 md:py-4 flex flex-wrap items-center justify-between">
    <div class="flex items-center justify-between w-full lg:w-auto">
      <!-- Logo -->
      <div class="logo_wrapper">
        <a href="/public_html" class="transition-opacity duration-300 hover:opacity-80">
          <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="quoteshub-logo" class="h-8 md:h-10 animate-fade-in" />
        </a>
      </div>

      <!-- Mobile Right Section -->
      <div class="ml-auto lg:hidden flex items-center space-x-2">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="<?php echo $baseUrl; ?>register"
            class="px-3 py-1.5 md:px-4 md:py-2 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 hover:shadow-lg font-semibold transition-all duration-300 text-xs md:text-sm">
            Sign Up
          </a>
        <?php else: ?>
          <!-- Notifications (Mobile) -->
          <a href="<?php echo $baseUrl; ?>notifications"
             class="relative flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full hover:bg-emerald-500 hover:text-white hover:shadow-lg transition-all duration-300">
              <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
                  </path>
              </svg>
              <?php if ($unreadCount > 0): ?>
                  <div class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-white bg-red-500 rounded-full text-[10px] animate-pulse shadow-lg">
                      <?php echo ($unreadCount > 9) ? '9+' : $unreadCount; ?>
                  </div>
              <?php endif; ?>
          </a>
        <?php endif; ?>
        
        <!-- Hamburger Menu Button -->
        <button id="mobileMenuButton" type="button" 
                class="inline-flex items-center justify-center p-2 rounded-full text-gray-700 hover:bg-emerald-100 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all duration-300"
                aria-controls="mobileMenu" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <!-- Hamburger Icon -->
          <svg class="w-6 h-6" id="hamburgerIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
          <!-- Close Icon (hidden by default) -->
          <svg class="w-6 h-6 hidden" id="closeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
    
   
<a href="<?php echo $baseUrl; ?>authors" class="font-semibold block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-full transition-all duration-300">Authors</a>

<?php include __DIR__ . '/search_bar.php'; ?>
        
    <div class="hidden lg:flex items-center space-x-4 mt-2 lg:mt-0 ml-auto">
      <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="<?php echo $baseUrl; ?>login" class="text-gray-700 hover:text-emerald-600 font-semibold transition-all duration-300">Login</a>
      <a href="<?php echo $baseUrl; ?>register"
        class="px-6 py-2.5 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 hover:shadow-xl font-semibold transition-all duration-300">Create
        Account</a>
      <?php else: ?>
      <a href="<?php echo $baseUrl; ?>create-quote"
        class="border-2 border-emerald-600 text-emerald-700 bg-white/80 backdrop-blur-sm rounded-full hover:bg-emerald-700 hover:text-white hover:shadow-lg py-2 px-5 font-semibold transition-all duration-300">Create
        Quote</a>
           <a href="<?php echo $baseUrl; ?>notifications"
               class="relative flex items-center justify-center w-10 h-10 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full hover:bg-emerald-500 hover:text-white hover:shadow-lg transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
                    </path>
                </svg>
                <div class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-white bg-red-500 rounded-full shadow-lg animate-pulse">
                    <span class="text-xs font-semibold"><?php echo ($unreadCount > 9) ? '9+' : $unreadCount; ?></span>
                </div>
            </a>
         <div class="relative">
          <!-- Profile Button -->
                <?php
                $userImageFile = $_SESSION['user_img'] ?? '';
                if (function_exists('getUserImageSrc')) {
                    $imageSrc = getUserImageSrc($userImageFile, $baseUrl);
                } else {
                    $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
                    $imageSrc = !empty($userImageFile) && file_exists($userImagePath) ? $baseUrl . 'public/uploads/users/' . $userImageFile : $baseUrl . 'public/uploads/authors_images/placeholder.png';
                }
                ?>
                <a href="#" id="desktopProfileButton"
                   class="flex items-center justify-center w-10 h-10 object-cover bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full hover:bg-emerald-500 hover:text-white hover:shadow-lg transition-all duration-300">
                    <!-- User image -->
                    <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="<?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                         class="w-8 h-8 rounded-full object-cover">
                </a>
        <div id="desktopProfileDropdown"
          class="absolute right-0 mt-2 w-52 bg-white/95 backdrop-blur-lg border border-gray-200 rounded-2xl shadow-2xl z-10 hidden p-2 animate-fade-in-down">
          <div class="px-4 py-2 border-b">
            <p class="font-semibold"><?php echo $_SESSION['name']; ?></p>
            <p class="text-sm text-gray-600">@<?php echo $_SESSION['username']; ?></p>
          </div>
          <a href="<?php echo $baseUrl; ?>dashboard"
            class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-xl font-medium transition-all duration-200">Dashboard</a>
          <a href="<?php echo $baseUrl; ?>profile"
            class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-xl font-medium transition-all duration-200">Profile</a>
          <a href="<?php echo $baseUrl; ?>settings"
            class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 border-b border-gray-200 rounded-xl font-medium transition-all duration-200">Settings</a>
          <a href="<?php echo $baseUrl; ?>logout"
            class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl font-medium transition-all duration-200">Sign
            Out</a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </nav>
  
  <!-- Mobile Menu (Hidden by default) -->
  <div id="mobileMenu" class="hidden lg:hidden border-t border-gray-200/50 bg-white/95 backdrop-blur-lg">
    <div class="container mx-auto px-4 py-4 space-y-3">
      <!-- Search Bar (Mobile) -->
      <div class="relative">
        <input type="text" placeholder="Search quotes..." 
               class="w-full px-4 py-2.5 pl-10 border border-gray-200 rounded-full focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none text-sm shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 110-14 7 7 0 010 14z"></path>
          </svg>
        </div>
      </div>
      
      <!-- Navigation Links -->
      <div class="space-y-2">
        <a href="<?php echo $baseUrl; ?>authors" 
           class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-full font-semibold transition-all duration-300">
          Authors
        </a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?php echo $baseUrl; ?>create-quote" 
             class="block px-4 py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 hover:shadow-lg rounded-full font-semibold transition-all duration-300 text-center">
            Create Quote
          </a>
          <a href="<?php echo $baseUrl; ?>dashboard" 
             class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-full font-medium transition-all duration-300">
            Dashboard
          </a>
          <a href="<?php echo $baseUrl; ?>profile" 
             class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-full font-medium transition-all duration-300">
            Profile
          </a>
          <a href="<?php echo $baseUrl; ?>settings" 
             class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-full font-medium transition-all duration-300">
            Settings
          </a>
          <a href="<?php echo $baseUrl; ?>logout" 
             class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-full font-medium transition-all duration-300">
            Sign Out
          </a>
        <?php else: ?>
          <a href="<?php echo $baseUrl; ?>login" 
             class="block px-4 py-2.5 text-gray-800 hover:bg-emerald-100 hover:text-emerald-700 rounded-full font-medium transition-all duration-300">
            Login
          </a>
          <a href="<?php echo $baseUrl; ?>register" 
             class="block px-4 py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 hover:shadow-lg rounded-full font-semibold transition-all duration-300 text-center">
            Create Account
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<script>
    // Mobile Menu Toggle + Header Scroll Effects
    $(document).ready(function() {
        // Logo fade-in animation on page load
        setTimeout(function() {
            $(".logo_wrapper img").addClass("animate-fade-in");
        }, 100);
        
        // Hamburger menu toggle
        $("#mobileMenuButton").click(function() {
            const menu = $("#mobileMenu");
            const hamburgerIcon = $("#hamburgerIcon");
            const closeIcon = $("#closeIcon");
            const isExpanded = $(this).attr("aria-expanded") === "true";
            
            // Toggle menu visibility
            menu.slideToggle(300);
            
            // Toggle icons
            hamburgerIcon.toggleClass("hidden");
            closeIcon.toggleClass("hidden");
            
            // Update aria-expanded
            $(this).attr("aria-expanded", !isExpanded);
        });
        
        // Profile dropdown toggle (Desktop & Mobile)
        $("#profileButton, #desktopProfileButton").click(function(e) {
            e.preventDefault();
            const dropdown = $(this).siblings("div").first();
            dropdown.toggleClass("hidden");
        });

        // Close dropdowns when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest("#profileButton, #profileDropdown, #desktopProfileButton, #desktopProfileDropdown").length) {
                $("#profileDropdown, #desktopProfileDropdown").addClass("hidden");
            }
        });
        
        // Close mobile menu when window is resized to desktop
        $(window).resize(function() {
            if ($(window).width() >= 1024) {
                $("#mobileMenu").hide();
                $("#hamburgerIcon").removeClass("hidden");
                $("#closeIcon").addClass("hidden");
                $("#mobileMenuButton").attr("aria-expanded", "false");
            }
        });
        
        // ============================================
        // PRIORITY 3: SCROLL EFFECT (Advanced)
        // Improved contrast at top, glassmorphism when scrolling
        // ============================================
        let lastScrollTop = 0;
        const header = $("#mainHeader");
        
        $(window).scroll(function() {
            const scrollTop = $(this).scrollTop();
            
            if (scrollTop > 50) {
                // Scrolled down - glassmorphic floating header
                header.removeClass("bg-white shadow-lg border-b-2 border-emerald-200/30")
                      .addClass("bg-white/95 backdrop-blur-lg shadow-xl border-b border-gray-200/50");
            } else {
                // At top - solid header with emerald accent for contrast
                header.removeClass("bg-white/95 backdrop-blur-lg shadow-xl border-b border-gray-200/50")
                      .addClass("bg-white shadow-lg border-b-2 border-emerald-200/30");
            }
            
            lastScrollTop = scrollTop;
        });

    });

</script>
