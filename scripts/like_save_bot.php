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

// Visit the main page to maintain session
curl_setopt($ch, CURLOPT_URL, $indexUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, false);
$pageHtml = curl_exec($ch);

if (!$pageHtml || curl_errno($ch)) {
    echo "[ERROR] Failed to fetch quotes page: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

// Extract CSRF token from the page, or use login token as fallback
$pageCsrfToken = '';
if (preg_match('/name="csrf_token"\s+value="([^"]+)"/', $pageHtml, $csrfMatches)) {
    $pageCsrfToken = $csrfMatches[1];
    echo "[INFO] CSRF token extracted from main page\n";
} elseif (!empty($csrfToken)) {
    // Use login CSRF token as fallback
    $pageCsrfToken = $csrfToken;
    echo "[INFO] Using login CSRF token for requests\n";
} else {
    echo "[WARNING] No CSRF token available\n";
}

// Connect to database to get latest quotes
try {
    require_once __DIR__ . '/../config/database.php';
    
    if (!isset($conn) || !$conn) {
        throw new Exception("Database connection not available");
    }
    
    echo "[INFO] Database connection established\n";
    
    // Get the 10 most recently created quotes
    $stmt = $conn->prepare("SELECT id FROM quotes WHERE status = 'approved' ORDER BY created_at DESC LIMIT 10");
    
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
    echo "[INFO] Falling back to HTML scraping method...\n";
    
    // Fallback: Extract quote IDs from HTML
    preg_match_all('/\/quote\/(\d+)/', $pageHtml, $matches);
    $quoteIds = array_unique($matches[1]);
    
    if (empty($quoteIds)) {
        echo "[ERROR] No quotes found.\n";
        curl_close($ch);
        exit(1);
    }
    
    echo "[INFO] Found " . count($quoteIds) . " quotes from HTML.\n";
}

// **LIKE & SAVE LATEST QUOTES**
$latestQuoteIds = array_slice($quoteIds, 0, 3); // Select the first 3 latest quotes
foreach ($latestQuoteIds as $quoteId) {
    echo "[INFO] Processing latest Quote ID: $quoteId\n";
    
    // Like
    $likeUrl = APP_URL . "/quote/$quoteId/like";
    curl_setopt($ch, CURLOPT_URL, $likeUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['csrf_token' => $pageCsrfToken]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-CSRF-TOKEN: $pageCsrfToken"]);
    $likeResult = curl_exec($ch);
    if (!curl_errno($ch)) {
        echo "[SUCCESS] Liked Quote ID: $quoteId\n";
    } else {
        echo "[ERROR] Failed to like Quote ID: $quoteId - " . curl_error($ch) . "\n";
    }

    // Save
    $saveUrl = APP_URL . "/quote/$quoteId/save";
    curl_setopt($ch, CURLOPT_URL, $saveUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['csrf_token' => $pageCsrfToken]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-CSRF-TOKEN: $pageCsrfToken"]);
    $saveResult = curl_exec($ch);
    if (!curl_errno($ch)) {
        echo "[SUCCESS] Saved Quote ID: $quoteId\n";
    } else {
        echo "[ERROR] Failed to save Quote ID: $quoteId - " . curl_error($ch) . "\n";
    }
    
    usleep(500000); // Wait 0.5 seconds between requests
}

// **LIKE & SAVE A RANDOM QUOTE**
$randomQuoteId = $quoteIds[array_rand($quoteIds)];
echo "[INFO] Processing random Quote ID: $randomQuoteId\n";

// Like
$likeUrl = APP_URL . "/quote/$randomQuoteId/like";
curl_setopt($ch, CURLOPT_URL, $likeUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['csrf_token' => $pageCsrfToken]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-CSRF-TOKEN: $pageCsrfToken"]);
$likeResult = curl_exec($ch);
if (!curl_errno($ch)) {
    echo "[SUCCESS] Liked Random Quote ID: $randomQuoteId\n";
} else {
    echo "[ERROR] Failed to like Quote ID: $randomQuoteId - " . curl_error($ch) . "\n";
}

// Save
$saveUrl = APP_URL . "/quote/$randomQuoteId/save";
curl_setopt($ch, CURLOPT_URL, $saveUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['csrf_token' => $pageCsrfToken]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-CSRF-TOKEN: $pageCsrfToken"]);
$saveResult = curl_exec($ch);
if (!curl_errno($ch)) {
    echo "[SUCCESS] Saved Random Quote ID: $randomQuoteId\n";
} else {
    echo "[ERROR] Failed to save Quote ID: $randomQuoteId - " . curl_error($ch) . "\n";
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
