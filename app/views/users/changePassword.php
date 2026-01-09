<?php include __DIR__ . '/../components/header.php'; ?>

<div class="container mx-auto my-8 p-4">
  <div class="max-w-md mx-auto bg-white p-6 border border-gray-200 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Change Password</h2>

    <!-- Form Message Placeholder -->
    <div id="form-message" class="mb-4"></div>

    <form id="password_form" class="space-y-6">
      <!-- Current Password -->
      <div class="relative">
        <label for="current_password" class="block text-lg font-medium mb-2">Current Password</label>
        <input type="password" placeholder="Current Password"
          class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          id="current_password" name="current_password" required>
        <span class="absolute inset-y-0 top-8 right-0 flex items-center pr-3 cursor-pointer" id="toggleCurrentPassword">
          <i class="fas fa-eye-slash"></i>
        </span>
      </div>

      <!-- New Password -->
      <div class="relative">
        <label for="new_password" class="block text-lg font-medium mb-2">New Password</label>
        <input type="password" placeholder="New Password"
          class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          id="new_password" name="new_password" required>
        <span class="absolute inset-y-0 top-8 right-0 flex items-center pr-3 cursor-pointer" id="toggleNewPassword">
          <i class="fas fa-eye-slash"></i>
        </span>
      </div>

      <!-- Confirm New Password -->
      <div class="relative">
        <label for="confirm_password" class="block text-lg font-medium mb-2">Confirm New Password</label>
        <input type="password" placeholder="Confirm New Password"
          class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          id="confirm_password" name="confirm_password" required>
        <span class="absolute inset-y-0  top-8 right-0 flex items-center pr-3 cursor-pointer"
          id="toggleConfirmPassword">
          <i class="fas fa-eye-slash"></i>
        </span>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit"
          class="w-full bg-blue-500 text-white px-4 py-3 rounded-md text-lg font-semibold shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">Change
          Password</button>
      </div>
    </form>
  </div>
</div>

<!-- FontAwesome Icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<!-- jQuery and Ajax Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $('#password_form').on('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    $.ajax({
      url: '/changePassword',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          $('#form-message').html(
            '<div class="bg-green-100 text-green-800 border border-green-300 rounded p-4">' + response
            .message + '</div>');
          setTimeout(function() {
            window.location.href = '/changePassword'; // Redirect on success
          }, 2000); // Adjust delay as needed
        } else {
          $('#form-message').html(
            '<div class="bg-red-100 text-red-800 border border-red-300 rounded p-4">' + response
            .message + '</div>');
        }
      },
      error: function() {
        $('#form-message').html(
          '<div class="bg-red-100 text-red-800 border border-red-300 rounded p-4">An unexpected error occurred. Please try again.</div>'
        );
      }
    });
  });

  // Toggle Password Visibility
  function togglePasswordVisibility(toggleId, inputId) {
    $(toggleId).on('click', function() {
      let input = $(inputId);
      let icon = $(this).find('i');
      if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
      } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
      }
    });
  }

  togglePasswordVisibility('#toggleCurrentPassword', '#current_password');
  togglePasswordVisibility('#toggleNewPassword', '#new_password');
  togglePasswordVisibility('#toggleConfirmPassword', '#confirm_password');
});
</script>



<?php include __DIR__ . '/../components/footer.php'; ?>