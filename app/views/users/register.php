<?php
require_once __DIR__ . '/../../../config/utilis.php';
$csrfToken = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Create Your Account | theQuoteshub - Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟 </title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <script src="https://accounts.google.com/gsi/client" async defer></script>

    <?php $favUrl = getBaseUrl(); ?>
  
    <link rel="canonical" href="<?php echo getBaseUrl(); ?>register" />
    
    <link rel="icon" type="image/x-icon" href="<?php echo $favUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $favUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favUrl; ?>public/uploads/images/favicon/favicon-16x16.png">

  <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
        </script>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Nunito", sans-serif;
    }

    body {
      overflow-x: hidden; /* Prevent horizontal overflow */
    }

    .background-radial-gradient {
      background-color: hsl(218, 41%, 15%);
      background-image: radial-gradient(650px circle at 0% 0%,
          hsl(218, 41%, 35%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%),
        radial-gradient(1250px circle at 100% 100%,
          hsl(218, 41%, 45%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%);
    }

    #radius-shape-1 {
      height: 220px;
      width: 220px;
      top: -60px;
      left: -130px;
      background: radial-gradient(#44006b, #ad1fff);
      overflow: hidden;
    }

    #radius-shape-2 {
      border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
      bottom: -60px;
      right: -110px;
      width: 300px;
      height: 300px;
      background: radial-gradient(#44006b, #ad1fff);
      overflow: hidden;
    }

    .bg-glass {
      background-color: hsla(0, 0%, 100%, 0.9) !important;
      backdrop-filter: saturate(200%) blur(25px);
    }

    .register_quote_text {
      font-size: 1.25rem;
    }

    /* Prevent horizontal overflow on small screens */
    @media (max-width: 768px) {
      .background-radial-gradient {
        overflow-x: hidden;
      }

      #radius-shape-1, #radius-shape-2 {
        display: none; /* Hide shapes on small screens to prevent overflow */
      }
    }
  </style>
</head>

<body>

  <!-- Section: Design Block -->
  <section class="background-radial-gradient overflow-auto responsive_overflow" style="height: 100vh;">
    <div class="container px-4 py-5 px-md-5 text-lg-start my-5">
      <div class="row gx-lg-5 align-items-center mb-5">
        <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
          <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
            Welcome to<br />
            <span style="color: hsl(218, 81%, 75%)">QuotesHub</span>
          </h1>
          <p class="mb-4 opacity-70 register_quote_text" style="color: hsl(218, 81%, 85%); font-size: 1.25rem;">
            "Every new beginning is a step closer to your dreams. Welcome to the first chapter of your journey!" 🌟
          </p>
          <p>
            <a href="<?php echo getBaseUrl(); ?>" class="btn btn-outline-light btn-sm" style="font-size: 1.1rem;">Back to Home</a>
          </p>
        </div>

        <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
          <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
          <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

          <div class="card bg-glass">
            <div class="card-body px-4 py-5 px-md-5">
              <div id="form-message"></div>
                                  
                    <!-- Google Sign Up Button -->
                        <div id="g_id_onload" data-client_id="893434650668-dvffkomhrigkakb8aga96cv6t92p9jqr.apps.googleusercontent.com" data-context="signin" data-ux_mode="popup" data-callback="handleCredentialResponse"></div>
                        <div class="g_id_signin btn btn-light btn-block mb-4" data-type="standard" style="border: 1px solid #ccc;">
                          <i class="bi bi-google me-2"></i> Sign up with Google
                        </div>
                    
                        <!-- Divider or Text -->
                        <p class="text-center text-muted mb-4" style="font-size: 1.1rem;">or</p>


              <form id="register-form">
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <label class="form-label" for="name_input">Name</label>
                      <input type="text" name="name" id="name_input" placeholder="Enter name"
                        class="form-control" required />
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <label class="form-label" for="username_input">Username</label>
                      <input type="text" name="username" id="username_input"
                        placeholder="Choose a unique username" class="form-control" required />
                    </div>
                  </div>
                </div>
                <div class="form-outline mb-4">
                  <label class="form-label" for="email_input">Email address</label>
                  <input type="email" name="email" id="email_input" placeholder="e.g., user@example.com"
                    class="form-control" required  inputmode="email" />
                </div>

            <div class="form-outline mb-4 position-relative">
              <label class="form-label" for="password_input">Password</label>
              <input type="password" name="password" id="password_input" placeholder="Password" class="form-control" required minlength="8" maxlength="20" />
              
              <span id="togglePassword" class="position-absolute" style="right: 10px; top: 38px; cursor: pointer;">
                <i class="bi bi-eye-slash" id="toggleIcon"  style="font-weight: 600;"></i>
              </span>
            </div>

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">


                <button type="submit" class="btn btn-primary btn-block mb-4">
                  Sign up
                </button>

                <p class="text-center mt-2">
                  <span class="text-muted" style="font-size: 1.1rem;">Already registered ?</span>
                  <a href="<?php echo url('login'); ?>" class="fw-bold"
                    style="font-size: 1.2rem; color: hsl(218, 81%, 50%)">Login here</a>
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#register-form').on('submit', function (event) {
        event.preventDefault(); 

        $.ajax({
          url: '/register',
          type: 'POST',
          data: $(this).serialize(),
          dataType: 'json',
          success: function (response) {
            $('#form-message').empty();
            if (response.status === 'success') {
              $('#form-message').html('<div class="alert alert-success">' + response.message + '</div>');
              setTimeout(function () {
                window.location.href = '<?php echo getBaseUrl(); ?>login'; 
              }, 2000);
            } else {
              $('#form-message').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
          },
          error: function () {
            $('#form-message').html('<div class="alert alert-danger">An unexpected error occurred. Please try again.</div>');
          }
        });
      });
    });
    
    
    // Toggle the password visibility
