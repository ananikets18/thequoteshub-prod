<?php include __DIR__ . '/../components/header.php';
include_once  __DIR__ . '/../../../config/utilis.php';
?>

<?php
$baseUrl = getBaseUrl();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<?php 
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}
?>

<?php 
// Load initial batch of notifications with pagination
$result = $this->notificationModel->getAllNotifications($_SESSION['user_id'], 20, 0);
$notifications = $result['notifications'];
$hasMore = $result['hasMore'];
$total = $result['total'];

$allRead = true; // Assume all notifications are read initially
foreach ($notifications as $notification) {
    if ($notification['is_read'] == 0) {
        $allRead = false; // Found an unread notification
        break;
    }
}

// Separate the follow notifications from the likes and saves
$likeSaveNotifications = array_filter($notifications, fn($n) => in_array($n['type'], ['like', 'save']));
$followNotifications = array_filter($notifications, fn($n) => $n['type'] === 'follow');

?>

<section class="h-min">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <div class="flex justify-between items-center mb-4">
            <h1 class="text-lg md:text-xl lg:text-2xl font-semibold">Your Notifications</h1>
            <?php if (!empty($notifications) || !empty($followNotifications)): ?>
                <form action="<?php echo url('mark-all-as-read'); ?>" method="post">
                    <input type="hidden" id="user-id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="notification_type" value="all"> <!-- 'likes', 'saves', 'follows' -->
                    <button type="submit" name="markAllAsRead" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-xs md:text-sm" <?php echo $allRead ? 'disabled' : ''; ?>>
                        Mark All as Read
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <hr class="mb-4">
        
        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-200 mb-6">
            <button id="likesTab" class="px-4 py-2 text-sm font-medium text-gray-600 border-b-2 focus:outline-none">
                Likes & Saves
            </button>
            <button id="followsTab" class="px-4 py-2 text-sm font-medium text-gray-600 border-b-2 focus:outline-none">
                Follows
            </button>
        </div>

        <!-- Likes & Saves Notifications -->
        <div id="likesAndSavesContainer" class="tab-content">
            <?php if (empty($likeSaveNotifications)): ?>
                <div class="text-gray-600 text-center py-8">
                    <p>You have no Likes or Saves notifications at this moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($likeSaveNotifications as $notification): ?>
                    <div class="p-2.5 rounded-lg shadow mb-4 transition <?= $notification['is_read'] ? 'bg-white' : ($notification['type'] === 'like' ? 'bg-green-100 outline outline-1 outline-green-400' : 'bg-blue-100 outline outline-2 outline-blue-500') ?>">
                        <div class="flex items-center">
                            <?php
                            // Fetch user image for the sender
                            $userImageFile = $notification['sender_img'];
                            $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
                            $imageSrc = !empty($userImageFile) && file_exists($userImagePath) 
                                ? $baseUrl . 'public/uploads/users/' . $userImageFile 
                                : $baseUrl . 'public/uploads/authors_images/placeholder.png';
                            ?>
                            <img src="<?= htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8') ?>" 
                                 alt="<?= htmlspecialchars($notification['quote_text'], ENT_QUOTES, 'UTF-8') ?>" 
                                 class="w-12 h-12 lg:w-18 lg:h-18 rounded-full object-cover border mr-4" />
                            <div class="flex-1">
                                <a href="<?php echo $baseUrl; ?><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($notification['user_name'])); ?>" class="font-bold text-gray-800 hover:text-green-600 capitalize text-sm md:text-md">
                                    <?= htmlspecialchars($notification['sender_name']) ?>
                                    <span class="ml-2 text-sm <?= $notification['type'] === 'like' ? 'text-blue-600' : 'text-green-600' ?>">
                                        <i class="fas <?= $notification['type'] === 'like' ? 'fa-thumbs-up' : 'fa-bookmark' ?>"></i>
                                    </span>
                                </a>
                                <p class="text-gray-700">
                                    <a href="<?= $baseUrl . 'quote/' . urlencode($notification['quote_id']) ?>" 
                                       class="italic text-blue-500 hover:underline text-xs md:text-sm lg:text-md">
                                        <?= htmlspecialchars(decodeCleanAndRemoveTags(decodeAndCleanText($notification['quote_text']))) ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <!-- Load More Button for Likes & Saves -->
            <div id="loadMoreLikes" class="text-center py-4" style="display: <?php echo $hasMore ? 'block' : 'none'; ?>;">
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Load More
                </button>
            </div>
            
            <div id="loadingLikes" class="text-center py-4" style="display: none;">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <p class="text-gray-600 mt-2">Loading...</p>
            </div>
        </div>

        <!-- Follows Notifications -->
        <div id="followsContainer" class="tab-content hidden">
            <?php if (empty($followNotifications)): ?>
                <div class="text-gray-600 text-center py-8">
                    <p>You have no follow notifications at this moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($followNotifications as $follow): ?>
                    <div class="p-2 rounded-lg shadow mb-4 transition <?= $follow['is_read'] ? 'bg-white' : 'bg-green-50 outline outline-1 outline-green-300' ?>">
                        <div class="flex  items-center">
                            <?php
                            $userImageFile = $follow['sender_img'];
                            $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
                            $imageSrc = !empty($userImageFile) && file_exists($userImagePath) 
                                ? $baseUrl . 'public/uploads/users/' . $userImageFile 
                                : $baseUrl . 'public/uploads/authors_images/placeholder.png';
                            ?>
                            <img src="<?= htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8') ?>" 
                                 alt="Follow Notification" 
                                 class="w-10 h-10 rounded-full object-cover border mr-4" />
                            <div class="flex-1">
                                <a href="<?php echo $baseUrl; ?><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($follow['user_name'])); ?>" class="text-sm font-medium text-gray-800">
                                    <?= htmlspecialchars($follow['sender_name']) ?> is started to following you
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <!-- Load More Button for Follows -->
            <div id="loadMoreFollows" class="text-center py-4" style="display: <?php echo $hasMore ? 'block' : 'none'; ?>;">
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Load More
                </button>
            </div>
            
            <div id="loadingFollows" class="text-center py-4" style="display: none;">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <p class="text-gray-600 mt-2">Loading...</p>
            </div>
        </div>

    </div>
