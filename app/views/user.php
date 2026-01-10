<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it hasn't been started already
}

if (isset($_SESSION['user_id'])) {
    $currentUserId = $_SESSION['user_id'];
} else {
        // Handle the case when 'user_id' is not set
        // echo "User is not logged in.";
}

?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($userData['name']))); ?> | Quoteshub - Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟 </title>
    
    <?php 
    require_once __DIR__ . '/../../config/config.php';
    $baseUrl = BASE_URL . '/'; 
    ?>
    
     <!-- Dynamic Meta Description: About the User and Profile -->
    <meta name="description" content="Discover quotes, ideas, and inspirations by <?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($userData['name']))); ?>. Join the community on Quoteshub to share wisdom and inspire others.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="Quotes, Wisdom, Inspiration, <?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($userData['name']))); ?>, Create Quotes, Share Quotes, QuotesHub">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo $baseUrl; ?>user/<?php echo $userData['id']; ?>">

    <!-- Open Graph Meta Tags (for social media) -->
    <meta property="og:title" content="<?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($userData['name']))); ?> | Quoteshub">
    <meta property="og:description" content="Check out <?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($userData['name']))); ?>'s profile on QuotesHub. Discover their inspiring quotes and ideas.">
    <meta property="og:url" content="<?php echo $baseUrl; ?>user/<?php echo $userData['id']; ?>">
    <meta property="og:type" content="profile">
    <meta property="og:image" content="<?php echo $baseUrl; ?>public/uploads/authors_images/placeholder.png'; ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($userData['name']))); ?> | Quoteshub">
    <meta name="twitter:description" content="Discover the wisdom of <?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($userData['name']))); ?> on Quoteshub!">
    <meta property="og:image" content="<?php echo $baseUrl; ?>public/uploads/authors_images/placeholder.png'; ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">

    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    <!-- Tailwind CSS CDN -->
    <!-- Tailwind removed - use base layout or local CSS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
       <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
        </script>
        
    <style>
        .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    body.modal-open {
    overflow: hidden;  /* Prevent page scrolling when modal is open */
        }
        
        .modal-content {
            max-height: 400px;  /* Adjust height as needed */
            overflow-y: auto;   /* Show vertical scrollbar if the content is long */
        }

    </style>

  </head>
  <body style="background-color: #f4f2ee;">
      
     <?php include_once  __DIR__ . '/../../config/utilis.php'; ?>
     <?php include_once  __DIR__ . '/../../config/config.php'; ?>
        <?php $baseUrl = BASE_URL . '/'; ?>

     <!-- Back to Home Link with Tailwind CSS -->
      <nav class="w-full bg-gray-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <a href="<?php echo $baseUrl; ?>" class="text-gray-700 font-bold text-sm md:text-lg hover:text-emerald-600">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="inline-block w-5 h-5 md:w-6 md:h-6 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m-6-6 6 6-6 6" />
              </svg>
              Back to Home
            </a>
          </div>
        </div>
      </nav>
    <div class="container mx-auto px-4 py-8 relative">
    <!-- Profile Button at the top right -->
<div class="absolute top-3 right-1 z-50">
    <button id="shareButton" title="Share this profile" 
       class="flex items-center justify-center  w-8 h-8 sm:text-sm lg:w-10 lg:h-10 bg-emerald-500 text-white rounded-full shadow-md hover:bg-emerald-600 transition duration-300">
        <!-- Share Icon (Font Awesome) -->
        <i class="fa fa-share text-white"></i>
    </button>
</div>

<!-- Modal Window for Sharing -->
<div id="shareModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 lg:max-w-sm lg:w-full z-60 relative">
        <!-- Close Button Aligned at Top Right -->
        <div class="absolute top-0 right-3">
            <button id="closeModal" class="text-3xl text-gray-500">
                &times;
            </button>
        </div>

        <h3 class="text-xl font-semibold text-center mb-4">Share Profile</h3>
        <div class="flex justify-center mb-4">
            <!-- Display user profile URL -->
            <input type="text" id="profileUrl" value="<?php echo getBaseUrl(); ?>/<?php echo htmlspecialchars($userData['username']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700" readonly />
        </div>
        <div class="flex justify-center">
            <!-- Copy to Clipboard Button -->
            <button id="copyButton" class="px-4 py-2 bg-emerald-500 text-white rounded-lg shadow-md hover:bg-emerald-600 transition duration-300">
                Copy to Clipboard
            </button>
        </div>
    </div>
