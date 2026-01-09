<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controllers/NotificationController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['markAllAsRead'])) {
    $userId = intval($_POST['user_id']);
    $notificationType = $_POST['notification_type'] ?? 'all'; // 'likes', 'saves', 'follows', or 'all'

    $notificationController = new NotificationController($conn);

    $result = false;
    if ($notificationType === 'follows') {
        $result = $notificationController->markAllFollowNotificationsAsRead($userId);
    } elseif ($notificationType === 'all') {
        $result = $notificationController->markAllAsRead($userId) &&
                  $notificationController->markAllFollowNotificationsAsRead($userId);
    }

    $_SESSION[$result ? 'success_message' : 'error_message'] = $result
        ? 'All notifications marked as read.'
        : 'Could not mark notifications as read.';

    header('Location: /notifications');
    exit;
}
?>
