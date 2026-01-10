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

while (true) {
    file_put_contents($cookieFile, ""); // Clear cookies

    $randomUser = $users[array_rand($users)];
    $username = $randomUser[$usernameIndex];
    $password = $randomUser[$passwordIndex];

    echo "[INFO] Trying to log in as: $username\n";

    $ch = curl_init();

    // Login request
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([ 'username' => $username, 'password' => $password ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
    ]);

    $loginResponse = curl_exec($ch);
    if (!$loginResponse) {
        echo "[ERROR] cURL Error: " . curl_error($ch) . "\n";
        break;
    }
    echo "[INFO] Login response received.\n";

    // Visit the main page and extract quote IDs
    curl_setopt($ch, CURLOPT_URL, $indexUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $pageHtml = curl_exec($ch);

    if (!$pageHtml) {
        echo "[ERROR] Failed to fetch quotes page.\n";
        curl_close($ch);
        break;
    }

    // Extract quote IDs using regex
    preg_match_all('/\/quote\/(\d+)/', $pageHtml, $matches);
    $quoteIds = array_unique($matches[1]);

    if (empty($quoteIds)) {
        echo "[ERROR] No quotes found.\n";
        curl_close($ch);
        break;
    }

    echo "[INFO] Found " . count($quoteIds) . " quotes.\n";

    // **LIKE & SAVE LATEST QUOTES**
    $latestQuoteIds = array_slice($quoteIds, 0, 3); // Select the first 3 latest quotes
    foreach ($latestQuoteIds as $quoteId) {
        echo "[INFO] Liking & saving latest Quote ID: $quoteId\n";
        
        // Like
        $likeUrl = APP_URL . "/quote/$quoteId/like";
        curl_setopt($ch, CURLOPT_URL, $likeUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_exec($ch);
        echo "[SUCCESS] Liked Quote ID: $quoteId\n";

        // Save
        $saveUrl = APP_URL . "/quote/$quoteId/save";
        curl_setopt($ch, CURLOPT_URL, $saveUrl);
        curl_exec($ch);
        echo "[SUCCESS] Saved Quote ID: $quoteId\n";
    }

    // **LIKE & SAVE A RANDOM QUOTE**
    $randomQuoteId = $quoteIds[array_rand($quoteIds)];
    echo "[INFO] Liking & saving random Quote ID: $randomQuoteId\n";
    
    // Like
    $likeUrl = APP_URL . "/quote/$randomQuoteId/like";
    curl_setopt($ch, CURLOPT_URL, $likeUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_exec($ch);
    echo "[SUCCESS] Liked Random Quote ID: $randomQuoteId\n";

    // Save
    $saveUrl = APP_URL . "/quote/$randomQuoteId/save";
    curl_setopt($ch, CURLOPT_URL, $saveUrl);
    curl_exec($ch);
    echo "[SUCCESS] Saved Random Quote ID: $randomQuoteId\n";

    // Logout
    curl_setopt($ch, CURLOPT_URL, $logoutUrl);
    curl_exec($ch);
    curl_close($ch);

    echo "[INFO] Logged out: $username\n";
    sleep(60); // Wait for 1 minute before repeating
}

?>