</div>


<!-- Notification Container -->
<div id="notification" class="fixed top-0 left-0 right-0 bg-green-500 text-white py-2 px-4 text-center hidden z-60">
    Profile URL copied to clipboard!
</div>



<!-- Profile and Bio Section -->
<div class="bg-white shadow-md rounded-lg p-6 mb-8">
    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
        <?php
        $userImageFile = $userData['user_img'];
        $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
        $imageSrc = !empty($userImageFile) && file_exists($userImagePath) ? $baseUrl . 'public/uploads/users/' . $userImageFile : $baseUrl . 'public/uploads/authors_images/placeholder.png';
        ?>
        <!-- Profile Picture Section -->
        <div class="relative flex-shrink-0 flex flex-col items-center justify-center space-y-2">
            <div class="relative">
                <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture"
                     class="w-36 h-36 rounded-xl object-cover border border-gray-300 shadow-md" />
                <?php if ($userData['status'] == 1): ?>
                    <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></span>
                <?php endif; ?>
            </div>

           <button title="Follow" 
                    id="followButton" 
                    data-follower-id="<?= htmlspecialchars($currentUserId); ?>" 
                    data-followed-user-id="<?= htmlspecialchars($userData['id']); ?>" 
                    type="button"
                    title="Follow Button"
                    class="px-10 py-2 bg-emerald-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-emerald-600 transition duration-300 tracking-wide">
                    <span class="follow-text">
                       Follow
                    </span>
            </button>
        </div>
        

        


        <!-- User Details Section -->
        <div class="text-center md:text-left flex flex-col space-y-4 w-full">
            <div>
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 capitalize flex items-center justify-center md:justify-start">
                    <span><?= decodeCleanAndRemoveTags(decodeAndCleanText($userData['name'])); ?></span>
                    <?php if ($isVerified): ?>
                        <svg class="ml-2 w-5 h-5 rounded-full border-1 border-yellow-500 bg-yellow-100" 
                             xmlns="http://www.w3.org/2000/svg" 
                             viewBox="0 0 24 24" 
                             title="Verified User">
                            <circle cx="12" cy="12" r="12" fill="#00cc44"/>
                            <path fill="white" d="M9.5 16.5l-3-3 1.41-1.41L9.5 13.67l6.09-6.09L17 8.5l-7.5 7.5z"/>
                        </svg>
                    <?php endif; ?>
                </h1>
                <p class="text-gray-500 text-sm md:text-base">Joined on <?= date("M' y", strtotime($userData['created_at'])); ?></p>
            </div>
            
            <p class="text-gray-700 text-sm md:text-base leading-relaxed px-8 md:px-0">
                <?= decodeCleanAndRemoveTags(decodeAndCleanText($userData['bio'])); ?>
            </p>

        <!-- Followers and Following Section -->
        <div class="flex justify-center md:justify-start space-x-6 text-center md:text-left followers_section">
            <div id="followersSection" onclick="loadFollowersList()" class="cursor-pointer hover:text-blue-500">
                <p class="text-lg font-semibold text-gray-800" id="followerCount">
                    <?php echo htmlspecialchars($followerCount); ?>
                </p>
                <p class="text-sm text-gray-500">Followers</p>
            </div>
            <div id="followingSection" onclick="loadFollowingList()" class="cursor-pointer hover:text-blue-500">
                <p class="text-lg font-semibold text-gray-800" id="followingCount">
                    <?php echo htmlspecialchars($followingCount); ?>
                </p>
                <p class="text-sm text-gray-500">Following</p>
            </div>
        </div>

            


        </div>
    </div>
    
            <!-- Modal for displaying followers or following -->
            <div id="followersModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
                <div class="modal-content bg-white p-8 rounded-lg shadow-lg max-h-[80vh] overflow-y-auto w-80">
                    <!-- Modal Header -->
                    <div class="modal-header flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Followers</h2>
                        <span class="close text-xl font-bold cursor-pointer text-gray-600 hover:text-red-600" onclick="closeModal()">&times;</span>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body space-y-4">
                        <ul id="followersList" class="space-y-2">
                            <!-- Followers list items will be injected here -->
                        </ul>
                    </div>
            
                    <!-- Modal Footer (Optional) -->
                    <!--<div class="modal-footer text-center mt-4 text-sm text-gray-500">-->
                    <!--    <p>End of the list</p>-->
                    <!--</div>-->
                </div>
            </div>

            <div id="followingModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
                <div class="modal-content bg-white p-8 rounded-lg shadow-lg max-h-[80vh] overflow-y-auto w-80">
                    <!-- Modal Header -->
                    <div class="modal-header flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Following</h2>
                        <span class="close text-xl font-bold cursor-pointer text-gray-600 hover:text-red-600" onclick="closeModal()">&times;</span>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body space-y-4">
                        <ul id="followingList" class="space-y-2">
                            <!-- Following list items will be injected here -->
                        </ul>
                    </div>
            
                    <!-- Modal Footer (Optional) -->
                    <!--<div class="modal-footer text-center mt-4 text-sm text-gray-500">-->
                    <!--    <p>End of the list</p>-->
                    <!--</div>-->
                </div>
            </div>

        <!-- Modal -->
                <div id="loginModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white rounded-lg p-6 shadow-lg max-w-sm text-center relative">
                        <div class="absolute top-0  right-3">
                            <button id="closeLoginModal" class="text-3xl text-gray-500">&times;</button>
                        </div>
                
                        <h2 class="text-lg font-bold mb-4">Login Required</h2>
                        <p class="text-gray-700 mb-6">You need to log in to follow users.</p>
                        <a href="<?php echo url('login'); ?>" 
                            class="px-6 py-2 bg-emerald-500 text-white rounded-lg shadow-md hover:bg-emerald-600 transition duration-300">
                            Login
                        </a>
                
                    </div>
                </div>


    <hr class="my-6">
    <?php if (!empty($badges)): ?>
    <div class="mt-6">
        <ul class="list-none flex items-center flex-wrap bg-gray-100 border border-gray-200 rounded-lg py-4 px-6 shadow-sm space-x-4  md:space-y-0">
            <?php foreach ($badges as $badge): ?>
            <li class="flex flex-col items-center text-gray-700">
                <?php
                $badgeImages = [
                    'Debut Writer' => 'public/uploads/badges/debut_writer.png',
                    '10 Quotes - 10 quotes published' => 'public/uploads/badges/10_quotes.png',
                    '50 Quotes - 50 quotes published' => 'public/uploads/badges/50_quotes.png',
                ];

                $badgeImagePath = isset($badgeImages[$badge['badge_name']]) ? $badgeImages[$badge['badge_name']] : 'public/uploads/badges/default.png';
                $isDefaultBadge = ($badgeImagePath === 'public/uploads/badges/default.png');
                $badgeTitleAlt = $isDefaultBadge ? 'Default Badge' : htmlspecialchars($badge['badge_name'], ENT_QUOTES, 'UTF-8');
                ?>
                <a href="<?php echo $baseUrl; ?>badges" class="block w-16 h-16 md:w-18 md:h-18 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 p-0.5 shadow-md transform transition duration-300 hover:scale-105">
                    <img src="<?php echo $baseUrl; ?><?php echo htmlspecialchars($badgeImagePath, ENT_QUOTES, 'UTF-8'); ?>"
                         alt="<?= $badgeTitleAlt; ?> Image"
                         class="rounded-xl object-cover w-full h-full"
                         title="<?= $badgeTitleAlt; ?>" />
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php else: ?>
    <div class="mt-6 text-center text-gray-500">
        No badges earned yet. 🎉
    </div>
    <?php endif; ?>
