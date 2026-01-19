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
$indexUrl = APP_URL . "/"; // Main page where quotes are listed
$logoutUrl = APP_URL . "/logout";
$cookieFile = __DIR__ . "/../storage/temp/cookies.txt";

// Log execution start with timestamp
echo "\n" . str_repeat("=", 60) . "\n";
echo "[" . date('Y-m-d H:i:s') . "] Like & Save Bot Started\n";
echo str_repeat("=", 60) . "\n";

file_put_contents($cookieFile, ""); // Clear cookies

$randomUser = $users[array_rand($users)];
$username = $randomUser[$usernameIndex];
$password = $randomUser[$passwordIndex];

echo "[INFO] Trying to log in as: $username\n";

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
        'username' => $username,
        'password' => $password,
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
if (!$loginResponse || curl_errno($ch)) {
    echo "[ERROR] cURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}
echo "[SUCCESS] Login response received.\n";

// Visit dashboard after login to get the session's CSRF token
echo "[INFO] Fetching session CSRF token from dashboard...\n";
$dashboardUrl = APP_URL . "/dashboard";
curl_setopt($ch, CURLOPT_URL, $dashboardUrl);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$dashboardPage = curl_exec($ch);

// Extract the session's CSRF token
$sessionCsrfToken = $csrfToken; // Default to login token
if (preg_match('/name="csrf_token"\s+value="([^"]+)"/', $dashboardPage, $dashMatches)) {
    $sessionCsrfToken = $dashMatches[1];
    echo "[INFO] Session CSRF token extracted from dashboard\n";
} elseif (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $dashboardPage, $metaMatches)) {
    $sessionCsrfToken = $metaMatches[1];
    echo "[INFO] Session CSRF token extracted from meta tag\n";
} elseif (preg_match('/var csrfToken = ["\']([^"\']+)["\']/', $dashboardPage, $jsMatches)) {
    $sessionCsrfToken = $jsMatches[1];
    echo "[INFO] Session CSRF token extracted from JavaScript\n";
} else {
    echo "[WARNING] Could not extract session CSRF token, using login token\n";
}

// Use the session CSRF token for all requests
$pageCsrfToken = $sessionCsrfToken;
echo "[INFO] Using session CSRF token for all requests\n";

// Connect to database to get latest quotes
try {
    require_once __DIR__ . '/../config/database.php';
    
    if (!isset($conn) || !$conn) {
        throw new Exception("Database connection not available");
    }
    
    echo "[INFO] Database connection established\n";
    
    // Get the 10 most recently created quotes
    $stmt = $conn->prepare("SELECT id FROM quotes ORDER BY created_at DESC LIMIT 10");
    
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $quoteIds = [];
    while ($row = $result->fetch_assoc()) {
        $quoteIds[] = $row['id'];
    }
    $stmt->close();
    
    if (empty($quoteIds)) {
        echo "[ERROR] No quotes found in database.\n";
        curl_close($ch);
        exit(1);
    }
    
    echo "[INFO] Found " . count($quoteIds) . " latest quotes from database.\n";
    
} catch (Exception $e) {
    echo "[ERROR] Database error: " . $e->getMessage() . "\n";
    curl_close($ch);
    exit(1);
}

// **LIKE & SAVE ONE LATEST QUOTE**
// Use bot API endpoint to bypass CSRF validation
$latestQuoteIds = array_slice($quoteIds, 0, 5);
$targetQuoteId = $latestQuoteIds[array_rand($latestQuoteIds)];

echo "[INFO] Processing Quote ID: $targetQuoteId\n";

// Get user ID from session (we're logged in)
// We need to extract user_id from the login response or session
// For now, we'll get it from the database based on username
$userStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();
$botUserId = $userData['id'];
$userStmt->close();

echo "[INFO] Bot user ID: $botUserId\n";

// Bot API key (should match the one in bot-actions.php)
$botApiKey = "your-secret-bot-api-key-here"; // Change this to match!

// Like using bot API
$botApiUrl = APP_URL . "/app/api/bot-actions.php";
curl_setopt($ch, CURLOPT_URL, $botApiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'action' => 'like',
    'quote_id' => $targetQuoteId,
    'user_id' => $botUserId,
    'api_key' => $botApiKey
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-BOT-API-KEY: $botApiKey"]);
$likeResult = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (!curl_errno($ch) && $httpCode == 200) {
    $response = json_decode($likeResult, true);
    if (isset($response['success']) && $response['success']) {
        echo "[SUCCESS] Liked Quote ID: $targetQuoteId via bot API\n";
    } else {
        echo "[WARNING] Like may have failed - Response: $likeResult\n";
    }
} else {
    echo "[ERROR] Like failed with HTTP $httpCode - Response: $likeResult\n";
}

usleep(500000);

// Save using bot API
curl_setopt($ch, CURLOPT_URL, $botApiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'action' => 'save',
    'quote_id' => $targetQuoteId,
    'user_id' => $botUserId,
    'api_key' => $botApiKey
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-BOT-API-KEY: $botApiKey"]);
$saveResult = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (!curl_errno($ch) && $httpCode == 200) {
    $response = json_decode($saveResult, true);
    if (isset($response['success']) && $response['success']) {
        echo "[SUCCESS] Saved Quote ID: $targetQuoteId via bot API\n";
    } else {
        echo "[WARNING] Save may have failed - Response: $saveResult\n";
    }
} else {
    echo "[ERROR] Save failed with HTTP $httpCode - Response: $saveResult\n";
}

// Logout
curl_setopt($ch, CURLOPT_URL, $logoutUrl);
curl_setopt($ch, CURLOPT_POST, false);
curl_exec($ch);
curl_close($ch);

echo "[INFO] Logged out: $username\n";
echo "[" . date('Y-m-d H:i:s') . "] Like & Save Bot Finished\n";
echo str_repeat("=", 60) . "\n";

?>
