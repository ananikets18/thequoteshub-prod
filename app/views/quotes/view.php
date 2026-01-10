<?php
include_once  __DIR__ . '/../../../config/database.php'; 
?>

<?php
$baseUrl = getBaseUrl();
?>
 <?php
$unreadCount = isset($_SESSION['unread_count']) ? $_SESSION['unread_count'] : 0;
 ?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo limitWords(decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])), 15); ?> - <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?> | QuotesHub </title>
     <!-- Dynamic Meta Description -->
    <meta name="description" content="Read this inspiring quote by <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?>: '<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?>'. Discover more motivational quotes on QuotesHub.">


        <!-- Meta Keywords -->
    <meta name="keywords" content="Quotes, Inspirational Quotes, <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?>, <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?>, Motivational Quotes, Wisdom, QuotesHub">

    <?php $baseUrl = getBaseUrl(); ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
      <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    
    <!-- Tailwind CSS (Local Build - Production Optimized) -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/tailwind.min.css">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo $baseUrl; ?>quote/<?php echo $quote['id']; ?>">

    <!-- Open Graph Tags (for social media sharing) -->
    <meta property="og:title" content="<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?> - <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?> | QuotesHub">
    
    <meta property="og:description" content="Explore this quote by <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?>: '<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?>'. Discover more at QuotesHub!">
    <meta property="og:url" content="<?php echo $baseUrl; ?>quote/<?php echo $quote['id']; ?>">
    <meta property="og:type" content="article">
    
    
       <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?> - <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?> | QuotesHub">
    <meta name="twitter:description" content="Explore this inspiring quote by <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?>: '<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?>'. Discover more at QuotesHub!">
  
    
     <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    
       <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
        </script>
    <style>
        .logo_wrapper{
            width: 8.7rem;
        }
    </style>
  </head>
  
  <body style="background-color:  #F4F2EE;">
   <header class="bg-white w-full h-auto border-b border-gray-300">
      <nav class="container mx-auto px-4 py-2 flex flex-wrap items-center justify-between">
        <div class="flex items-center justify-between w-full lg:w-auto">
          <div class="logo_wrapper">
            <a href="/">
              <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="quoteshub-logo" />
            </a>
          </div>

          <?php if (!isset($_SESSION['user_id'])): ?>
          <div class="ml-auto lg:hidden">
            <a href="<?php echo $baseUrl; ?>register"
              class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition">Create
              Account</a>
          </div>
          <?php else: ?>
          <div class="ml-auto lg:hidden flex items-center space-x-2">
            <a href="<?php echo $baseUrl; ?>create-quote"
              class="border border-emerald-600 text-emerald-700 bg-white rounded-lg hover:bg-emerald-700 hover:text-white py-1 px-1 md:py-2 md:px-4 transition-all duration-200 text-sm">Create
              Quote</a>

              <a href="<?php echo $baseUrl; ?>notifications"
               class="relative flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white border border-gray-300 rounded-full hover:bg-emerald-500 hover:text-white transition duration-200">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
                    </path>
                </svg>
            
              
                    <span class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-xs font-semibold text-white bg-red-500 rounded-xl">
                        <?php echo $unreadCount; ?>
                    </span>
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
    
       <?php include __DIR__ . '/../components/search_bar.php'; ?>


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
                
                <span class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-xs font-semibold text-white bg-red-500 rounded-xl">
                        <?php echo $unreadCount; ?>
                    </span>
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
<?php include_once  __DIR__ . '/../../../config/utilis.php'; ?>
<?php
$baseUrl = getBaseUrl(); // Replace with your actual base URL
?>


<!-- Main Content with Padding for Tablet to Mobile Screens -->
<div class="min-h-screen flex flex-col items-center py-10 px-6 md:px-12">
    

