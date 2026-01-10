<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once __DIR__ . '/../../../config/env.php';
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../controllers/NotificationController.php';
require_once __DIR__ . '/../../models/NotificationModel.php';

// Use base URL from environment configuration
$baseUrl = rtrim(APP_URL, '/') . '/';

// Access global database connection
global $conn;

// Instantiate the NotificationModel with the connection
$notificationModel = new NotificationModel($conn);

// Initialize unread notification count
$unreadCount = 0;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $unreadCount = $notificationModel->getUnreadNotificationCount($userId);
}

// Optional: close connection if it's not used further
// $conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <title>QuotesHub - Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟</title>
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
    
    <!-- jQuery (Load First - Required by other scripts) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <!-- Tailwind CSS (Local Build - Production Optimized) -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/tailwind.min.css">
    
    <!-- Base Styles -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-WFQ8T199Z6');
    </script>
    
    <!--Google Adsense-->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4514834781575546"
     crossorigin="anonymous"></script>
    
    <!--AdQuake Ads -->
   
    <!--ClickAddila-->
   
    <!--Hilltop Ads-->
    <meta name="7e0a543f2d98a2f6a659a6325a719c78159acc2d" content="7e0a543f2d98a2f6a659a6325a719c78159acc2d" />
    
    <style>
        .logo_wrapper{
            width: 8.7rem;
        }
    </style>
</head>
<body style="background-color: #F4F2EE;">

