<?php
/**
 * Base Layout Template
 * This template provides the common structure for all pages
 * 
 * Required variables:
 * - $pageTitle: Title of the page
 * - $contentView: Path to the content view file
 * 
 * Optional variables:
 * - $additionalCSS: Array of additional CSS files
 * - $additionalJS: Array of additional JS files
 * - $pageDescription: Meta description for SEO
 * - $pageKeywords: Meta keywords for SEO
 * - $bodyClass: Additional CSS classes for body tag
 */

// Include utilities and helpers
require_once __DIR__ . '/../../helpers/ViewHelpers.php';
require_once __DIR__ . '/../../../config/utilis.php';

// Logic extracted from header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/env.php';
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../controllers/NotificationController.php';
require_once __DIR__ . '/../../models/NotificationModel.php';

global $conn;
$notificationModel = new NotificationModel($conn);
$unreadCount = 0;
if (isset($_SESSION['user_id'])) {
    $unreadCount = $notificationModel->getUnreadNotificationCount($_SESSION['user_id']);
}

// Set defaults
$baseUrl = getBaseUrl();
$isLoggedIn = isUserLoggedIn();
$pageTitle = $pageTitle ?? 'QuotesHub - Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟';
$pageDescription = $pageDescription ?? 'Share, discover, and connect through inspiring quotes';
$pageKeywords = $pageKeywords ?? 'quotes, inspiration, wisdom, share';
$bodyClass = $bodyClass ?? '';
$additionalCSS = $additionalCSS ?? [];
$additionalJS = $additionalJS ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    <meta name="author" content="QuotesHub">
    
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Favicon (Multiple formats for browser compatibility) -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    
    <!-- jQuery (Load First - Required by other scripts) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <!-- Tailwind CSS (Local Build - Production Optimized) -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/tailwind.min.css">
    
    <!-- Base Styles -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    
    <!-- Additional CSS -->
    <?php foreach ($additionalCSS as $cssFile): ?>
        <link rel="stylesheet" href="<?php echo $baseUrl . $cssFile; ?>">
    <?php endforeach; ?>
    
    <!-- Inline Styles Slot -->
    <?php if (isset($inlineStyles)): ?>
        <style><?php echo $inlineStyles; ?></style>
    <?php endif; ?>
</head>
<body class="<?php echo htmlspecialchars($bodyClass); ?>" style="background-color: #F4F2EE;">
    
    <!-- Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>
    
    <!-- Main Content -->
    <main id="main-content" role="main" style="margin-top: 0; padding-top: 0;">
        <?php 
        // Render content from View engine
        if (isset($content)) {
            echo $content;
        } elseif (isset($view) && method_exists($view, 'section')) {
            echo $view->section('content');
        } else {
            // Fallback for legacy includes
            if (isset($contentView) && file_exists($contentView)) {
                include $contentView;
            }
        }
        ?>
    </main>
    
    <!-- Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <!-- Global Configuration -->
    <script>
        // Global configuration object
        window.APP_CONFIG = {
            baseUrl: "<?php echo $baseUrl; ?>",
            isLoggedIn: <?php echo $isLoggedIn ? 'true' : 'false'; ?>,
            userId: <?php echo getCurrentUserId() ?? 'null'; ?>,
            csrfToken: "<?php echo $_SESSION['csrf_token'] ?? ''; ?>"
        };
    </script>
    
    <!-- JavaScript Modules (if needed for this page) -->
    <?php if (isset($useModules) && $useModules): ?>
        <script src="<?php echo $baseUrl; ?>public/assets/js/modules/scroll.module.js"></script>
        <script src="<?php echo $baseUrl; ?>public/assets/js/modules/modal.module.js"></script>
        <script src="<?php echo $baseUrl; ?>public/assets/js/modules/like.module.js"></script>
        <script src="<?php echo $baseUrl; ?>public/assets/js/modules/save.module.js"></script>
        <script src="<?php echo $baseUrl; ?>public/assets/js/modules/notification.module.js"></script>
    <?php endif; ?>
    
    <!-- Additional JavaScript -->
    <?php foreach ($additionalJS as $jsFile): ?>
        <script src="<?php echo $baseUrl . $jsFile; ?>"></script>
    <?php endforeach; ?>
    
    <!-- Inline Scripts Slot -->
    <?php if (isset($inlineScripts)): ?>
        <script><?php echo $inlineScripts; ?></script>
    <?php endif; ?>
    
</body>
</html>