<div class="flex flex-col md:flex-row md:space-x-6 w-full max-w-5xl">
      <?php if ($quote): ?>
  <section class="w-full max-w-2xl bg-white p-3 md:p-6 rounded-xl mb-12 border border-2 border-gray-200 space-y-6" id="quote-card">
    <!-- Quote Card Header with User Profile Link -->
    <div class="flex md:flex-row flex-col md:space-y-0 space-y-2 justify-between md:items-center mb-4">
              <div class="flex items-center space-x-2 lg:space-x-2">
                <?php
                    // Check if the user image filename is empty or if the file does not exist
                    $userImageFile = $quote['user__img'];
                    $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
            
                    // Determine the image source based on whether the file exists or if it's blank
                    $imageSrc = !empty($userImageFile) && file_exists($userImagePath) ? $baseUrl . 'public/uploads/users/' . $userImageFile : $baseUrl . 'public/uploads/authors_images/placeholder.png';
                ?>
                <div class="relative">
                    <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="User"
                     class="w-8 h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 rounded-full mr-2 md:mr-4 object-cover" />
            
                <!-- Display the active status indicator -->
                <?php if ($quote['user_status'] == 1): // Assuming 1 means active ?>
                    <span class="w-2 h-2 bg-green-500 rounded-full absolute left-1.5 bottom-0"></span>
                <?php endif; ?>
                </div>
       
            
                <a  href="<?php echo $baseUrl; ?><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['user__name'])); ?>"
                   class="font-semibold text-gray-800 hover:underline capitalize text-sm sm:text-md md:text-lg">
                    <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['user_name'])); ?>
                </a>
                <?php if ($quote['user_quote_count'] > 20): ?>
                   <svg class="w-4 h-4 rounded-full border-1 border-yellow-500 bg-yellow-100 hover:cursor-pointer" 
                     xmlns="http://www.w3.org/2000/svg" 
                     viewBox="0 0 24 24" 
                     title="Verified User">
                    <circle cx="12" cy="12" r="12" fill="#00cc44"/>
                    <path fill="white" d="M9.5 16.5l-3-3 1.41-1.41L9.5 13.67l6.09-6.09L17 8.5l-7.5 7.5z"/>
                </svg>
            <?php endif; ?>
            </div>

      <span class="text-xs md:text-sm text-gray-500">Created on <?php echo convertUtcToIst($quote['created_at']); ?></span>
    </div>
    
    <!-- Quote Content -->
    <div class="text-center p-4 md:p-6 bg-green-100 border border-gray-100 rounded-lg relative">
      <blockquote class="border-l-2 md:border-l-4 border-green-500 text-sm sm:text-md md:text-xl lg:text-2xl px-3 md:px-5 italic font-semibold text-gray-800 leading-relaxed view_blockquote" id="quoteText">
        " <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?> "
      </blockquote>
      <a href="<?php echo url('authors/' . urlencode($quote['author_name'])); ?>" class="mt-4 text-xs md:text-md lg:text-lg text-gray-600 font-medium capitalize cursor-pointer" id="authorName">— <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?></a>
    </div>


            <!-- Categories and Buttons -->
                <div class="mt-4 flex items-center justify-between">
                  <div class="flex space-x-2">
                    <a title="<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['category_name'])); ?>" href="<?php echo $baseUrl; ?>category/<?php echo ($quote['category_name']); ?>"
                      class="bg-gray-50 text-gray-700 px-2 py-1 rounded-full text-xs lg:text-sm hover:bg-green-700 hover:text-white border border-green-300">#
                      <span class="capitalize"><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['category_name'])); ?></span></a>
                  </div>
                  
                  <div class="flex space-x-2">
                    <!-- Customize Quote Button -->
                    <a title="Customize Quote" href="<?php echo $baseUrl; ?>customize_quote/<?php echo $quote['id']; ?>"
                       class="flex items-center space-x-2 text-gray-500 hover:text-purple-500 focus:outline-none border border-gray-300 rounded-lg shadow-sm px-3 py-2 transition-all duration-200 ease-in-out hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 md:h-5 md:w-5">
                              <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                              <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                        </svg>

                      <!--<span class="font-medium text-xs md:text-sm">Customize Quote</span>-->
                    </a>
                
                    <!-- Copy to Clipboard Button -->
                    <button title="Copy Quote" id="copyBtn" class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 focus:outline-none border border-gray-300 rounded-lg shadow-sm px-3 py-2 transition-all duration-200 ease-in-out hover:shadow-lg">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 md:h-5 md:w-5">
                          <path fill-rule="evenodd" d="M17.663 3.118c.225.015.45.032.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 0 1-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217.324-1.132 1.3-2.01 2.548-2.114.224-.019.448-.036.673-.051A3 3 0 0 1 13.5 1.5H15a3 3 0 0 1 2.663 1.618ZM12 4.5A1.5 1.5 0 0 1 13.5 3H15a1.5 1.5 0 0 1 1.5 1.5H12Z" clip-rule="evenodd" />
                          <path d="M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 0 1 9 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0 1 16.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625v-12Z" />
                          <path d="M10.5 10.5a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963 5.23 5.23 0 0 0-3.434-1.279h-1.875a.375.375 0 0 1-.375-.375V10.5Z" />
                      </svg>
                      <!--<span id="copyText" class="font-medium text-xs md:text-sm">Copy Quote</span>-->
                    </button>
                  </div>
                </div>

    <!-- Interaction Buttons -->
    <div class="flex items-center mt-6 w-full">
      <div class="flex md:flex-row flex-col md:space-y-0 space-y-3 items-center justify-between w-full">
        <div class="flex items-center justify-between space-x-5">
          <div class="quote-actions flex items-center space-x-5">
            <!-- Like Button and Count -->
        
            <button  sr-only 
                      title="likes" 
                      data-quote-id="<?php echo $quote['id']; ?>" 
                      class="text-gray-500 text-red-500 focus:outline-none flex items-center text-sm md:text-md md:space-x-1 like-button <?php echo isset($_SESSION['user_id']) ? 'logged-in' : ''; ?>"
                      style="cursor: <?php echo isset($_SESSION['user_id']) ? 'default' : 'pointer'; ?>">
                      <svg class="w-3 h-3 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                          d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                      </svg>
                      <!-- Like count -->
                      <span class="like-count font-medium capitalize text-xs md:text-sm ml-1">
                        <?php echo $likeCount; ?> likes
                      </span>
                    </button>
                    
           <button title="save" sr-only
                  data-quote-id="<?php echo $quote['id']; ?>" 
                  class="text-gray-500 text-blue-500 focus:outline-none flex items-center text-sm md:text-md md:space-x-1 save-button <?php echo isset($_SESSION['user_id']) ? 'logged-in' : ''; ?>"
                  style="cursor: <?php echo isset($_SESSION['user_id']) ? 'default' : 'pointer'; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 md:w-6 md:h-6">
                    <path fill-rule="evenodd"
                      d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                      clip-rule="evenodd" />
                  </svg>
                  <!-- Save count -->
                  <span class="save-count font-medium capitalize text-xs md:text-sm ml-1">
                    <?php echo $saveCount; ?> saves
                  </span>
                </button>

          </div>
          <!-- Share Button -->
          <button title="Share" class="text-gray-500 hover:text-yellow-500 focus:outline-none shareButton"
            data-quote="<?php echo $quote['id']; ?>"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
              fill="currentColor" class="w-3 h-3 md:w-6 md:h-6">
              <path fill-rule="evenodd"
                d="M15.75 4.5a3 3 0 1 1 .825 2.066l-8.421 4.679a3.002 3.002 0 0 1 0 1.51l8.421 4.679a3 3 0 1 1-.729 1.31l-8.421-4.678a3 3 0 1 1 0-4.132l8.421-4.679a3 3 0 0 1-.096-.755Z"
                clip-rule="evenodd" />
            </svg>
          </button>
            </div>

        <!-- Google Translate -->
        <div>
           <div id="google_translate_element" class="ms-auto"></div> 
        </div>
      </div>
    </div>

  </section>

  <?php else: ?>
  

  <h1 class="text-center">Quote not found.</h1>
  <?php endif; ?>

    <aside class="w-full mx-auto max-w-sm bg-white h-max p-3 rounded-lg border border-gray-300">
        <a href="<?php echo $baseUrl; ?>categories" class="font-bold text-lg mb-2 text-blue-600 hover:text-blue-800 transition duration-200 ease-in-out">
            Explore Categories
        </a>

        <ul class="space-y-1 mt-4">
            <?php foreach ($randomFiveCategories as $randomCategory): ?>
                <li class="text-sm md:text-base ">
               <a href="<?php echo $baseUrl; ?>category/<?php echo urlencode($randomCategory['category_name']); ?>" class="block text-gray-700 hover:text-emerald-500 hover:bg-gray-100 px-2 py-1 rounded">
                        #<?php echo ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($randomCategory['category_name']))); ?>
                </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>


  </div>
  
 
    <div class="border border-t border-gray-200 w-full"></div>
  <br> 
  <!-- More Quotes Section -->
  <section class="w-full max-w-4xl">
    <h2 class="text-lg font-semibold mb-6">More Quotes Like This</h2>
    <?php if (!empty($quotes)): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($quotes as $quote): ?>
      <a href="<?php echo $quote['id']; ?>" class="bg-white p-4 shadow rounded border">
        <blockquote class="text-sm md:text-md lg:text-lg italic text-gray-500 font-semibold">
          " <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?> "
        </blockquote>
        <p class="mt-2 text-gray-600 text-xs md:text-sm">— <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?></p>
      </a>  
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <h2>No quotes found.</h2>
    <?php endif; ?>
  </section>