</section>

<br><hr><br>

<footer class="bg-white text-gray-700 py-6 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center mb-6">
            <?php foreach (['about', 'terms', 'contact', 'disclaimer', 'privacy', 'blogs', 'feedback'] as $page): ?>
                <a href="<?php echo $baseUrl . $page; ?>" class="mx-4 text-xs md:text-sm hover:text-emerald-600">
                    <?php echo ucfirst($page); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="flex items-center md:flex-row flex-col justify-center space-x-4">
            <a class="flex items-center justify-center space-x-4" href="<?php echo $baseUrl; ?>">
                <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="Website Logo" class="mr-4" />
                <p class="text-xs text-gray-500">&copy; <span><?php echo date("Y"); ?></span> Quoteshub</p>
            </a>
            <p class="text-xs text-gray-500 ml-4">
                Crafted with <span class="hover:text-emerald-600">&hearts;</span> for the people on the internet
            </p>
        </div>
    </div>
</footer>

<script>

$(document).ready(function () {
    let currentOffset = 20; // Start after the initial 20 loaded
    const limit = 20;
    let isLoading = false;
    let currentTab = 'likes';
    
    // Tab switching logic
    function switchTab(tab) {
        const $likesTab = $('#likesTab');
        const $followsTab = $('#followsTab');
        const $likesContainer = $('#likesAndSavesContainer');
        const $followsContainer = $('#followsContainer');

        currentTab = tab;
        
        if (tab === 'likes') {
            $likesTab.addClass('border-blue-500 text-blue-500').removeClass('text-gray-600');
            $followsTab.removeClass('border-blue-500 text-blue-500').addClass('text-gray-600');
            $followsContainer.fadeOut(200, function () {
                $likesContainer.fadeIn(200);
            });
        } else {
            $followsTab.addClass('border-blue-500 text-blue-500').removeClass('text-gray-600');
            $likesTab.removeClass('border-blue-500 text-blue-500').addClass('text-gray-600');
            $likesContainer.fadeOut(200, function () {
                $followsContainer.fadeIn(200);
            });
        }
    }

    // Function to load more notifications
    function loadMoreNotifications(type) {
        if (isLoading) return;
        
        isLoading = true;
        const $loadMoreBtn = type === 'likes' ? $('#loadMoreLikes') : $('#loadMoreFollows');
        const $loading = type === 'likes' ? $('#loadingLikes') : $('#loadingFollows');
        const $container = type === 'likes' ? $('#likesAndSavesContainer') : $('#followsContainer');
        
        $loadMoreBtn.hide();
        $loading.show();
        
        $.ajax({
            url: '<?php echo $baseUrl; ?>app/api/load-notifications.php',
            type: 'GET',
            data: {
                offset: currentOffset,
                limit: limit,
                type: type === 'likes' ? 'likes_saves' : 'follows'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Remove loading indicators
                    $loading.before(response.html);
                    
                    currentOffset += limit;
                    
                    // Show or hide load more button based on hasMore
                    if (response.hasMore) {
                        $loadMoreBtn.show();
                    } else {
                        $loadMoreBtn.hide();
                    }
                } else {
                    console.error('Error loading notifications:', response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                alert('Failed to load more notifications. Please try again.');
                $loadMoreBtn.show();
            },
            complete: function() {
                $loading.hide();
                isLoading = false;
            }
        });
    }

    // Default tab activation
    switchTab('likes');

    // Event handlers for tabs
    $('#likesTab').on('click', function () {
        switchTab('likes');
    });

    $('#followsTab').on('click', function () {
        switchTab('follows');
    });
    
    // Load more button handlers
    $('#loadMoreLikes button').on('click', function() {
        loadMoreNotifications('likes');
    });
    
    $('#loadMoreFollows button').on('click', function() {
        loadMoreNotifications('follows');
    });
    
    // Optional: Infinite scroll (uncomment to enable)
    /*
    $(window).on('scroll', function() {
        if (currentTab === 'likes') {
            const $container = $('#likesAndSavesContainer');
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const containerHeight = $container.offset().top + $container.outerHeight();
            
            if (scrollTop + windowHeight >= containerHeight - 200 && !isLoading && $('#loadMoreLikes').is(':visible')) {
                loadMoreNotifications('likes');
            }
        } else {
            const $container = $('#followsContainer');
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const containerHeight = $container.offset().top + $container.outerHeight();
            
            if (scrollTop + windowHeight >= containerHeight - 200 && !isLoading && $('#loadMoreFollows').is(':visible')) {
                loadMoreNotifications('follows');
            }
        }
    });
    */
});

</script>
<?php include __DIR__ . '/../components/footer.php'; ?>