document.getElementById('togglePassword').addEventListener('click', function (e) {
    // Get the input field and the icon
    const passwordInput = document.getElementById('password_input');
    const icon = document.getElementById('toggleIcon');
    
    // Toggle between 'password' and 'text' input type
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }
});

function handleCredentialResponse(response) {
    // Decode JWT token
    const data = parseJwt(response.credential);

    // Extract only the 'name' and 'email' fields
    const userInfo = {
        name: data.name,
        email: data.email
    };

    // Manually create a serialized string
    const serializedData = $.param({
        google_token: response.credential,
        name: userInfo.name,
        email: userInfo.email
    });

    // Log serialized data for debugging
    console.log("Serialized data:", serializedData);

    // Make an AJAX call to register the user with Google
   $.ajax({
    url: '/registerWithGoogle',
    type: 'POST',
    contentType: 'application/json', // Sending JSON format
    data: JSON.stringify({
        google_token: response.credential,
        name: userInfo.name,
        email: userInfo.email
    }),
    dataType: 'json', // Expecting JSON response
    success: function (response) {
        $('#form-message').empty();
        if (response.status === 'success') {
            $('#form-message').html('<div class="alert alert-success">' + response.message + '</div>');
            setTimeout(function () {
                window.location.href = '<?php echo getBaseUrl(); ?>dashboard';
            }, 2000);
        } else {
            $('#form-message').html('<div class="alert alert-danger">' + response.message + '</div>');
        }
    },
    error: function (xhr, status, error) {
        console.error("AJAX Error:", status, error);
        console.error("Response Text:", xhr.responseText);
        $('#form-message').html('<div class="alert alert-danger">An unexpected error occurred. Please try again.</div>');
    }
});

}


function parseJwt(token) {
    // Split the JWT into its components
    const base64Url = token.split('.')[1];
    // Decode the Base64 URL-encoded string
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    // Convert it to a JSON object
    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload); // Return the parsed JSON object
}



  </script>
</body>

</html>