</div>
<br /><br /><br /><br />
<!-- Footer -->
<footer class="bg-white text-gray-700 py-6 border-t border-gray-200">
  <div class="container mx-auto px-4">
    <!-- Footer Links -->
    <div class="flex flex-wrap justify-center mb-6">
      <a href="<?php echo $baseUrl; ?>about" class="mx-4 text-xs md:text-sm hover:text-emerald-600">About Us</a>
              <a href="<?php echo $baseUrl; ?>guidelines" class="mx-4 text-sm hover:text-emerald-600">Guidelines</a>
      <a href="<?php echo $baseUrl; ?>terms" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Terms of Service</a>
      <a href="<?php echo $baseUrl; ?>contact" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Contact Us</a>
      <a href="<?php echo $baseUrl; ?>disclaimer" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Disclaimer</a>
      <a href="<?php echo $baseUrl; ?>privacy" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Privacy Policy</a>
      <a href="<?php echo $baseUrl; ?>blogs" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Blog</a>
      <a href="<?php echo $baseUrl; ?>feedback" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Feedback</a>
    </div>

    <!-- Logo and Copyright -->
    <div class="flex md:flex-row flex-col items-center justify-center space-x-4">
      <a class="flex items-center justify-center space-x-4" href="<?php echo getBaseUrl(); ?>"><img
          src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="Website Logo" class="mr-4" />
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
      <!-- Login Button -->
      <a href="<?php echo url('login'); ?>"
        class="px-4 py-2 text-sm md:text-base lg:text-lg bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Log In
      </a>
      <!-- Close Button -->
      <button
        class="modal-close px-4 py-2 text-sm md:text-base lg:text-lg bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
        Close
      </button>
    </div>
  </div>
