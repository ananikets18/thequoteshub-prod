 <?php
$unreadCount = isset($_SESSION['unread_count']) ? $_SESSION['unread_count'] : 0;
 ?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>theQuoteshub Blog - Explore QuotesHub, where you can share and discover inspiring quotes. Join a community that celebrates wisdom and creativity. 💬🌟</title>
    <?php
    $baseUrl = getBaseUrl(); ?>
    
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">

    <link rel="canonical" href="<?php echo getBaseUrl(); ?>blogs" />


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
    <meta name="twitter:card" content="summary_large_image">
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
      <nav class="container mx-auto px-4 py-2 flex flex-wrap items-center justify-between">
        <div class="flex items-center justify-between w-full lg:w-auto">
          <div class="logo_wrapper">
            <a href="/">
              <img src="<?php echo $baseUrl; ?>/public/uploads/images/logo_clean.svg" alt="quoteshub-logo" />
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
              class="border border-emerald-600 text-emerald-700 bg-white rounded-lg hover:bg-emerald-700 hover:text-white py-1 px-2 md:py-2 md:px-4 transition-all duration-200 text-sm">Create
              Quote</a>

            <a href="<?php echo $baseUrl; ?>notifications"
              class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white border border-gray-300 rounded-full hover:bg-emerald-500 hover:text-white transition duration-200">
              <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
                </path>
              </svg>
            </a>

            <!-- Profile Button -->
            <div class="relative">

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

        <div class="search_bar_wrapper w-full mt-2 lg:mt-0 lg:w-auto lg:ml-4">
          <div class="relative">
            <input type="text" placeholder="Search Quotes..."
              class="w-full lg:max-w-sm pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition outline-none" />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M11 18a7 7 0 110-14 7 7 0 010 14z"></path>
              </svg>
            </div>
          </div>
        </div>

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
            class="flex items-center justify-center w-10 h-10 bg-white border border-gray-300 rounded-full hover:bg-emerald-500 hover:text-white transition duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.437L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9">
              </path>
            </svg>
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

<div class="container mx-auto px-4 py-8">
  <!-- Page Header -->
 <h1 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900">Latest Blogs</h1>
  <p class="text-base md:text-lg text-gray-600 mb-8">
    A collection blog to keep an eye on version control and updates of 'theQuoteshub'
  </p>

  <!-- Blogs Grid -->
  <div class="space-y-12">
    <?php if (empty($blogs)): ?>
    <p class="text-gray-600">No blogs available at the moment.</p>
    <?php else: ?>
    <?php foreach ($blogs as $blog): ?>
    <div class="bg-white p-8 rounded shadow">
      <div class="flex flex-col md:flex-row md:items-start space-y-4 md:space-y-0 md:space-x-4">
        <!-- Blog Date -->
        <span class="text-gray-500 text-sm md:w-1/6">
          <?php echo date('F j, Y', strtotime($blog['created_at'])); ?>
        </span>

        <!-- Blog Content -->
        <div class="w-full md:w-5/6">
          <h2 class="text-2xl font-semibold text-gray-900 mb-2">
           <a href="<?php echo $baseUrl; ?>view/<?php echo htmlspecialchars($blog['id']); ?>">
            <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($blog['title'])); ?>
            </a>
          </h2>

          <p class="text-gray-700 leading-relaxed mb-4">
            <?php echo decodeCleanAndRemoveTags(decodeAndCleanText(substr($blog['content'], 0, 299))) . '...'; ?>
          </p>
        </div>
      </div>

      <!-- Read More Button -->
      <div class="mt-4 text-center">
        <a href="<?php echo $baseUrl; ?>view/<?php echo htmlspecialchars($blog['id']); ?>"
          class="text-pink-600 text-sm font-medium hover:text-pink-700 transition-all duration-200">
          Read more →
        </a>
      </div>
    </div>

    <hr class="my-8 border-gray-200">
    <?php endforeach; ?>
    <?php endif; ?>
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
          src="<?php echo $baseUrl; ?>/public/uploads/images/logo.svg" alt="Website Logo" class="mr-4" />
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
