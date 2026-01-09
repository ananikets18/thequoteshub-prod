<?php
include __DIR__ . '/../components/header.php';
include_once  __DIR__ . '/../../../config/utilis.php';

$baseUrl = getBaseUrl(); // Replace with your actual base URL
?>

<div class="container mx-auto my-4 p-4">
  <h1 class="text-2xl font-bold mb-6">Create a New Quote</h1>

  <?php if (!empty($errors)): ?>
  <div class="bg-red-200 text-red-800 border border-red-300 rounded p-4 mb-6">
    <ul class="list-disc list-inside">
      <?php foreach ($errors as $error): ?>
      <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php elseif (!empty($message)): ?>
  <div class="bg-green-200 text-green-800 border border-green-300 rounded p-4 mb-6">
    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
  </div>
  <?php endif; ?>

<form method="POST" class="space-y-6">
  <!-- Author and Quote Section -->
  <div class="flex flex-col sm:flex-row sm:space-x-4">
    <!-- Author Name -->
    <div class="flex-1 bg-white p-4 border border-2 rounded-lg">
      <label for="author_name" class="block text-lg font-medium mb-2">Author Name</label>
      <input type="text" placeholder="e.g., William Shakespeare"
        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        id="author_name" name="author_name" aria-label="Enter author name" required>
    </div>

    <!-- Quote Text -->
    <div class="flex-1 p-4 bg-white border border-2 rounded-lg">
      <label for="quote_text" class="block text-lg font-medium mb-2">Quote Text</label>
      <textarea
        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="e.g., To be, or not to be, that is the question..." id="quote_text" name="quote_text" rows="3" 
        aria-label="Enter the quote text" required></textarea>
    </div>
  </div>

  <!-- Category and New Category Section -->
  <div class="flex flex-col sm:flex-row sm:space-x-4">
    <!-- Existing Category -->
    <div class="flex-1 p-4 bg-white border border-2 rounded-lg">
      <label for="category_id" class="block text-lg font-medium mb-2">Category</label>
      <select
        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        id="category_id" name="category_id" aria-label="Select a category" required>
        <option value="0">Select a category</option>
        <?php
        // Fetch categories from the database
        $categories = $this->quoteModel->getAllCategories();
        foreach ($categories as $category) {
          echo "<option value=\"{$category['id']}\">{$category['category_name']}</option>";
        }
        ?>
      </select>
    </div>

    <!-- New Category -->
    <div class="flex-1 p-4 bg-white border border-2 rounded-lg">
      <label for="new_category" class="block text-lg font-medium mb-2">Or create a new category</label>
      <input type="text" placeholder="Create a new Category"
        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        id="new_category" name="new_category" aria-label="Create a new category">
    </div>
  </div>

  <!-- Submit Button -->
  <div>
    <button type="submit"
      class="w-full bg-blue-500 text-white px-4 py-3 rounded-md text-lg font-semibold shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
      Create Quote
    </button>
  </div>
</form>


</div>


<?php include __DIR__ . '/../components/footer.php'; ?>