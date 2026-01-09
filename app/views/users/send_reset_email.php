<?php
require_once __DIR__ . '/../../../config/utilis.php'; 
require_once __DIR__ . '/../../../config/database.php';

$email = $_POST['email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid Email !!";
    exit();
}

// Generate a random token
$token = bin2hex(random_bytes(16)); 
$link = getBaseUrl() . "forget-password?token=$token&email=$email";

// Check if the email exists in the database
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Store the token and its expiry time in the database
    $expiry_time = date("Y-m-d H:i:s", strtotime('+15 minutes')); // Token expiry time
    $update_stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_token_expiry=? WHERE email=?");
    $update_stmt->bind_param("sss", $token, $expiry_time, $email);
    
    if ($update_stmt->execute()) {
        $to = $email; // Send to the user's email
        $subject = "QuotesHub - Forgot Password";
        $message = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Forgot Password</title>
                <style>
                    body {
                        background-color: #f7fafc; /* Tailwind's gray-100 */
                        padding: 24px; /* Tailwind's p-6 */
                    }
                    .container {
                        max-width: 32rem; /* Tailwind's max-w-lg */
                        margin: auto;
                        background-color: #ffffff; /* Tailwind's white */
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Tailwind's shadow-md */
                        border-radius: 0.5rem; /* Tailwind's rounded-lg */
                        padding: 24px; /* Tailwind's p-6 */
                    }
                    .title {
                        font-size: 1.5rem; /* Tailwind's text-2xl */
                        font-weight: 600; /* Tailwind's font-semibold */
                        margin-bottom: 1rem; /* Tailwind's mb-4 */
                    }
                    .button {
                        display: inline-block;
                        background-color: black;    
                        font-weight: 600; /* Tailwind's font-semibold */
                        padding: 12px 24px; /* Tailwind's py-2 px-4 */
                        border-radius: 0.375rem; /* Tailwind's rounded */
                        text-decoration: none;
                        transition: background-color 0.2s;
                    }
                    a {
                        color: white !important;
                    }
                    .footer {
                        margin-top: 1rem; /* Tailwind's mt-4 */
                        color: #a0aec0; /* Tailwind's text-gray-500 */
                        font-size: 0.875rem; /* Tailwind's text-sm */
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1 class='title'>Forgot Password Request</h1>
                    <p>
                        You requested a password reset for your QuotesHub account: <strong>$email</strong>
                    </p>
                    <a href='$link' class='button'>Reset Your Password Here >>> </a>
                    <p class='footer'>
                        ----- This is a System Generated Email. Please Don't Reply To This Email -----
                    </p>
                </div>
            </body>
            </html>
        ";
        
        // Set headers to improve deliverability
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: QuotesHub <thequoteshubteam@thequoteshub.in>' . "\r\n";
        $headers .= 'Reply-To: no-reply@thequoteshub.in' . "\r\n"; // Add a reply-to address
        $headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n"; // Add X-Mailer header
        $headers .= 'X-Priority: 3' . "\r\n"; // Normal priority
        $headers .= 'List-Unsubscribe: <mailto:no-reply@thequoteshub.in>' . "\r\n"; // List-Unsubscribe header

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            echo "A reset link has been sent to <strong>$email</strong>.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "Error updating token.";
    }
} else {
    echo "Account Not Exist On This Email !!";
}

// After password reset, send confirmation email
function sendConfirmationEmail($email) {
    $subject = "QuotesHub - Password Reset Successful";
    $message = "
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Reset Successful</title>
            <style>
                body {
                    background-color: #f7fafc;
                    padding: 24px;
                }
                .container {
                    max-width: 32rem;
                    margin: auto;
                    background-color: #ffffff;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    border-radius: 0.5rem;
                    padding: 24px;
                }
                .title {
                    font-size: 1.5rem;
                    font-weight: 600;
                    margin-bottom: 1rem;
                }
                .footer {
                    margin-top: 1rem;
                    color: #a0aec0;
                    font-size: 0.875rem;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1 class='title'>Your Password Has Been Reset</h1>
                <p>Your password has been reset successfully. If you did not request this change, please contact our support team immediately.</p>
                <p class='footer'>----- This is a System Generated Email. Please Don't Reply To This Email -----</p>
            </div>
        </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: QuotesHub <thequoteshubteam@thequoteshub.in>' . "\r\n";
    $headers .= 'Reply-To: no-reply@thequoteshub.in' . "\r\n"; 
    $headers .= 'X-Mailer: PHP/' . phpversion(); 
    $headers .= 'X-Priority: 3' . "\r\n"; // Normal priority
    $headers .= 'List-Unsubscribe: <mailto:no-reply@thequoteshub.in>' . "\r\n"; // List-Unsubscribe header

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Call this function after the password has been reset successfully
// Example: after updating the user's password in your reset logic
if ($passwordResetSuccessful) {
    sendConfirmationEmail($email);
}
?>
