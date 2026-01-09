<?php include __DIR__ . '/../components/header.php'; ?>
  <?php $baseUrl = getBaseUrl(); ?>
<style>
#image_preview {
  width: 10rem !important;
  height: 10rem !important;
  border-radius: 50%;
  object-fit: cover;
}
</style>
<div class="container mx-auto my-8 p-4">
  <div class="max-w-3xl mx-auto bg-white p-6 border border-gray-200 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Update Profile</h2>
    <span class="text-sm text-green-600 font-semibold">**This information will be displayed publicly so be careful what you share.</span>

    <!-- Form Message Placeholder -->
    <div id="form-message" class="mb-4"></div>

    <form id="profile_form" class="space-y-6" enctype="multipart/form-data">
      <!-- Name field -->
      <div>
        <label for="name" class="block text-lg font-medium mb-2">Name</label>
        <input type="text"
          class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
      </div>

      <!-- Email field -->
      <div>
        <label for="email" class="block text-lg font-medium mb-2">Email</label>
        <input type="email"
          class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
      </div>

      <!-- Username field -->
      <div>
        <label for="username" class="block text-lg font-medium mb-2">Username</label>
        <input type="text"
          class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
      </div>
      <!-- Profile Image Upload -->
            <div class="lg:flex lg:items-center lg:space-x-6">
              <div class="lg:w-1/3">
                <label for="profile_image" class="block text-lg font-medium mb-2">Profile Image</label>
                
                <!-- Display previously uploaded image if exists -->
                 
                 <?php if (!empty($user['user_img'])): ?>
                          <!-- Display user's uploaded profile image -->
                          <img id="image_preview"  src="<?php echo $baseUrl; ?>public/uploads/users/<?php echo htmlspecialchars($user['user_img']); ?>" 
                               alt="Current Profile Image" 
                               class="shadow-md rounded-full w-32 h-32 object-cover">
                <?php else: ?>
                          <!-- Display default placeholder image -->
                          <img id="image_preview"  src="<?php echo $baseUrl; ?>public/uploads/authors_images/placeholder.png" 
                               alt="Profile Image" 
                               class="shadow-md rounded-full w-32 h-32 object-cover">
                               
                <?php endif; ?>
              </div>
            
              <!-- File input for new image upload -->
              <div class="mt-4 lg:mt-0 lg:w-2/3">
                <input type="file" 
                  class="block w-full text-sm text-gray-700 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                  id="profile_image" name="profile_image" accept="image/*" aria-label="Upload your profile image">
              </div>
            </div>

      <!-- Bio field -->
      <div>
        <label for="bio" class="block text-lg font-medium mb-2">Bio</label>
        <textarea
          class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          name="bio" id="bio" rows="4"  placeholder="Tell us something about yourself..." maxlength="200" aria-label="User bio field"><?php echo htmlspecialchars($user['bio']); ?></textarea>
      </div>



      <!-- Submit Button -->
      <div>
        <button type="submit"
          class="w-full bg-blue-500 text-white px-4 py-3 rounded-md text-lg font-semibold shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">Update
          Profile</button>
      </div>
    </form>
  </div>
</div>

<!-- jQuery and AJAX Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $('#profile_form').on('submit', function(event) {
    event.preventDefault();

    let formData = new FormData(this); 

    $.ajax({
      url: '/profile',
      type: 'POST',
      data: formData,
      contentType: false, 
      processData: false, 
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          $('#form-message').html(
            '<div class="bg-green-100 text-green-800 border border-green-300 rounded p-4">' + response
            .message + '</div>');
          setTimeout(function() {
            window.location.href = '/profile';
          }, 2000);
        } else {
          $('#form-message').html(
            '<div class="bg-red-100 text-red-800 border border-red-300 rounded p-4">' + response
            .message + '</div>');
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', status, error);
        console.error('Response text:', xhr.responseText); 
        $('#form-message').html(
          '<div class="bg-red-100 text-red-800 border border-red-300 rounded p-4">An unexpected error occurred. Please try again.</div>'
        );
      }
    });
  });

  // Image preview
  $('#profile_image').on('change', function() {
    let file = this.files[0];
    if (file) {
      let reader = new FileReader();

      reader.onload = function(e) {
        $('#image_preview').attr('src', e.target.result).removeClass('hidden'); 
      };

      reader.readAsDataURL(file);
    }
  });

});

</script>




<?php include __DIR__ . '/../components/footer.php'; ?>