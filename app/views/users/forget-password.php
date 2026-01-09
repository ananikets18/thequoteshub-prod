<?php

require_once __DIR__ . '/../../../config/database.php'; // Adjust the path as necessary

// Check if the token and email parameters are present in the URL
if (!isset($_GET['token']) || !isset($_GET['email'])) {
    echo "Invalid token or email.";
    exit();
}

$token = htmlspecialchars($_GET['token']);
$email = htmlspecialchars($_GET['email']);

// Initialize a variable for the message
$message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];

    // Validate the new password (e.g., minimum length)
    if (strlen($new_password) < 6) {
        echo "Password must be at least 6 characters.";
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the password and reset token in the database
    $stmt = $conn->prepare("UPDATE users SET password_hash=?, reset_token=NULL, reset_token_expiry=NULL WHERE email=? AND reset_token=? AND reset_token_expiry > NOW()");
    $stmt->bind_param("sss", $hashed_password, $email, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Set the success message
        $message = "Password successfully updated. You will be redirected to the login page in 3 seconds.";
    } else {
        $message = "Invalid token or error updating password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Tailwind CSS CDN -->
    <!-- Tailwind removed - use base layout or local CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-lg w-full bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Reset Your Password</h2>
        <?php if ($message): ?>
            <p class="text-green-600 mb-4"><?php echo $message; ?></p>
            <script>
                // Redirect to login after 3 seconds
                setTimeout(function() {
                    window.location.href = '/login';
                }, 3000);
            </script>
        <?php else: ?>
            <form method="POST" action="" class="space-y-4">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">

                <label for="new_password" class="block mb-2 text-sm font-medium text-gray-700">New Password:</label>
                <div class="relative">
                    <input type="password" placeholder="Create New Password" id="new_password" name="new_password" required class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300">
                    <button type="button" id="togglePassword" class="absolute right-4 top-2 text-gray-600 hover:text-gray-800">
                        <i id="eyeIcon" class="bi bi-eye" style="font-size: 1.25rem;"></i> <!-- Bootstrap "eye" icon -->
                    </button>
                </div>

                <button type="submit" class="inline-block bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 w-full">
                    Reset Password
                </button>
            </form>
        <?php endif; ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                var passwordInput = $('#new_password');
                var inputType = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', inputType);

                // Toggle between Bootstrap "eye" and "eye-slash" icons
                if (inputType === 'text') {
                    $('#eyeIcon').removeClass('bi-eye').addClass('bi-eye-slash'); // Change to eye-slash when showing password
                } else {
                    $('#eyeIcon').removeClass('bi-eye-slash').addClass('bi-eye'); // Change back to eye when hiding password
                }
            });
        });
    </script>
</body>
</html>