</div>

        
        
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg lg:text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <span class="mr-2"><?= count($quotes); ?> 📝</span> <?= count($quotes) === 1 ? 'Quote' : 'Quotes'; ?> Created
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if (!empty($quotes)): ?>
                    <?php foreach ($quotes as $quote): ?>
                        <a href="<?php echo $baseUrl; ?>quote/<?php echo $quote['quote_id']; ?>">
                            <div class="bg-gray-50 border border-2 border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition duration-300 ease-in-out">
                                <blockquote class="text-gray-700 text-lg mb-2 break-words">
                                    "<?= decodeHtmlEntities(htmlspecialchars($quote['quote_text'])); ?>"
                                </blockquote>
                                <p class="text-gray-500 text-sm break-words">— <?= decodeHtmlEntities(htmlspecialchars($quote['author_name'])); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 col-span-full text-center">No quotes found for this user. 😔</p>
                <?php endif; ?>
            </div>
        </div>
        
        
        
        </div>
        <br /><br /><br /><br />
    <!-- Footer -->
    <footer class="bg-white text-gray-700 py-6 border-t border-gray-200">
      <div class="container mx-auto px-4">
        <!-- Footer Links -->
        <div class="flex flex-wrap justify-center mb-6">
          <a href="<?php echo $baseUrl; ?>about" class="mx-4 text-xs md:text-sm hover:text-emerald-600">About Us</a>
                  <a href="<?php echo $baseUrl; ?>guidelines" class="mx-4 text-sm hover:text-emerald-600">Guidelines</a>
          <a href="<?php echo $baseUrl; ?>terms" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Terms of Service</a>
          <a href="<?php echo $baseUrl; ?>contact" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Contact Us</a>
          <a href="<?php echo $baseUrl; ?>disclaimer" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Disclaimer</a>
          <a href="<?php echo $baseUrl; ?>privacy" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Privacy Policy</a>
          <a href="<?php echo $baseUrl; ?>blogs" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Blog</a>
          <a href="<?php echo $baseUrl; ?>feedback" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Feedback</a>
        </div>
    
        <!-- Logo and Copyright -->
        <div class="flex md:flex-row flex-col items-center justify-center space-x-4">
          <a class="flex items-center justify-center space-x-4" href="<?php echo getBaseUrl(); ?>"><img
              src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="Website Logo" class="mr-4" />
            <p class="text-xs text-gray-500">&copy; <span><?php echo date("Y"); ?></span>  Quoteshub</p>
          </a>
          <p class="text-xs text-gray-500 ml-4">
            Crafted with
            <span class="hover:text-emerald-600">&hearts;</span> for the people
            on the internet
          </p>
        </div>
      </div>
    </footer>
    <script>


