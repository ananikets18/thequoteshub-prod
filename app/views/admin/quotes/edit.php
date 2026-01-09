<?php include __DIR__ . '/../components/header.php'; ?>

<?php
// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin/login");
    exit();
}
?>


  <?php include __DIR__ . '/../components/navbar-component.php'; ?>

<main class="container mx-auto my-4 p-4">
    <h1 class="text-3xl font-bold mb-6">Edit Quote</h1>

    <?php if ($quote): ?>
    <form action="<?php echo url('admin/editquote/' . $quote['id']); ?>" method="post" enctype="multipart/form-data" class="space-y-6">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <!-- Author Name -->
        <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md">
            <label for="author" class="block text-lg font-medium mb-2">Author Name</label>
            <input type="text" name="author" id="author" value="<?php echo decodeAndCleanText($quote['author_name']); ?>"
                class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
        </div>

        <!-- Quote Text -->
        <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md">
            <label for="quote_text" class="block text-lg font-medium mb-2">Quote Text</label>
            <textarea name="quote_text" id="quote_text" rows="4"
                class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo decodeAndCleanText($quote['quote_text']); ?></textarea>
                
                  
        </div>
        
         <!-- Categories -->
        <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md">
            <label class="block text-lg font-medium mb-2">Categories</label>
            <p class="bg-green-100 w-fit p-2 border rounded-lg my-2">Already assigned categories: 
              <span class="underline font-semibold"><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['category_name'])); ?></span>  
            </p>
       <div class="bg-white p-6 rounded-lg shadow-md">
                <label class="block text-lg font-medium mb-2">Select Category to Change</label>
              <select name="category_id" id="categories"
                    class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select a Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                            <?php echo ($category['id'] == $quote['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
        </div>


        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full bg-blue-500 text-white px-4 py-3 rounded-md text-lg font-semibold shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Update Quote
            </button>
        </div>
    </form>

    <?php else: ?>
    <div class="bg-red-100 text-red-800 border border-red-300 rounded p-4 mt-6">
        <p>Quote not found.</p>
    </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../components/footer.php'; ?>
