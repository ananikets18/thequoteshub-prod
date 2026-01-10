<?php
require_once __DIR__ . '/../../../config/utilis.php';
$csrfToken = generateCsrfToken();    ?>
<?php $baseUrl = getBaseUrl(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
  <title>Login - QuotesHub | Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟"</title>
  
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
<script src="https://accounts.google.com/gsi/client" async defer></script>


<link rel="canonical" href="<?php echo getBaseUrl(); ?>login" />

   <!-- Meta Tags for SEO -->
  <meta name="description" content="Login to QuotesHub to create, inspire, and connect through quotes. Join a community that celebrates wisdom and creativity." />
  <meta name="keywords" content="quotes, QuotesHub, login, inspiration, share quotes, community, wisdom, creativity" />
  <meta name="author" content="QuotesHub Team" />

  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="Login - QuotesHub" />
  <meta property="og:description" content="Login to QuotesHub and start sharing inspiring quotes with the community." />
  <meta property="og:image" content="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico" />
  <meta property="og:url" content="<?php echo getBaseUrl(); ?>/login" />
  <meta property="og:type" content="website" />

  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:title" content="Login - QuotesHub" />
  <meta name="twitter:description" content="Login to QuotesHub and connect through quotes." />
  <meta name="twitter:image" content="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico" />
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



   <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
        </script>
  
  <!-- Tailwind CSS (Local Build - Production Optimized) -->
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/tailwind.min.css">
  
  <style>
    body {
      background-color: #f4f2ee;
      font-family: "Inter", sans-serif;
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
  <div class="max-w-7xl mx-auto h-screen">
    <div class="grid md:grid-cols-2 items-center gap-8 h-full relative overflow-hidden">
      <!-- Login Form -->
      <div class="login-form-wrapper max-w-lg mx-auto w-full p-8 border border-gray-300 rounded-lg bg-white shadow-lg relative z-10">
      <form
        id="login-form" action="<?php echo url('login'); ?>" 
        method="POST">
        <div class="mb-8">
          <h3 class="text-gray-800 text-4xl font-extrabold mb-4">Sign in</h3>
       <p class="text-gray-600 text-sm md:text-md mb-4">
  Welcome to 
  <a href="<?php echo getBaseUrl(); ?>" 
     class="bg-gradient-to-r from-purple-500 to-pink-500 text-transparent bg-clip-text font-semibold">
     QuotesHub!
  </a> 
  Log in to share your thoughts, discover new quotes, and be inspired by a world of ideas.
</p>

          <?php if (!empty($error)): ?>
          <div class="alert alert-danger bg-red-200 text-red-800 border border-red-300 rounded-lg p-2 mb-4">
            <?php echo htmlspecialchars($error); ?>
          </div>
          <?php endif; ?>
        </div>

        <div class="mb-6">
          <label class="text-gray-800 text-sm mb-2 block">Username</label>
       <div class="relative">
          <input
          name="username"
          type="text"
          required
          class="w-full text-sm text-gray-800 bg-gray-100 focus:bg-white px-4 py-3 rounded-md outline-blue-600 border border-gray-300"
          placeholder="Enter your username (e.g., johndoe123)"
          autocomplete="username"
        />
        
                    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
</svg>


              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M4 7l1 14h14l1-14H4zM4 7v12m16-12v12" />
            </svg>
          </div>
        </div>

        <div class="mb-8">
          <label class="text-gray-800 text-sm mb-2 block">Password</label>
          <div class="relative">
            <input
              id="password_input"
              name="password"
              required
              type="password"
              class="w-full text-sm text-gray-800 bg-gray-100 focus:bg-white px-4 py-3 rounded-md outline-blue-600 border border-gray-300"
              placeholder="Enter password"
            autocomplete="current-password">
            <svg
              id="togglePassword"
              xmlns="http://www.w3.org/2000/svg"
              fill="#bbb"
              stroke="#bbb"
              class="w-5 h-5 absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer"
              viewBox="0 0 128 128"
            >
              <path
                d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"
                data-original="#000000"
              ></path>
            </svg>
          </div>
        </div>

        

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
        <button type="submit" class="w-full shadow-xl py-3 px-6 text-sm font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
          Log in
        </button>

        <p class="text-sm mt-8 text-center text-gray-800">
          Don't have an account? <a href="<?php echo url('register'); ?>" class="text-blue-600 font-semibold hover:underline ml-1">Register here</a>
        </p>
      </form>
       <div class="flex items-center justify-center mt-4 mb-3">
            <button id="forgotPasswordButton" class="text-blue-600 bg-transparent hover:underline text-sm">
               Have You Forgot your Password?
            </button>
        </div>
       </div>

      <!-- Image Section -->
      <div class="relative flex items-center justify-center h-full md:py-6 z-1">
        <img
          src="https://readymadeui.com/photo.webp"
          class="rounded-md w-full max-w-lg mx-auto object-cover"
          alt="Inspirational Quote"
        />
      </div>
    </div>
  </div>
    
            <!-- Modal -->
        <div id="forgotPasswordModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl">
                <h2 class="text-xl md:text-2xl font-semibold mb-4 text-center">Reset Your Password</h2>
                <p class="mb-4 text-gray-600 text-center" id="text_hide">Please enter your email address to reset your password.</p>
                <input type="email" id="emailInput" name="email" placeholder="Enter your registered Email Address" class="border border-2 border-blue-400 rounded w-full py-2 px-3 mb-4 focus:outline outline-2 outline-green-700">
                <div class="flex justify-between">
                    <button id="sendEmailButton" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">Send</button>
                    <button id="closeModalButton" class="text-gray-600 hover:text-gray-800 px-4 py-2 hover:bg-gray-300 rounded-lg border border-1 border-gray-400">Close</button>
                </div>
            </div>
        </div>
        
        
  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password_input');
      const icon = this;

      // Toggle the type attribute
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('w-5', 'h-5');
        icon.classList.add('w-6', 'h-6');
        icon.setAttribute('fill', '#000');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('w-6', 'h-6');
        icon.classList.add('w-5', 'h-5');
        icon.setAttribute('fill', '#bbb');
      }
    });
    
    
    $(document).ready(function() {
    // Show the modal when the button is clicked
    $('#forgotPasswordButton').on('click', function() {
        $('#forgotPasswordModal').removeClass('hidden');
        $('#emailInput').val(''); 
        $('#sendEmailButton').removeClass('hidden');
        $('#emailInput').show(); 
        $('#confirmationMessage').remove(); 
        $('#instructionText').show();
    });

    // Close the modal when the close button is clicked
    $('#closeModalButton').on('click', function() {
        $('#forgotPasswordModal').addClass('hidden'); 
    });
    
                $(document).on('click', function(event) {
                let modal = $('#forgotPasswordModal');
                if (!$(event.target).closest('.bg-white').length && modal.is(':visible')) {
                    modal.addClass('hidden');
                }
            });
            
            $(document).on('keydown', function(event) {
                if (event.key === 'Escape') {
                    $('#forgotPasswordModal').addClass('hidden'); // Hide the modal
                }
            });

    // Handle the email sending logic
    $('#sendEmailButton').on('click', function() {
        const email = $('#emailInput').val();
        
        $('#emailInput').hide(); 
        $('#sendEmailButton').hide(); 
        $('#instructionText').hide(); 
        $('#text_hide').hide(); 
        
    
         try {
            $.ajax({
                type: 'POST',
                url: '/app/views/users/send_reset_email.php', 
                data: { email: email },
                success: function(response) {
       
                    $('#forgotPasswordModal .bg-white').append(`<p id="confirmationMessage" class="text-green-600 text-center mt-4">${response}</p>`);
                    
                 
                    setTimeout(function() {
                        $('#forgotPasswordModal').addClass('hidden'); 
                    }, 3000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#forgotPasswordModal .bg-white').append(`<p class="text-red-600 text-center mt-4">An error occurred. Please try again.</p>`);
                    console.error('AJAX error:', textStatus, errorThrown);
                }
            });
        } catch (error) {
            console.error('An unexpected error occurred:', error.message);
            $('#forgotPasswordModal .bg-white').append(`<p class="text-red-600 text-center mt-4">Something went wrong. Please refresh the page and try again.</p>`);
        }
        


        
            // console.log(`Email sent to: ${email}`);
            });
});

  </script>
</body>

</html>
