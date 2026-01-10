<?php include __DIR__ . '/../components/header.php'; ?>
<?php
require_once __DIR__ . '/../../config/utilis.php';
$baseUrl = getBaseUrl();
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $description = htmlspecialchars($_POST['description']);

  $to = 'thequoteshubteam@thequoteshub.in';
  $subject = 'Contact Us Message from ' . $email;
  $message = "Email: " . $email . "\n\nMessage:\n" . $description;

  // Additional headers
  $headers = "From: QuotesHub <no-reply@thequoteshub.in>\r\n";
  $headers .= "Reply-To: " . $email . "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
  $headers .= "X-Priority: 1\r\n";
  $headers .= "X-Sender-IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
  $headers .= "X-Feedback-ID: 1:quoteshub\r\n"; // For better email feedback tracking

  // Send email
  if (mail($to, $subject, $message, $headers)) {
    echo 'Message sent successfully!';
  } else {
    // Log error message
    error_log('Error sending message: ' . error_get_last()['message']);
    http_response_code(500);
    echo 'Error sending message.';
  }
}
?>


<!-- Header Section -->
<header class="bg-white shadow-lg py-6">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-bold text-center text-gray-900">Contact Us</h1>
    <p class="text-center text-lg text-gray-600 mt-2">We’d love to hear from you! Reach out to us with your questions or
      feedback.</p>
  </div>
</header>

<!-- Contact Form Section -->
<section class="py-12">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg p-6 sm:p-8 lg:p-10">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Get in Touch</h2>
      <form id="contactForm">
        <!-- Email Input -->
        <div class="mb-6">
          <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
          <input type="email" id="email" name="email" required placeholder="Your email address"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Description Textarea -->
        <div class="mb-6">
          <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Description</label>
          <textarea id="description" name="description" rows="4" required placeholder="Your message or feedback"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Send
          Message</button>
        <div id="responseMessage" class="mt-4"></div>
      </form>
    </div>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-white py-6">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <p class="text-gray-500 text-sm">© <span><?php echo date("Y"); ?></span>  TheQuotesHub. All Rights Reserved.</p>
  </div>
</footer>

<script>
$(document).ready(function() {
  $('#contactForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      url: '', // No need to specify URL since it's the same page
      type: 'POST',
      data: formData,
      success: function(response) {
        $('#responseMessage').html(
          '<p class="text-green-500">Your message has been sent successfully!</p>');
        $('#contactForm')[0].reset();
      },
      error: function() {
        $('#responseMessage').html(
          '<p class="text-red-500">There was an error sending your message. Please try again.</p>');
      }
    });
  });
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>