$(document).ready(function () {
    // Open the modal when the share button is clicked
    $('#shareButton').on('click', function () {
        $('#shareModal').removeClass('hidden');  // Show the modal
    });

    // Close the modal when the close button is clicked
    $('#closeModal').on('click', function () {
        $('#shareModal').addClass('hidden');  // Hide the modal
    });

    // Close the modal if clicking outside the modal content
    $('#shareModal').on('click', function (e) {
        if ($(e.target).is('#shareModal')) {
            $('#shareModal').addClass('hidden');  // Hide the modal
        }
    });

    // Close the modal if the Escape key is pressed
    $(document).on('keydown', function (e) {
        if (e.key === "Escape") {
            $('#shareModal').addClass('hidden');  // Hide the modal
        }
    });

    // Copy the profile URL to clipboard when the copy button is clicked
    $('#copyButton').on('click', function () {
        var copyText = $('#profileUrl')[0];
        copyText.select();  // Select the text field
        document.execCommand('copy');  // Execute the copy command
        
        // Show the notification
        $('#notification').removeClass('hidden');
        
        // Hide the notification after 3 seconds
        setTimeout(function () {
            $('#notification').addClass('hidden');
        }, 3000);
    });
});


$(document).ready(function() {
    var followerId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
    var followedUserId = $("#followButton").data("followed-user-id");


    // Function to update follower and following counts
    function updateFollowCounts(userId) {
        $.ajax({
            url: "<?php echo $baseUrl; ?>api/follow-counts",
            method: "GET",
            data: { user_id: userId },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#followerCount").text(response.followers_count);
                    $("#followingCount").text(response.following_count);
                } else {
                    console.log("Failed to fetch follow counts:", response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("AJAX Error:", textStatus, errorThrown);
            }
        });
    }

    // Fetch the follow status from the server when the page loads
    if (followerId > 0) {
         $.ajax({
              url: "<?php echo $baseUrl; ?>api/follow-status",
            method: "GET",
            data: {
                follower_id: followerId,
                followed_user_id: followedUserId
            },
            dataType: "json",
            success: function(response) {
                
            
                if (response.success) {
                    var isFollowing = response.is_following;
                    var followText = isFollowing ? "Unfollow" : "Follow";
                    var followButtonClass = isFollowing ? "bg-red-500 hover:bg-red-600" : "bg-emerald-500 hover:bg-emerald-600";
            
                    // Update the follow button text and classes based on the follow status
                    $("#followButton").find(".follow-text").text(followText);
                    $("#followButton").removeClass("bg-emerald-500 hover:bg-emerald-600 bg-red-500 hover:bg-red-600")
                                       .addClass(followButtonClass);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("AJAX Error:", textStatus, errorThrown);
            }
        });


    }

    // Follow/unfollow button click handler
    $("#followButton").on("click", function(event) {
        var $button = $(this);

        // Check if the user is logged in
        var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

        if (!isLoggedIn) {
            // Show the login modal if the user is not logged in
            $("#loginModal").removeClass("hidden");
        } else if (followerId === followedUserId) {
            // Disable the button if the user is trying to follow themselves
            $button.prop("disabled", true).addClass("bg-gray-400 cursor-not-allowed");
        } else {
            // Proceed with the follow/unfollow action
            $.ajax({
                url: "<?php echo $baseUrl; ?>api/follow",
                method: "POST",
                data: {
                    follower_id: followerId,
                    followed_user_id: followedUserId
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        var isFollowing = response.is_following;
                        var followText = isFollowing ? "Unfollow" : "Follow";
                        var followButtonClass = isFollowing ? "bg-red-500 hover:bg-red-600" : "bg-emerald-500 hover:bg-emerald-600";

                        // Update the follow button text and classes
                        $button.find(".follow-text").text(followText);
                        $button.removeClass("bg-emerald-500 hover:bg-emerald-600 bg-red-500 hover:bg-red-600")
                               .addClass(followButtonClass);

                        // Update follow counts in real-time
                        updateFollowCounts(followedUserId);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("AJAX Error:", textStatus, errorThrown);
                }
            });
        }
    });

    // Close the login modal when the close button is clicked
    $("#closeLoginModal").on("click", function() {
        $("#loginModal").addClass("hidden");
    });
});


// Open modal and lock body scroll
function openModal(modalId) {
    // Lock body scroll
    $('body').css('overflow', 'hidden');

    // Show modal
    $('#' + modalId).removeClass('hidden');

    // Click outside the modal to close
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.modal-content').length) {
            closeModal();
        }
    });

    // Close modal on Escape key press
    $(document).on('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
}

