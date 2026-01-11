<?php 
// Start session only if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../components/header.php'; 
include_once __DIR__ . '/../../../config/utilis.php'; 

// Ensure user is authenticated
if (!isset($_SESSION['username'])) {
    echo '<p class="text-sm md:text-lg">Please log in to access your dashboard.</p>';
    exit;
}
?>

<?php
// Fetch logged-in user data
$user = $this->userModel->getUserById($_SESSION['user_id']);

?>
<style>
  /* Gradient background */
  .gradient-bg {
    background: linear-gradient(to bottom right, #1e3a8a, #3b82f6);
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
    padding-top: 7rem;
  }
</style>

<!-- Gradient Background Section -->

<div class="gradient-bg p-8 pb-16 mb-[-4rem]"> 
<div class="bg-white mx-auto max-w-6xl border rounded-tl-lg rounded-tr-lg shadow-xl p-4 flex flex-col items-center justify-center h-26">
       <div class="flex items-center">
            <h2 class="text-xl md:text-3xl font-bold text-purple-800 mr-2">
                Welcome, <?= decodeHtmlEntities(htmlspecialchars($_SESSION['username'])) ?>! 🎉
            </h2>
        </div>
        <p class="text-md text-gray-700 mt-2">Your Inspiration Tokens:
            <span class="font-semibold text-green-600 text-sm md:text-md"><?= $user['points'] ?></span> 💰
        </p>
</div>

</div>

<div class="bg-white border rounded-lg shadow-xl p-6 mx-auto max-w-7xl relative z-1">
      <h2 class="text-lg md:text-xl font-bold mb-4 text-gray-800">Analytics</h2>
  <div class="user-stats flex flex-col space-y-2 mb-6">
    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 list-none p-0">
      <li class="border border-gray-300 p-6 rounded-lg bg-blue-100 shadow-lg text-center transition hover:bg-blue-200">
        <span class="text-3xl sm:text-2xl font-bold text-blue-800"><?= htmlspecialchars($quoteCount) ?></span>
        <span class="block text-sm sm:text-base text-gray-600">Total Quotes Created</span>
      </li>
      <li
        class="border border-gray-300 p-6 rounded-lg bg-green-100 shadow-lg text-center transition hover:bg-green-200">
        <span class="text-3xl sm:text-2xl font-bold text-green-800"><?= htmlspecialchars($totalViews) ?></span>
        <span class="block text-sm sm:text-base text-gray-600">Total Views</span>
      </li>
      <li
        class="border border-gray-300 p-6 rounded-lg bg-yellow-100 shadow-lg text-center transition hover:bg-yellow-200">
        <span class="text-3xl sm:text-2xl font-bold text-yellow-800"><?= htmlspecialchars($totalLikes) ?></span>
        <span class="block text-sm sm:text-base text-gray-600">Total Likes</span>
      </li>
      <li
        class="border border-gray-300 p-6 rounded-lg bg-purple-100 shadow-lg text-center transition hover:bg-purple-200">
        <span class="text-3xl sm:text-2xl font-bold text-purple-800"><?= htmlspecialchars($totalSaves) ?></span>
        <span class="block text-sm sm:text-base text-gray-600">Total Saves</span>
      </li>
    </ul>


  </div>
</div>

<main class="container mx-auto my-4 p-4">
    <?php if (isset($_SESSION['badgeNotification'])): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex items-center" role="alert">
        <span class="mr-2 text-2xl">🏆</span>
        <div>
            <p class="font-bold">Badge Earned! 🎉</p>
            <p><?= $_SESSION['badgeNotification'] ?></p>
        </div>
    </div>
    <?php unset($_SESSION['badgeNotification']); endif; ?>


    <div class="flex justify-between mb-4">
        <h2 class="text-lg md:text-xl font-bold mb-4 text-gray-800">Your Quotes</h2>
        <div class="relative inline-block text-left">
          <button type="button" class="inline-flex justify-between w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none active:border-blue-500 focus:outline focus:outline-blue-500" aria-haspopup="true" id="filterButton">
                Filter Quotes
                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <div class="absolute right-0 z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="filterButton" id="filterDropdown">
                <div class="py-1" role="none">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="filterQuotes('recently_created'); return false;">Recently Created</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="filterQuotes('edited'); return false;">Edited</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="filterQuotes('most_liked'); return false;">Most Liked</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="filterQuotes('most_saved'); return false;">Most Saved</a>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto w-full" id="quotesTable">
        <!-- Quotes will be populated here -->
    </div>
</main>

<script>
let currentFilter = 'recently_created';


function loadQuotes() {
    const baseUrl = '<?php echo getBaseUrl(); ?>';
    const url = `${baseUrl}app/views/quotes/fetch_quotes.php?filter=${currentFilter}`;

    fetch(url, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text(); // Get as text first
    })
    .then(text => {
        // Try to parse JSON, handle empty responses
        if (!text || text.trim() === '') {
            console.error('Empty response received');
            return [];
        }
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('JSON parse error:', e);
            console.error('Response text:', text);
            throw new Error('Invalid JSON response');
        }
    })
    .then(data => {
        // Handle error responses
        if (data.error) {
            console.error('Server error:', data.error);
            $('#quotesTable').html(`<p class="text-center text-red-500">${data.error}</p>`);
            return;
        }
        
        // console.log(data);
        if (Array.isArray(data) && data.length > 0) {
            let gridHTML = '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-w-7xl mx-auto p-2.5">';

            data.forEach(quote => {
                gridHTML += `
                    <div class="border border-gray-300 rounded-lg shadow-xl p-6 flex flex-col transition-transform transform hover:scale-105 hover:shadow-2xl ${
                        quote.is_edited ? 'bg-yellow-100' : 'bg-white'
                    }">
                        <div class="flex justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800">
                                <a href="<?php echo url('quote/${quote.id}'); ?>" class="text-gray-800 hover:text-blue-600 transition duration-200">
                                    ${quote.quote_text.length > 30 ? quote.quote_text.slice(0, 30) + '...' : quote.quote_text}
                                </a>
                            </h3>
                        </div>
                        <div class="text-sm text-gray-600 mb-4">
                            <p>Created at: <span class="font-medium">${quote.created_at}</span></p>
                            ${quote.is_edited ? `<p>Edited at: <span class="font-medium">${quote.updated_at}</span></p>` : ''}
                        </div>
                        <div class="flex justify-between text-gray-600 mb-4">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span class="font-medium">${quote.total_likes}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2c-1.716 0-3.408.106-5.07.31C3.806 2.45 3 3.414 3 4.517V17.25a.75.75 0 0 0 1.075.676L10 15.082l5.925 2.844A.75.75 0 0 0 17 17.25V4.517c0-1.103-.806-2.068-1.93-2.207A41.403 41.403 0 0 0 10 2Z"/>
                                </svg>
                                <span class="font-medium">${quote.total_saves}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-5 w-5 mr-1 text-gray-500" fill="currentColor">
                                    <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">${quote.view_count}</span>
                            </div>
                        </div>
                        <div class="flex space-x-4 w-full">
                            <a href="<?php echo url('edit-quote/${quote.id}'); ?>" title="Edit Quote" onclick="editQuote(${quote.id})" class="flex items-center justify-center bg-blue-500 text-white px-3 py-2 rounded shadow hover:bg-blue-600 transition flex-1 text-center">
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
                                  <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                </svg>

                                <span>Edit</span>
                            </a>
                            <button data-id="${quote.id}" onclick="deleteQuote(this)" class="flex items-center justify-center bg-red-500 text-white px-3 py-2 rounded shadow hover:bg-red-600 transition flex-1 text-center">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
                              <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                            </svg>

                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                `;
            });

            gridHTML += '</div>';
            $('#quotesTable').html(gridHTML);
        } else {
            $('#quotesTable').html('<p class="text-center text-gray-600">No quotes found.</p>');
        }
    })
         .catch(error => {
            console.error('Error loading quotes:', error);
            document.getElementById('quotesTable').innerHTML = `<p class="text-red-500 font-bold">Failed to load quotes. ${error.message}</p>`;
        });
}


function filterQuotes(attribute) {
    currentFilter = attribute;
    loadQuotes();
    $('#filterDropdown').addClass('hidden');
}

function deleteQuote(button) {
    const quoteId = button.getAttribute('data-id');
    const baseUrl = '<?php echo getBaseUrl(); ?>';
    
    if (confirm('Are you sure you want to delete this quote?')) {
        fetch(`${baseUrl}app/views/quotes/delete-quote.php`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?php echo generateCsrfToken(); ?>'
            },
            body: JSON.stringify({ 
                id: quoteId,
                csrf_token: '<?php echo generateCsrfToken(); ?>'
            })
        })
        .then(response => response.json())
        .then(data => data.success ? alert('Quote deleted successfully.') && loadQuotes() : alert('Failed to delete quote: ' + data.message))
        .catch(error => console.error('Error:', error));
    }
}


$(document).ready(function() {
    $('#filterButton').click(function() {
        $('#filterDropdown').toggleClass('hidden');
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('#filterDropdown, #filterButton').length) {
            $('#filterDropdown').addClass('hidden');
        }
    });

    loadQuotes();
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
