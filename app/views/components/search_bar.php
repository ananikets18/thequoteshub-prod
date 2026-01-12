<?php include_once __DIR__ . '/../../../config/database.php';
include_once  __DIR__ . '/../../../config/utilis.php';
?>

<div class="search_bar_wrapper w-full mt-2 lg:mt-0 lg:w-auto lg:ml-4 relative">
  <div class="relative">
    <input type="text" id="search_query" placeholder="Explore Quoteshub....."
      class="search-input w-full lg:max-w-sm pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition outline-none" />
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 21l-4.35-4.35M11 18a7 7 0 110-14 7 7 0 010 14z"></path>
      </svg>
    </div>
  </div>

  <!-- Search Results -->
  <div id="search_results" class="mt-2 absolute top-46 bg-white w-full lg:max-w-sm h-auto rounded-lg border-2 z-10 p-1.5" style="display: none;">
    <!-- Results will appear here -->
  </div>
</div>

<script>
// Declare baseUrl only if not already defined
if (typeof baseUrl === 'undefined') {
    var baseUrl = '<?php echo url(''); ?>';
}

$(document).ready(function () {
    $('#search_query').on('input', function () {
        let query = $(this).val().trim();

        if (query.length > 2) {
            $.ajax({
                url: baseUrl + 'search',
                type: 'GET',
                data: { q: query },
                success: function (data) {
                    try {
                        // jQuery automatically parses JSON when Content-Type is application/json
                        // So 'data' is already a JavaScript object, no need to parse it
                        let response = data;

                        if (response.status === 'success') {
                            let resultsHTML = '';

                            response.results.forEach(function (result) {
                                if (result.type === 'quote') {
                                    resultsHTML += `
                                        <a href="${baseUrl}quote/${result.id}" class="block hover:bg-gray-100 p-2 rounded">
                                            <div class="search-result-item">
                                                <p class="text-lg font-semibold text-gray-800 mb-1">${result.content}</p>
                                                <p class="text-sm text-gray-600">- ${result.author || 'Unknown'}</p>
                                            </div>
                                        </a>
                                    `;
                                } else if (result.type === 'author') {
                                    const sanitizedContent = result.content.replace(/<\/?mark>/g, '');
                                    const authorSlug = encodeURIComponent(result.content.replace(/  /g, '+'));
                                        resultsHTML += `
                                            <a href="${baseUrl}authors/${result.content.replace(/<\/?mark>/g, '').replace(/\s+/g, '+')}" class="block hover:bg-gray-100 p-2 rounded">
                                                <div class="search-result-item">
                                                    <p class="text-lg font-semibold text-gray-800 mb-1 capitalize">${result.content}</p>
                                                    <p class="text-sm text-gray-600 italic">Author</p>
                                                </div>
                                            </a>
                                        `;


                                } else if (result.type === 'user') {
                                    resultsHTML += `
                                        <a href="${baseUrl}${result.name}" class="block hover:bg-gray-100 p-2 rounded">
                                            <div class="search-result-item">
                                                <p class="text-lg font-semibold text-gray-800 mb-1 capitalize">${result.username}</p>
                                                <p class="text-sm text-gray-600 italic">User</p>
                                            </div>
                                        </a>
                                    `;
                                }
                            });

                            $('#search_results').html(resultsHTML).show();
                        } else {
                            $('#search_results').html(`<p class="text-red-500 p-2">${response.message}</p>`).show();
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        $('#search_results').html('<p class="text-red-500">Error processing results. Please try again later.</p>').show();
                    }
                },
                error: function () {
                    $('#search_results').html('<p class="text-red-500">Error fetching search results. Please try again later.</p>').show();
                }
            });
        } else {
            $('#search_results').empty().hide();
        }
    });
});



</script>


