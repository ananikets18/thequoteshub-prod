<?php

require __DIR__ . '/../config/env.php'; // Load environment configuration

$indexUrl = APP_URL . "/"; // Main page where quotes are listed

// Log execution start with timestamp
echo "\n" . str_repeat("=", 60) . "\n";
echo "[" . date('Y-m-d H:i:s') . "] View Count Bot Started\n";
echo str_repeat("=", 60) . "\n";

$ch = curl_init();

// Visit the main page to get quote IDs
curl_setopt_array($ch, [
    CURLOPT_URL => $indexUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
]);

$pageHtml = curl_exec($ch);

if (!$pageHtml || curl_errno($ch)) {
    echo "[ERROR] Failed to fetch quotes page: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

// Extract quote IDs using regex
preg_match_all('/\/quote\/(\d+)/', $pageHtml, $matches);
$quoteIds = array_unique($matches[1]);

if (empty($quoteIds)) {
    echo "[ERROR] No quotes found on the page.\n";
    curl_close($ch);
    exit(1);
}

echo "[INFO] Found " . count($quoteIds) . " quotes.\n";

// Randomize the number of quotes to view (5-10)
$numQuotesToView = rand(5, min(10, count($quoteIds)));
$quotesToView = array_rand(array_flip($quoteIds), $numQuotesToView);

// Ensure it's an array even if only one quote
if (!is_array($quotesToView)) {
    $quotesToView = [$quotesToView];
}

echo "[INFO] Will view $numQuotesToView quotes.\n";

$viewedCount = 0;

foreach ($quotesToView as $quoteId) {
    $quoteUrl = APP_URL . "/quote/$quoteId";
    
    // Randomize user agent for more natural behavior
    $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ];
    
    curl_setopt($ch, CURLOPT_URL, $quoteUrl);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgents[array_rand($userAgents)]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $quotePageHtml = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (!curl_errno($ch) && $httpCode == 200) {
        echo "[SUCCESS] Viewed Quote ID: $quoteId (HTTP $httpCode)\n";
        $viewedCount++;
    } else {
        echo "[ERROR] Failed to view Quote ID: $quoteId - HTTP $httpCode - " . curl_error($ch) . "\n";
    }
    
    // Random delay between views (1-3 seconds) to simulate human behavior
    $delay = rand(1000000, 3000000); // microseconds (1-3 seconds)
    usleep($delay);
}

curl_close($ch);

echo "[INFO] Successfully viewed $viewedCount out of $numQuotesToView quotes.\n";
echo "[" . date('Y-m-d H:i:s') . "] View Count Bot Finished\n";
echo str_repeat("=", 60) . "\n";

?>
