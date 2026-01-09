<?php include __DIR__ . '/components/header.php'; ?>
<!-- Navbar -->
<?php include __DIR__ . '/components/navbar-component.php'; ?>
<div class="wrapper  min-h-screen p-4 w-full">
  <div class="bg-white p-6 rounded-lg shadow-md w-full">
    <h1 class="text-2xl font-semibold mb-4 text-gray-800">Create New Blog</h1>

    <form action="<?php echo url('admin/create-blog'); ?>" method="POST">
      <!-- CSRF Token -->
      <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
      
      <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" id="title" name="title"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm"
          required>
      </div>    

      <div class="mb-4">
        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
        <textarea id="content" name="editor1" rows="6"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm"
          required></textarea>
      </div>

      <button type="submit"
        class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Submit</button>
    </form>
  </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('editor1');
</script>
<?php include __DIR__ . '/components/footer.php'; ?>
