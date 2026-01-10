<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($blog['title'])); ?> - Explore QuotesHub, where you can share and discover inspiring quotes. Join a community that celebrates wisdom and creativity. 💬🌟 | Quoteshub </title>
    <?php
    $baseUrl = getBaseUrl(); ?>
    
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
    
    
    <link rel="canonical" href="<?php echo getBaseUrl(); ?>view/<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($blog['id'])); ?>" />

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Explore QuotesHub, where you can share and discover inspiring quotes. Join a community that celebrates wisdom and creativity.">
    <meta name="keywords" content="quotes, inspiration, wisdom, QuotesHub, share quotes, quote community, motivate, connect, express yourself">
    <meta name="author" content="QuotesHub Team">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="QuotesHub - Share Your Wisdom" />
    <meta property="og:description" content="Join QuotesHub to create, inspire, and connect through quotes!" />
    <meta property="og:url" content="<?php echo getBaseUrl(); ?>/blog" />
    <meta property="og:type" content="website" />

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:title" content="QuotesHub - Share Your Wisdom">
    <meta name="twitter:description" content="Explore and share inspiring quotes on QuotesHub.">
    
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    <!-- Tailwind CSS CDN -->
    <!-- Tailwind removed - use base layout or local CSS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    
       <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
        </script>
    
</head>
<body style="background-color: #f4f2ee;">
    
    <!-- Header + Navbar -->
<header class="bg-white w-full h-auto border-b border-gray-300">
  <nav class="container mx-auto px-3 md:px-4 py-2 flex flex-wrap items-center justify-between">
    <!-- Mobile Menu Button & Logo Section -->
    <div class="flex items-center justify-between w-full lg:w-auto">
      <!-- Logo Section -->
      <div class="logo_wrapper ml-0">
        <a href="<?php echo $baseUrl; ?>"><img src="<?php echo $baseUrl; ?>public/uploads/images/logo.svg"
            alt="quoteshub-logo" /></a>
      </div>
      <!-- Mobile Menu Button (visible on small screens) -->
      <div class="block lg:hidden">
        <button id="menu-button" class="text-gray-800 focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu Links (hidden by default, shown on toggle) -->
    <div id="mobile-menu" class="menu-hidden fixed inset-0 bg-stone-100 border-t border-gray-200 lg:hidden mt-28 z-10">
      <div class="flex flex-col items-center py-4">
        <a href="<?php echo $baseUrl; ?>" class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Explore</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="<?php echo $baseUrl ?>login" class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Login</a>
        <a href="<?php echo $baseUrl; ?>register"
          class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Register</a>
        <?php else: ?>
        <a href="<?php echo $baseUrl; ?>create-quote"
          class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Create Quote</a>
        <a href="<?php echo $baseUrl; ?>dashboard"
          class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Dashboard</a>
        <a href="<?php echo $baseUrl; ?>profile"
          class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Profile</a>
        <a href="<?php echo $baseUrl; ?>changePassword"
          class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Change Password</a>
        <a href="<?php echo $baseUrl; ?>logout" class="block py-2 text-gray-700 hover:text-emerald-600 text-lg">Log
          out</a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Remaining nav links for large screens -->
    <div class="hidden lg:flex space-x-8 text-gray-700 ml-10">
      <a href="<?php echo $baseUrl; ?>" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Explore</span>
      </a>
      <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="<?php echo $baseUrl; ?>login" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Login</span>
      </a>
      <a href="<?php echo $baseUrl; ?>register" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Register</span>
      </a>
      <?php else: ?>
      <a href="<?php echo $baseUrl; ?>create-quote" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Create Quote</span>
      </a>

      <a href="<?php echo $baseUrl; ?>dashboard" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Dashboard</span>
      </a>

      <a href="<?php echo $baseUrl; ?>profile" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Profile</span>
      </a>

      <a href="<?php echo $baseUrl; ?>changePassword" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Change Password</span>
      </a>

      <a href="<?php echo $baseUrl; ?>logout" class="flex items-center space-x-1 hover:text-emerald-600">
        <span class="font-medium text-lg">Log out</span>
      </a>
      <?php endif; ?>
    </div>

    <!-- Search Bar (below logo and menu button on mobile) -->
    <div class="search_bar_wrapper w-full mt-2 lg:mt-0 lg:w-auto md:ml-auto">
      <div class="relative">
        <input type="text" placeholder="Search Quotes..."
          class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition outline-none" />
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <!-- Search Icon -->
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M11 18a7 7 0 110-14 7 7 0 010 14z"></path>
          </svg>
        </div>
      </div>
    </div>
  </nav>
</header>
 <?php include_once  __DIR__ . '/../../../config/utilis.php'; ?>
 <?php
  $baseUrl = 'https://thequoteshub.in/'; // Replace with your actual base URL
  ?>

 <div class="container mx-auto px-4 py-8">
   <div class="mt-6">
       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="inline-block w-5 h-5 md:w-6 md:h-6 mr-2 text-gray-700">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m-6-6 6 6-6 6"></path>
          </svg>
     <a href="<?php echo url('blogs'); ?>" class="text-gray-700 hover:underline">Back to Blogs List</a>
   </div>
   <br>
   <hr><br>
   <div class="bg-white p-8 rounded shadow">
     <h1 class="text-4xl font-bold text-gray-900 mb-4">
          <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($blog['title'])); ?>
     </h1>
     <span class="text-gray-500 text-sm mb-4 block">
       <?php echo date('F j, Y', strtotime($blog['created_at'])); ?>
     </span>
     <div class="text-gray-700 leading-relaxed">
          <?php echo decodeHtmlEntities($blog['content']); ?>
     </div>
   </div>
 </div>
 
 
 
 <br>
<hr>
<br>

<!-- Footer -->
<footer class="bg-white text-gray-700 py-6 border-t border-gray-200">
  <div class="container mx-auto px-4">
    <!-- Footer Links -->
    <div class="flex flex-wrap justify-center mb-6">
      <a href="<?php echo $baseUrl; ?>about" class="mx-4 text-xs md:text-sm hover:text-emerald-600">About Us</a>
      <a href="<?php echo $baseUrl; ?>terms" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Terms of Service</a>
      <a href="<?php echo $baseUrl; ?>contact" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Contact Us</a>
      <a href="<?php echo $baseUrl; ?>disclaimer" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Disclaimer</a>
      <a href="<?php echo $baseUrl; ?>privacy" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Privacy Policy</a>
      <a href="<?php echo $baseUrl; ?>blogs" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Blog</a>
      <a href="<?php echo $baseUrl; ?>feedback" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Feedback</a>
    </div>

    <!-- Logo and Copyright -->
    <div class="flex items-center md:flex-row flex-col justify-center space-x-4">
      <a class="flex items-center justify-center space-x-4" href="https:://www.thequoteshub.in"><img
          src="<?php echo $baseUrl; ?>public/uploads/images/logo.svg" alt="Website Logo" class="mr-4" />
        <p class="text-xs text-gray-500">&copy; <span><?php echo date("Y"); ?></span>  Quoteshub</p>
      </a>
      <p class="text-xs text-gray-500 ml-4">
        Crafted with
        <span class="hover:text-emerald-600">&hearts;</span> for the people
        on the internet
      </p>
    </div>
  </div>
</footer>
<?php include __DIR__ . '/../components/footer.php'; ?>