<!-- Header + Navbar -->
   <header class="bg-white w-full h-auto border-b border-gray-300">
      <!--Ad section-->
      <div data-banner-id="1434386"></div>
      <!----> 
      <nav class="container mx-auto px-4 py-2 flex flex-wrap items-center justify-between">
        <div class="flex items-center justify-between w-full lg:w-auto">
          <div class="logo_wrapper">
            <a href="/public_html">
              <img  src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="quoteshub-logo" />
            </a>
          </div>

          <?php if (!isset($_SESSION['user_id'])): ?>
          <div class="ml-auto lg:hidden">
            <a href="<?php echo $baseUrl; ?>register"
              class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition text-sm">Create
              Account</a>
          </div>
          <?php else: ?>
          <div class="ml-auto lg:hidden flex items-center space-x-2">
            <a href="<?php echo $baseUrl; ?>create-quote"
              class="border border-emerald-600 text-emerald-700 bg-white rounded-lg hover:bg-emerald-700 hover:text-white py-1 px-1 md:py-2 md:px-4 transition-all duration-200 text-sm">Create
              Quote</a>
              
            <a href="<?php echo $baseUrl; ?>notifications"
               class="relative flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white border border-gray-300 rounded-full hover:bg-emerald-500 hover:text-white transition duration-200">
                <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
                    </path>
                </svg>
                
            
                    <div class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-white bg-red-500 rounded-xl">
                             <span class="text-xs font-normal"><?php echo ($unreadCount > 9) ? '9+' : $unreadCount; ?></span>
                    </div>
            </a>

            <!-- Profile Button -->
            <div class="relative">
                 <?php
                    // Get user image from session
                    $userImageFile = $_SESSION['user_img'] ?? '';
                    $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
                
                    // Determine the image source based on whether the file exists or if it's blank
                    $imageSrc = !empty($userImageFile) && file_exists($userImagePath) ? $baseUrl . 'public/uploads/users/' . $userImageFile : $baseUrl . 'public/uploads/authors_images/placeholder.png';
                    ?>
              <a href="#" id="profileButton"
                class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white border border-gray-300 rounded-full object-cover hover:bg-emerald-500 hover:text-white transition duration-200">
                <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8'); ?>"
                  class="w-6 h-6 md:w-8 md:h-8 rounded-full object-cover">
              </a>
              

              <!-- Dropdown Menu -->
              <div id="profileDropdown"
                class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden p-2">
                <div class="px-4 py-2 border-b">
                  <p class="font-semibold"><?php echo $_SESSION['name']; ?></p>
                  <p class="text-sm text-gray-600">@<?php echo $_SESSION['username']; ?></p>
                </div>
                <a href="<?php echo $baseUrl; ?>dashboard"
                  class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg">Dashboard</a>
                   <a href="<?php echo $baseUrl; ?>profile"
                class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg">Profile</a>
                <a href="<?php echo $baseUrl; ?>settings"
                  class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg border-b">Settings</a>
                <a href="<?php echo $baseUrl; ?>logout"
                  class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg">Sign
                  Out</a>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
        
       
    <a href="<?php echo $baseUrl; ?>authors" class="font-semibold block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg">Authors</a>
    
    <?php include __DIR__ . '/search_bar.php'; ?>
            
        <!--<div class="search_bar_wrapper w-full mt-2 lg:mt-0 lg:w-auto lg:ml-4">-->
        <!--  <div class="relative">-->
        <!--    <input type="text" placeholder="Search Quotes..."-->
        <!--      class="w-full lg:max-w-sm pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition outline-none" />-->
        <!--    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
        <!--      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"-->
        <!--        xmlns="http://www.w3.org/2000/svg">-->
        <!--        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
        <!--          d="M21 21l-4.35-4.35M11 18a7 7 0 110-14 7 7 0 010 14z"></path>-->
        <!--      </svg>-->
        <!--    </div>-->
        <!--  </div>-->
        <!--</div>-->

        <div class="hidden lg:flex items-center space-x-4 mt-2 lg:mt-0 ml-auto">
          <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="<?php echo $baseUrl; ?>login" class="text-gray-700 hover:text-emerald-600 font-medium">Login</a>
          <a href="<?php echo $baseUrl; ?>register"
            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition">Create
            Account</a>
          <?php else: ?>
          <a href="<?php echo $baseUrl; ?>create-quote"
            class="border border-emerald-600 text-emerald-700 bg-white rounded-lg hover:bg-emerald-700 hover:text-white py-2 px-4 transition-all duration-200">Create
            Quote</a>
               <a href="<?php echo $baseUrl; ?>notifications"
                   class="relative flex items-center justify-center w-10 h-10 bg-white border border-gray-300 rounded-full hover:bg-emerald-500 hover:text-white transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <div class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-white bg-red-500 rounded-xl ">
                        <span class="text-xs font-normal"><?php echo ($unreadCount > 9) ? '9+' : $unreadCount; ?></span>
                    </div>
                </a>
             <div class="relative">
              <!-- Profile Button -->
                    <?php
                    $userImageFile = $_SESSION['user_img'] ?? '';
                    $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
                
                    $imageSrc = !empty($userImageFile) && file_exists($userImagePath) ? $baseUrl . 'public/uploads/users/' . $userImageFile : $baseUrl . 'public/uploads/authors_images/placeholder.png';
                    ?>
                    <a href="#" id="desktopProfileButton"
                       class="flex items-center justify-center w-10 h-10 object-cover bg-white border border-gray-300 rounded-full hover:bg-emerald-500 hover:text-white transition duration-200">
                        <!-- User image -->
                        <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                             class="w-8 h-8 rounded-full object-cover">
                    </a>
            <div id="desktopProfileDropdown"
              class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden p-2">
              <div class="px-4 py-2 border-b">
                <p class="font-semibold"><?php echo $_SESSION['name']; ?></p>
                <p class="text-sm text-gray-600">@<?php echo $_SESSION['username']; ?></p>
              </div>
              <a href="<?php echo $baseUrl; ?>dashboard"
                class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg">Dashboard</a>
              <a href="<?php echo $baseUrl; ?>profile"
                class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg">Profile</a>
              <a href="<?php echo $baseUrl; ?>settings"
                class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 border-b rounded-lg">Settings</a>
              <a href="<?php echo $baseUrl; ?>logout"
                class="block px-4 py-2 text-gray-800 hover:bg-emerald-100 rounded-lg">Sign
                Out</a>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </nav>
    </header>
