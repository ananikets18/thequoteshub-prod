<?php

require __DIR__ . '/../vendor/autoload.php'; // Load PHPSpreadsheet library
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

$loginUrl = "https://thequoteshub.in/login";
$createQuoteUrl = "https://thequoteshub.in/create-quote";
$logoutUrl = "https://thequoteshub.in/logout";

$cookieFile = __DIR__ . "/../storage/temp/cookies.txt";

while (true) {
    // Clear cookies before login to avoid session issues
    file_put_contents($cookieFile, "");

    // Pick a random user
    $randomUser = $users[array_rand($users)];
    $username = $randomUser[$usernameIndex];
    $password = $randomUser[$passwordIndex];

    echo "\n[INFO] Trying to log in as: $username\n";

    $ch = curl_init();

    // Get login page (to maintain session)
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
    ]);

    $loginPage = curl_exec($ch);

    // Send login request
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'username' => $username,
            'password' => $password,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 200 && strpos($response, "dashboard") !== false) {
        echo "[SUCCESS] Logged in as: $username\n";

        // Pick a random quote
        $randomQuote = $quotes[array_rand($quotes)];
        $authorName = $randomQuote[$authorIndex];
        $quoteText = $randomQuote[$quoteIndex];
        $quoteCategory = $randomQuote[$categoryIndex];

        echo "[INFO] Creating quote: \"$quoteText\" by $authorName under category '$quoteCategory'\n";

        // Fetch category dropdown options
        curl_setopt_array($ch, [
            CURLOPT_URL => $createQuoteUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
        ]);

        $createPage = curl_exec($ch);

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

        // Submit quote creation form
        curl_setopt_array($ch, [
            CURLOPT_URL => $createQuoteUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'author_name' => $authorName,
                'quote_text' => $quoteText,
                'category_id' => $categoryId,
                'new_category' => ($categoryId == 0 ? $quoteCategory : ''),
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $quoteResponse = curl_exec($ch);
        echo "[DEBUG] Sending data: " . json_encode([
            'author_name' => $authorName,
            'quote_text' => $quoteText,
            'category_id' => $categoryId,
            'new_category' => ($categoryId == 0 ? $quoteCategory : ''),
        ], JSON_PRETTY_PRINT) . "\n";

        if (strpos($quoteResponse, "Quote created successfully") !== false) {
            echo "[SUCCESS] Quote created successfully.\n";
        } else {
            echo "[ERROR] Failed to create quote.\n";
        }

    } else {
        echo "[ERROR] Login failed for: $username\n";
    }

    // Wait for 30 minutes (1800 seconds)
    sleep(1800);

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

    sleep(2);
}

?>
