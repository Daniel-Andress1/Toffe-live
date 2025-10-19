<?php
// Get id from URL parameter
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($id)) {
    header("Content-Type: text/plain; charset=UTF-8");
    echo "Error: Missing id parameter.";
    exit;
}

// Target URL
$url = "https://vividmosaica.com/embedf.php?player=desktop&live=" . urlencode($id);

// Set HTTP headers like browser
$opts = [
    "http" => [
        "method" => "GET",
        "header" =>
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36\r\n" .
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8\r\n" .
            "Referer: https://dabac.link/\r\n" .
            "Accept-Language: en-US,en;q=0.8\r\n",
        "ignore_errors" => true
    ]
];

$context = stream_context_create($opts);
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    header("Content-Type: text/plain; charset=UTF-8");
    echo "Failed to load source for id: $id";
    exit;
}

// Extract m3u8 link
$m3u8 = '';
if (preg_match("/src=['\"](https?:\/\/[^'\"]+\.m3u8[^'\"]*)['\"]/i", $response, $matches)) {
    $m3u8 = $matches[1];
    // Clean URL — remove extra slashes after https:
    $m3u8 = preg_replace("#^https:\/*#", "https://", $m3u8);
}

// Redirect
if ($m3u8) {
    header("Location: $m3u8");
    exit;
} else {
    header("Content-Type: text/plain; charset=UTF-8");
    echo "No m3u8 URL found for id: $id";
}
?>