</div>





<!-- Share Modal -->
<div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden shareModal">
  <div class="bg-white p-6 rounded-lg shadow-lg w-80 relative">
    <!-- Close Button -->
    <button id="closeModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-lg focus:outline-none">
      &times;
    </button>

    <!-- Modal Content -->
    <h2 class="text-lg font-semibold mb-4 text-center">Share this Quote</h2>
    <div class="flex flex-col space-y-2 mb-4">
      <!-- Social Media Buttons -->
      <a href="#" class="flex items-center justify-center bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
        target="_blank" id="facebookShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M12 2.4c-5.31 0-9.6 4.29-9.6 9.6 0 4.86 3.57 8.88 8.27 9.8v-6.93h-2.48v-2.48h2.48v-1.83c0-2.44 1.48-3.75 3.63-3.75 1.03 0 2.06.07 2.06.07v2.27h-1.16c-1.14 0-1.5.72-1.5 1.46v1.76h2.59l-.41 2.48h-2.18v6.93c4.71-.92 8.27-4.94 8.27-9.8 0-5.31-4.29-9.6-9.6-9.6z" />
        </svg>
        Facebook
      </a>
      <a href="#" class="flex items-center justify-center bg-blue-400 text-white py-2 rounded hover:bg-blue-500"
        target="_blank" id="twitterShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M23 3a10.9 10.9 0 01-3.14.87A4.92 4.92 0 0022.4 2.1a10.9 10.9 0 01-3.14 1.2A4.9 4.9 0 0016.6 1a4.92 4.92 0 00-4.9 4.9c0 .4.05.79.1 1.17A13.9 13.9 0 011.67 2.29 4.9 4.9 0 003 8.21a4.84 4.84 0 01-2.22-.6v.06c0 2.36 1.68 4.33 3.9 4.78a4.91 4.91 0 01-2.22.08c.62 1.94 2.42 3.35 4.56 3.39A9.86 9.86 0 010 17.4a13.9 13.9 0 007.55 2.2c9.05 0 14-7.5 14-14 0-.21-.01-.42-.02-.62A10.02 10.02 0 0023 3z" />
        </svg>
        Twitter
      </a>
      <a href="#" class="flex items-center justify-center bg-red-500 text-white py-2 rounded hover:bg-red-600"
        target="_blank" id="linkedinShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M4.98 3.5c-1.9 0-3.4 1.5-3.4 3.4 0 1.9 1.5 3.4 3.4 3.4 1.9 0 3.4-1.5 3.4-3.4 0-1.9-1.5-3.4-3.4-3.4zm-1.2 5.9h2.4v9.5H3.78v-9.5zM15.1 11.8c-.4-1.6-1.8-2.8-3.5-2.8-2 0-3.4 1.2-3.4 3v.1c0 1.9 1.3 3.2 3.2 3.2 1.7 0 3.3-1.2 3.3-3v-.1zm1.8-5.3h2.4v2.7h.1c.3-.6 1-1.4 2.1-1.4 1.5 0 2.7 1.3 2.7 3.1v5.5h-2.4v-5.1c0-1.2-.7-1.9-1.6-1.9-1 0-1.8.7-2.1 1.4-.1.2-.1.6-.1.9v5.7h-2.4v-6.5c0-.5-.1-1.1-.4-1.6-.6-1.2-1.8-1.9-3.2-1.9-2.2 0-4 1.8-4 4v6.5H1.2v-6.5c0-2.6 2.1-4.7 4.7-4.7 2.6 0 4.7 2.1 4.7 4.7v1.1c.9-1.6 2.6-2.7 4.7-2.7 3 0 5.5 2.5 5.5 5.6v6.5h-2.4v-6.5z" />
        </svg>
        LinkedIn
      </a>
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
      <!-- Social Media Buttons -->
      <a href="#" class="flex items-center justify-center bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
        target="_blank" id="facebookShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M12 2.4c-5.31 0-9.6 4.29-9.6 9.6 0 4.86 3.57 8.88 8.27 9.8v-6.93h-2.48v-2.48h2.48v-1.83c0-2.44 1.48-3.75 3.63-3.75 1.03 0 2.06.07 2.06.07v2.27h-1.16c-1.14 0-1.5.72-1.5 1.46v1.76h2.59l-.41 2.48h-2.18v6.93c4.71-.92 8.27-4.94 8.27-9.8 0-5.31-4.29-9.6-9.6-9.6z" />
        </svg>
        Facebook
      </a>
      <a href="#" class="flex items-center justify-center bg-blue-400 text-white py-2 rounded hover:bg-blue-500"
        target="_blank" id="twitterShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M23 3a10.9 10.9 0 01-3.14.87A4.92 4.92 0 0022.4 2.1a10.9 10.9 0 01-3.14 1.2A4.9 4.9 0 0016.6 1a4.92 4.92 0 00-4.9 4.9c0 .4.05.79.1 1.17A13.9 13.9 0 011.67 2.29 4.9 4.9 0 003 8.21a4.84 4.84 0 01-2.22-.6v.06c0 2.36 1.68 4.33 3.9 4.78a4.91 4.91 0 01-2.22.08c.62 1.94 2.42 3.35 4.56 3.39A9.86 9.86 0 010 17.4a13.9 13.9 0 007.55 2.2c9.05 0 14-7.5 14-14 0-.21-.01-.42-.02-.62A10.02 10.02 0 0023 3z" />
        </svg>
        Twitter
      </a>
      <a href="#" class="flex items-center justify-center bg-red-500 text-white py-2 rounded hover:bg-red-600"
        target="_blank" id="linkedinShare">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M4.98 3.5c-1.9 0-3.4 1.5-3.4 3.4 0 1.9 1.5 3.4 3.4 3.4 1.9 0 3.4-1.5 3.4-3.4 0-1.9-1.5-3.4-3.4-3.4zm-1.2 5.9h2.4v9.5H3.78v-9.5zM15.1 11.8c-.4-1.6-1.8-2.8-3.5-2.8-2 0-3.4 1.2-3.4 3v.1c0 1.9 1.3 3.2 3.2 3.2 1.7 0 3.3-1.2 3.3-3v-.1zm1.8-5.3h2.4v2.7h.1c.3-.6 1-1.4 2.1-1.4 1.5 0 2.7 1.3 2.7 3.1v5.5h-2.4v-5.1c0-1.2-.7-1.9-1.6-1.9-1 0-1.8.7-2.1 1.4-.1.2-.1.6-.1.9v5.7h-2.4v-6.5c0-.5-.1-1.1-.4-1.6-.6-1.2-1.8-1.9-3.2-1.9-2.2 0-4 1.8-4 4v6.5H1.2v-6.5c0-2.6 2.1-4.7 4.7-4.7 2.6 0 4.7 2.1 4.7 4.7v1.1c.9-1.6 2.6-2.7 4.7-2.7 3 0 5.5 2.5 5.5 5.6v6.5h-2.4v-6.5z" />
        </svg>
        LinkedIn
      </a>
       <!-- Copy URL Button -->
      <button id="copyModalBtn" class="flex items-center justify-center bg-green-600 text-gray-200 py-2 rounded hover:bg-green-700">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M17.663 3.118c.225.015.45.032.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 0 1-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217.324-1.132 1.3-2.01 2.548-2.114.224-.019.448-.036.673-.051A3 3 0 0 1 13.5 1.5H15a3 3 0 0 1 2.663 1.618ZM12 4.5A1.5 1.5 0 0 1 13.5 3H15a1.5 1.5 0 0 1 1.5 1.5H12Z" />
          <path
            d="M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 0 1 9 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0 1 16.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625v-12Z" />
          <path
            d="M10.5 10.5a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963 5.23 5.23 0 0 0-3.434-1.279h-1.875a.375.375 0 0 1-.375-.375V10.5Z" />
        </svg>
        <span id="copyModalText">Copy Link</span>
      </button>
    </div>
  </div>
