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
    <div class="max-w-6xl mx-auto bg-white p-6 border border-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Add Author Information</h2>
        <span class="text-sm text-green-600 font-semibold">**This information will be displayed publicly, so be careful what you share.</span>

        <!-- Form Message Placeholder -->
        <div id="form-message" class="mb-4"></div>

       <form id="author_form" action="<?php echo url('save-author'); ?>" method="POST" enctype="multipart/form-data"  class="space-y-6">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

            <!-- Author Name and Profession -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Author Name -->
                <div>
                    <label for="author_name" class="block text-lg font-medium mb-2">Author Name</label>
                    <input type="text" id="author_name" name="author_name"
                        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?php echo htmlspecialchars($authorName); ?>" readonly required>
                </div>

                <!-- Profession -->
                <div>
                    <label for="profession" class="block text-lg font-medium mb-2">Profession</label>
                    <input type="text" id="profession" name="profession" placeholder="Job or Discovery"
                        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-lg font-medium mb-2">Description</label>
                <textarea id="description" name="description" placeholder="Ex: A member of the wealthy South African Musk family, Musk was born in Pretoria and briefly attended the University of Pretoria. At the age of 18..."
                    class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4"></textarea>
            </div>
    
       <!-- Author Image Upload -->
                <div class="lg:flex lg:items-center lg:space-x-6">
                    <div class="lg:w-1/3">
                        <label for="author_image" class="block text-lg font-medium mb-2">Author Image</label>
    
                        <?php if (!empty($authorDetails['image'])): ?>
                        <img id="image_preview" src="<?php echo $baseUrl; ?>public/uploads/authors_images/<?php echo htmlspecialchars($authorDetails['image']); ?>"
                            alt="Current Author Image" class="shadow-md rounded-full">
                        <?php else: ?>
                        <img id="image_preview" src="<?php echo $baseUrl; ?>public/uploads/authors_images/placeholder.png"
                            alt="Author Image Placeholder" class="shadow-md rounded-full">
                        <?php endif; ?>
                    </div>
    
                    <div class="mt-4 lg:mt-0 lg:w-2/3">
                        <input type="file" id="author_image" name="author_image" accept="image/*"
                            class="block w-full text-sm text-gray-700 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>


            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-3 rounded-md text-lg font-semibold shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- jQuery and AJAX Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#author_form').on('submit', function (event) {
            event.preventDefault();


            let formData = new FormData(this);

            $.ajax({
                url: '/save-author',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                console.log(response); // Check the response structure in the console
    
            if (response.status === 'success') {
                $('#form-message').html(
                    '<div class="bg-green-100 text-green-800 border border-green-300 rounded p-4">' + response.message + '</div>'
                );
                setTimeout(function () {
                    window.location.href = '/update-authors';
                }, 2000);
            } else {
                $('#form-message').html(
                    '<div class="bg-red-100 text-red-800 border border-red-300 rounded p-4">' + response.message + '</div>'
                );
            }
        },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    console.error('Response text:', xhr.responseText);
                    $('#form-message').html(
                        '<div class="bg-red-100 text-red-800 border border-red-300 rounded p-4">An unexpected error occurred. Please try again.</div>'
                    );
                }
            });
        });

        // Image preview
        $('#author_image').on('change', function () {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#image_preview').attr('src', e.target.result);
                };

                reader.readAsDataURL(file);
            }
        });
    });
</script>

    <?php include __DIR__ . '/../components/footer.php'; ?>

