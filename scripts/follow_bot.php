<?php

require __DIR__ . '/../vendor/autoload.php'; // Load PHPSpreadsheet library
require __DIR__ . '/../config/env.php'; // Load environment configuration
use PhpOffice\PhpSpreadsheet\IOFactory;

$excelFilePath = __DIR__ . "/../storage/data/quotes_like_save.xlsx";

// Load users from the Excel file
try {
    $spreadsheet = IOFactory::load($excelFilePath);

    if ($spreadsheet->getSheetCount() < 1) {
        die("[ERROR] The Excel file does not contain any sheets.\n");
    }

    $userSheet = $spreadsheet->getSheet(0);
    $userData = $userSheet->toArray();

    $userHeader = array_map('strtolower', $userData[0]);
    $usernameIndex = array_search('username', $userHeader);
    $passwordIndex = array_search('password', $userHeader);

    if ($usernameIndex === false || $passwordIndex === false) {
        die("[ERROR] Username or password column not found in the Excel file.\n");
    }

    array_shift($userData);
    $users = array_values($userData);

} catch (Exception $e) {
    die("[ERROR] " . $e->getMessage() . "\n");
}

$loginUrl = APP_URL . "/login";
$followApiUrl = APP_URL . "/api/follow";
$logoutUrl = APP_URL . "/logout";
$cookieFile = __DIR__ . "/../storage/temp/cookies_follow.txt";

// Log execution start with timestamp
echo "\n" . str_repeat("=", 60) . "\n";
echo "[" . date('Y-m-d H:i:s') . "] Follow Bot Started\n";
echo str_repeat("=", 60) . "\n";

file_put_contents($cookieFile, ""); // Clear cookies

// Pick a random user to be the follower
$randomFollower = $users[array_rand($users)];
$followerUsername = $randomFollower[$usernameIndex];
$followerPassword = $randomFollower[$passwordIndex];

echo "[INFO] Trying to log in as: $followerUsername\n";

$ch = curl_init();

// Get login page (to maintain session and extract CSRF token)
curl_setopt_array($ch, [
    CURLOPT_URL => $loginUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
]);

$loginPage = curl_exec($ch);

if (curl_errno($ch)) {
    echo "[ERROR] cURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

// Extract CSRF token from login page
$csrfToken = '';
if (preg_match('/name="csrf_token"\s+value="([^"]+)"/', $loginPage, $matches)) {
    $csrfToken = $matches[1];
    echo "[INFO] CSRF token extracted successfully\n";
} else {
    echo "[WARNING] CSRF token not found in login page\n";
}

// Login request with CSRF token
curl_setopt_array($ch, [
    CURLOPT_URL => $loginUrl,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'username' => $followerUsername,
        'password' => $followerPassword,
        'csrf_token' => $csrfToken,
    ]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
]);

$loginResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (!$loginResponse || curl_errno($ch)) {
    echo "[ERROR] cURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

if ($httpCode == 200 && strpos($loginResponse, "dashboard") !== false) {
    echo "[SUCCESS] Logged in as: $followerUsername\n";
} else {
    echo "[ERROR] Login failed for: $followerUsername (HTTP $httpCode)\n";
    curl_close($ch);
    exit(1);
}

// Now we need to get the user IDs from the database
// We'll need to fetch user profiles to get their IDs
// Let's get a list of users to follow by visiting the main page

$indexUrl = APP_URL . "/";
curl_setopt($ch, CURLOPT_URL, $indexUrl);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$pageHtml = curl_exec($ch);

if (!$pageHtml || curl_errno($ch)) {
    echo "[ERROR] Failed to fetch main page: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

// Extract CSRF token from the page for follow requests
$pageCsrfToken = '';
if (preg_match('/name="csrf_token"\s+value="([^"]+)"/', $pageHtml, $csrfMatches)) {
    $pageCsrfToken = $csrfMatches[1];
    echo "[INFO] CSRF token extracted from main page\n";
} else {
    echo "[WARNING] CSRF token not found in main page\n";
}

// Extract user profile links to get usernames
preg_match_all('/\/user\/([a-zA-Z0-9_]+)/', $pageHtml, $userMatches);
$usernames = array_unique($userMatches[1]);

if (empty($usernames)) {
    echo "[ERROR] No user profiles found on the page.\n";
    curl_close($ch);
    exit(1);
}

echo "[INFO] Found " . count($usernames) . " user profiles.\n";

// Randomize the number of users to follow (2-4)
$numUsersToFollow = rand(2, min(4, count($usernames)));
$usersToFollow = array_rand(array_flip($usernames), $numUsersToFollow);

// Ensure it's an array even if only one user
if (!is_array($usersToFollow)) {
    $usersToFollow = [$usersToFollow];
}

echo "[INFO] Will attempt to follow $numUsersToFollow users.\n";

$followedCount = 0;

foreach ($usersToFollow as $targetUsername) {
    // Skip if trying to follow self
    if ($targetUsername === $followerUsername) {
        echo "[SKIP] Cannot follow self: $targetUsername\n";
        continue;
    }
    
    // Visit the user's profile page to get their user ID
    $userProfileUrl = APP_URL . "/user/$targetUsername";
    curl_setopt($ch, CURLOPT_URL, $userProfileUrl);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    $userProfileHtml = curl_exec($ch);
    
    if (!$userProfileHtml || curl_errno($ch)) {
        echo "[ERROR] Failed to fetch profile for: $targetUsername\n";
        continue;
    }
    
    // Extract user ID from the profile page
    // Look for data-user-id or similar attribute
    $userId = null;
    if (preg_match('/data-user-id=["\'](\d+)["\']/', $userProfileHtml, $idMatches)) {
        $userId = $idMatches[1];
    } elseif (preg_match('/followed_user_id["\']?\s*[:=]\s*["\']?(\d+)/', $userProfileHtml, $idMatches)) {
        $userId = $idMatches[1];
    }
    
    if (!$userId) {
        echo "[ERROR] Could not extract user ID for: $targetUsername\n";
        continue;
    }
    
    echo "[INFO] Processing user: $targetUsername (ID: $userId)\n";
    
    // Send follow request
    curl_setopt_array($ch, [
        CURLOPT_URL => $followApiUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'followed_user_id' => $userId,
            'csrf_token' => $pageCsrfToken,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-CSRF-TOKEN: $pageCsrfToken",
            "Content-Type: application/x-www-form-urlencoded",
        ],
    ]);
    
    $followResult = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (!curl_errno($ch)) {
        $response = json_decode($followResult, true);
        
        if (isset($response['success']) && $response['success']) {
            $action = isset($response['action']) ? $response['action'] : 'toggled';
            echo "[SUCCESS] Follow action for $targetUsername: $action\n";
            $followedCount++;
        } else {
            $message = isset($response['message']) ? $response['message'] : 'Unknown error';
            echo "[WARNING] Follow action for $targetUsername: $message\n";
        }
    } else {
        echo "[ERROR] Failed to follow $targetUsername - " . curl_error($ch) . "\n";
    }
    
    // Random delay between follow actions (2-5 seconds)
    $delay = rand(2000000, 5000000); // microseconds
    usleep($delay);
}

// Logout
curl_setopt($ch, CURLOPT_URL, $logoutUrl);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_exec($ch);
curl_close($ch);

echo "[INFO] Logged out: $followerUsername\n";
echo "[INFO] Successfully performed follow actions on $followedCount users.\n";
echo "[" . date('Y-m-d H:i:s') . "] Follow Bot Finished\n";
echo str_repeat("=", 60) . "\n";

?>