</div>



<script defer type="text/javascript"
  src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
      pageLanguage: "en"
    },
    "google_translate_element"
  );
}

document.getElementById('copyModalBtn').addEventListener('click', function() {
  const url = window.location.href;
  navigator.clipboard.writeText(url).then(() => {
    // Change the button text to 'Copied'
    document.getElementById('copyModalText').textContent = 'Copied';
    
    // Optionally, change it back after a delay
    setTimeout(() => {
      document.getElementById('copyModalText').textContent = 'Copy Link';
    }, 1500);
  }).catch(err => {
    console.error('Failed to copy URL: ', err);
  });
});


document.getElementById('copyBtn').addEventListener('click', function() {
  // Get the quote text and author name from the DOM
  const quoteText = document.getElementById('quoteText').innerText.trim();
  const authorName = document.getElementById('authorName').innerText.trim();
  
  // Format the content to copy: "Quote text" — Author Name
  const quoteToCopy = `${quoteText} ${authorName}`;
  
  // Copy the formatted quote to the clipboard
  navigator.clipboard.writeText(quoteToCopy).then(() => {
    // Change the button text to 'Copied'
    document.getElementById('copyText').textContent = 'Copied';
    
    // Optionally, change it back after a delay
    setTimeout(() => {
      document.getElementById('copyText').textContent = 'Copy Quote';
    }, 1500);
  }).catch(err => {
    console.error('Failed to copy quote: ', err);
  });
});


$(document).ready(function() {
    // Like and Save Button Logic
    $('.like-button, .save-button').on('click', function(event) {
      // Check if the user is logged in (modify this check according to your logic)
      var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

      if (!isLoggedIn) {
        // Prevent the default action and show the login modal
        event.preventDefault();
        $('#loginModal').removeClass('hidden');
      }
    });

    // Close modal when clicking the close button
    $('.modal-close').on('click', function() {
      $('#loginModal').addClass('hidden');
    });
});


</script>



<?php include __DIR__ . '/../components/footer.php'; ?>
