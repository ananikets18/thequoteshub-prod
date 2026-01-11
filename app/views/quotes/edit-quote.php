<?php 
include __DIR__ . '/../components/header.php';
include_once __DIR__ . '/../../../config/utilis.php';
?>

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: /login");
  exit();
}
?>


<main class="container mx-auto my-4 p-4">
  <h1 class="text-3xl font-bold mb-6">Edit Quote</h1>
    <?php
          // Display the message if it's set
          if (isset($_SESSION['message'])) {
            echo "<div class='bg-green-100 text-green-800 border border-green-300 rounded p-4 mb-6'>";
            echo $_SESSION['message'];
            echo "</div>";
        
            // Unset the message so it doesn't persist on page refresh
            unset($_SESSION['message']);
        
            // Include a script to redirect after a delay
            echo "<script>
                        setTimeout(function() {
                            window.location.href = '/dashboard';
                        }, 1500); // Delay of 3000 milliseconds (1.5 seconds)
                      </script>";
          }
          ?>


  <?php if (isset($quote) && $quote): ?>
  <form action="<?php echo url('edit-quote/' . $quote['id']); ?>" method="post" enctype="multipart/form-data"
    class="space-y-6">
    
    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

    <!-- Author Name -->
    <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md">
      <label for="author" class="block text-lg font-medium mb-2">Author Name</label>
      <input type="text" name="author" id="author" value="<?php echo htmlspecialchars(decodeAndCleanText($quote['author_name']), ENT_QUOTES, 'UTF-8'); ?>"
        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required>
    </div>

    <!-- Quote Text -->
    <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md">
      <label for="quote_text" class="block text-lg font-medium mb-2">Quote Text</label>
      <textarea name="quote_text" id="quote_text" rows="4"
        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars(decodeAndCleanText($quote['quote_text']), ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>

    <!-- Submit Button -->
    <div>
      <button type="submit"
        class="w-full bg-blue-500 text-white px-4 py-3 rounded-md text-lg font-semibold shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">Update
        Quote</button>
    </div>
  </form>

  <?php else: ?>
  <div class="bg-red-100 text-red-800 border border-red-300 rounded p-4 mt-6">
    <p>Quote not found.</p>
  </div>
  <?php endif; ?>
</main>


<?php include __DIR__ . '/../components/footer.php'; ?>