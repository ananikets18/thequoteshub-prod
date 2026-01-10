<?php include __DIR__ . '/../components/header.php'; ?>
<?php
require_once __DIR__ . '/../../config/utilis.php';
$baseUrl = getBaseUrl();
?>
<!-- Header Section -->
<header class="bg-white shadow-lg py-6">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-bold text-center text-gray-900">Feedback</h1>
    <p class="text-center text-lg text-gray-600 mt-2">We value your feedback. Please let us know your thoughts on our
      website.</p>
  </div>
</header>

<!-- Feedback Form Section -->
<section class="py-12">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg p-6 sm:p-8 lg:p-10">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">We’d Love to Hear Your Thoughts!</h2>
      <form id="feedbackForm">
        <!-- Name Input -->
        <div class="mb-6">
          <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Name (Optional)</label>
          <input type="text" id="name" name="name" placeholder="Your name (optional)"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Email Input -->
        <div class="mb-6">
          <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
          <input type="email" id="email" name="email" required placeholder="Your email address"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Feedback Textarea -->
        <div class="mb-6">
          <label for="feedback" class="block text-gray-700 text-sm font-medium mb-2">Feedback</label>
          <textarea id="feedback" name="feedback" rows="4" required placeholder="Your feedback"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Submit
          Feedback</button>
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
  $('#feedbackForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      url: '', // No need to specify URL since it's the same page
      type: 'POST',
      data: formData,
      success: function(response) {
        $('#responseMessage').html('<p class="text-green-500">Thank you for your feedback!</p>');
        $('#feedbackForm')[0].reset();
      },
      error: function() {
        $('#responseMessage').html(
          '<p class="text-red-500">There was an error submitting your feedback. Please try again.</p>');
      }
    });
  });
});
</script>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $feedback = htmlspecialchars(trim($_POST['feedback']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo 'Invalid email format.';
        exit;
    }

    // Prepare email components
    $to = 'thequoteshubteam@thequoteshub.in';
    $subject = 'Feedback from ' . ($name ?: 'Anonymous') . ' (' . $email . ')';
    $message = "Name: " . ($name ?: 'Anonymous') . "\nEmail: " . $email . "\n\nFeedback:\n" . $feedback;

    // Set headers to avoid spam filters
    $headers = "From: QuotesHub <no-reply@thequoteshub.in>\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "X-Priority: 3\r\n"; // Normal priority
    $headers .= "X-Sender-IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo 'Feedback submitted successfully!';
    } else {
        http_response_code(500);
        echo 'Error submitting feedback. Please try again later.';
    }
}
?>

<?php include __DIR__ . '/../components/footer.php'; ?>