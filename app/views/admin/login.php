<?php include __DIR__ . '/components/header.php'; ?>
<?php
require_once __DIR__ . '/../../config/utilis.php';
$baseUrl = getBaseUrl() . '/admin';
?>
<div class="bg-gray-200 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md mx-auto bg-white shadow-md rounded-lg p-8">
    <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Admin Login</h2>
    
       <!-- Display errors if any -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        

    <form action="<?php echo url('admin/login'); ?>" method="POST">
      <!-- CSRF Token -->
      <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
      
      <!-- Email Input -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" id="email" name="email"
          class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          placeholder="Enter your admin email" required>
      </div>

      <!-- Password Input -->
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" id="password" name="password"
          class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          placeholder="Enter your password" required>
      </div>

      <!-- Submit Button -->
      <div class="mb-4">
        <button type="submit"
          class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          Login
        </button>
      </div>

    </form>
  </div>
</div>


</body>

</html>