// Close modal and unlock body scroll
function closeModal() {
    // Unlock body scroll
    $('body').css('overflow', 'auto');

    // Hide modals
    $('.modal').addClass('hidden');

    // Remove the click and keydown event listeners to prevent multiple bindings
    $(document).off('click');
    $(document).off('keydown');
}

function loadFollowersList() {
    $.ajax({
        url: "<?php echo $baseUrl; ?>get_followers_list",
        method: "GET",
        data: { user_id: <?php echo $userData['id']; ?> },
        dataType: "json",
        success: function(response) {
            // console.log(response);
            if (response.success) {
                const followers = response.followers;
                let followersHtml = '';
                followers.forEach(function(follower) {
                    followersHtml += `<a href="<?php echo $baseUrl; ?>${follower.username}" class="text-sm text-gray-700 font-medium hover:bg-gray-100 p-2 rounded block capitalize">${follower.user_name}</a>`;
                });
                $('#followersList').html(followersHtml);
                openModal('followersModal');
            } else {
                console.log("Failed to fetch followers:", response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error:", textStatus, errorThrown);
        }
    });
}


// Function to load following list and open the modal
function loadFollowingList() {
    $.ajax({
        url: "<?php echo $baseUrl; ?>get_following_list",
        method: "GET",
        data: { user_id: <?php echo $userData['id']; ?> },
        dataType: "json",
        success: function(response) {
                //  console.log(response);
            if (response.success) {
                const following = response.following;
                let followingHtml = '';
                following.forEach(function(followedUser) {
                    followingHtml += `<a href="<?php echo $baseUrl; ?>${followedUser.username}" class="text-sm text-gray-700 font-medium hover:bg-gray-100 p-2 rounded block capitalize">${followedUser.user_name}</a>`; 
                });
                $('#followingList').html(followingHtml);
                openModal('followingModal');
            } else {
                console.log("Failed to fetch following:", response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error:", textStatus, errorThrown);
        }
    });
}






    </script>
    <?php include __DIR__ . '/../views/components/footer.php'; ?>