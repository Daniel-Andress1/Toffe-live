<?php
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit("Missing 'id' parameter");
}

$matchPath = $_GET['id'];
$baseUrl = "https://tiksports.eu/";
$url = $baseUrl . $matchPath;

// Fetch HTML
$options = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
    ]
];
$context = stream_context_create($options);
$html = @file_get_contents($url, false, $context);

if (!$html) {
    http_response_code(500);
    exit("Failed to fetch page");
}

// Parse HTML
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$iframes = $xpath->query('//div[contains(@class,"iframe-wrapper")]/iframe');

if ($iframes->length === 0) {
    http_response_code(404);
    exit("No iframe found");
}

$iframeSrc = $iframes->item(0)->getAttribute('src');
$parts = parse_url($iframeSrc);
parse_str($parts['query'], $queryParams);

if (!isset($queryParams['link'])) {
    http_response_code(404);
    exit("No link parameter found");
}

$m3u8Url = urldecode($queryParams['link']);

// Output the link directly for external players
header("Content-Type: text/plain");
header("Access-Control-Allow-Origin: *");
echo $m3u8Url;
exit;
?>
