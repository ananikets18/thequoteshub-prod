<?php

require __DIR__ . '/../vendor/autoload.php'; // Load PHPSpreadsheet library
require __DIR__ . '/../config/env.php'; // Load environment configuration
use PhpOffice\PhpSpreadsheet\IOFactory;

$excelFilePath = __DIR__ . "/../storage/data/quotes_data.xlsx";

// Load users and quotes from the Excel file
try {
    $spreadsheet = IOFactory::load($excelFilePath);
    $userSheet = $spreadsheet->getSheet(0);
    $quoteSheet = $spreadsheet->getSheet(1);

    $userData = $userSheet->toArray();
    $quoteData = $quoteSheet->toArray();

    // Get indexes for user credentials
    $userHeader = array_map('strtolower', $userData[0]);
    $usernameIndex = array_search('username', $userHeader);
    $passwordIndex = array_search('password', $userHeader);

    if ($usernameIndex === false || $passwordIndex === false) {
        throw new Exception("Username or password column not found in the Excel file.");
    }

    array_shift($userData);
    $users = array_values($userData);

    // Get indexes for quote data
    $quoteHeader = array_map('strtolower', $quoteData[0]);
    $authorIndex = array_search('author_name', $quoteHeader);
    $quoteIndex = array_search('quote_text', $quoteHeader);
    $categoryIndex = array_search('category', $quoteHeader);

    if ($authorIndex === false || $quoteIndex === false || $categoryIndex === false) {
        throw new Exception("Author, quote text, or category column not found in the Excel file.");
    }

    array_shift($quoteData);
    $quotes = array_values($quoteData);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$loginUrl = APP_URL . "/login";
$createQuoteUrl = APP_URL . "/create-quote";
$logoutUrl = APP_URL . "/logout";

$cookieFile = __DIR__ . "/../storage/temp/cookies.txt";

// Log execution start with timestamp
echo "\n" . str_repeat("=", 60) . "\n";
echo "[" . date('Y-m-d H:i:s') . "] Quote Creation Bot Started\n";
echo str_repeat("=", 60) . "\n";

// Clear cookies before login to avoid session issues
file_put_contents($cookieFile, "");

// Pick a random user
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

// Send login request with CSRF token
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

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "[ERROR] cURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

if ($httpCode == 200 && strpos($response, "dashboard") !== false) {
    echo "[SUCCESS] Logged in as: $username\n";

    // Pick a random quote
    $randomQuote = $quotes[array_rand($quotes)];
    $authorName = $randomQuote[$authorIndex];
    $quoteText = $randomQuote[$quoteIndex];
    $quoteCategory = $randomQuote[$categoryIndex];

    echo "[INFO] Creating quote: \"$quoteText\" by $authorName under category '$quoteCategory'\n";

    // Fetch category dropdown options and CSRF token
    // IMPORTANT: Reset to GET request (clear POST from login)
    curl_setopt_array($ch, [
        CURLOPT_URL => $createQuoteUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_POST => false,  // Explicitly set to GET request
        CURLOPT_HTTPGET => true,  // Force GET method
    ]);

    $createPage = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Debug: Check what we actually received
    echo "[DEBUG] Create-quote GET request HTTP code: $httpCode\n";
    echo "[DEBUG] Create-quote page length: " . strlen($createPage) . " bytes\n";
    
    // Check if we got redirected or got an error page
    if (preg_match('/<title>(.*?)<\/title>/', $createPage, $titleMatch)) {
        echo "[DEBUG] Page title: " . $titleMatch[1] . "\n";
    }
    
    // Check for common error indicators
    if (strpos($createPage, 'login') !== false && strpos($createPage, 'password') !== false) {
        echo "[WARNING] Page appears to be a login page - session may have expired\n";
    }

    // Extract CSRF token from create-quote page
    $createCsrfToken = '';
    // Try multiple regex patterns to handle different HTML attribute orders
    if (preg_match('/name=["\']csrf_token["\']\\s+value=["\']([^"\']+)["\']/', $createPage, $csrfMatches)) {
        $createCsrfToken = $csrfMatches[1];
        echo "[INFO] CSRF token extracted from create-quote page\n";
    } elseif (preg_match('/value=["\']([^"\']+)["\']\\s+name=["\']csrf_token["\']/', $createPage, $csrfMatches)) {
        $createCsrfToken = $csrfMatches[1];
        echo "[INFO] CSRF token extracted from create-quote page (reversed attributes)\n";
    } elseif (preg_match('/<input[^>]*name=["\']csrf_token["\'][^>]*value=["\']([^"\']+)["\'][^>]*>/', $createPage, $csrfMatches)) {
        $createCsrfToken = $csrfMatches[1];
        echo "[INFO] CSRF token extracted from create-quote page (full input tag)\n";
    } else {
        echo "[WARNING] CSRF token not found in create-quote page\n";
        echo "[DEBUG] Searching for csrf_token in page...\n";
        if (strpos($createPage, 'csrf_token') !== false) {
            echo "[DEBUG] 'csrf_token' string found in page\n";
            // Extract a snippet around csrf_token for debugging
            $pos = strpos($createPage, 'csrf_token');
            $snippet = substr($createPage, max(0, $pos - 50), 150);
            echo "[DEBUG] Snippet: " . htmlspecialchars($snippet) . "\n";
        } else {
            echo "[DEBUG] 'csrf_token' string NOT found in page at all\n";
            echo "[DEBUG] First 500 chars of page:\n";
            echo substr($createPage, 0, 500) . "\n";
        }
    }

    // Extract available categories from the dropdown
    preg_match_all('/<option value="(\d+)">(.*?)<\/option>/', $createPage, $categoryMatches, PREG_SET_ORDER);
    $categoryId = 0;

    foreach ($categoryMatches as $match) {
        if (trim($match[2]) === trim($quoteCategory)) {
            $categoryId = $match[1];
            break;
        }
    }

    if ($categoryId == 0) {
        echo "[WARNING] Category '$quoteCategory' not found in dropdown. Creating a new category.\n";
    }

    // Submit quote creation form with CSRF token
    curl_setopt_array($ch, [
        CURLOPT_URL => $createQuoteUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'author_name' => $authorName,
            'quote_text' => $quoteText,
            'category_id' => $categoryId,
            'new_category' => ($categoryId == 0 ? $quoteCategory : ''),
            'csrf_token' => $createCsrfToken,  // Include CSRF token
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);

    $quoteResponse = curl_exec($ch);
    
    if (strpos($quoteResponse, "Quote created successfully") !== false) {
        echo "[SUCCESS] Quote created successfully.\n";
    } else {
        echo "[ERROR] Failed to create quote.\n";
        echo "[DEBUG] Response snippet: " . substr($quoteResponse, 0, 200) . "...\n";
    }

} else {
    echo "[ERROR] Login failed for: $username (HTTP $httpCode)\n";
}

// Logout process
curl_setopt_array($ch, [
    CURLOPT_URL => $logoutUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
]);

curl_exec($ch);
curl_close($ch);

echo "[INFO] Logged out: $username\n";
echo "[" . date('Y-m-d H:i:s') . "] Quote Creation Bot Finished\n";
echo str_repeat("=", 60) . "\n";

?>
