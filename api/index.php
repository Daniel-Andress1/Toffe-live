<?php
// Get channel name from query parameter (?id=...)
$channelName = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!$channelName) {
    http_response_code(400);
    echo "Missing channel name (?id=...).";
    exit;
}

// Beesports authorize API
$url = "https://beesport.io/authorize-channel";

// Build channel URL
$channelUrl = "https://live_tv.starcdnup.com/" . $channelName . "/index.m3u8";

// JSON body
$data = ["channel" => $channelUrl];
$jsonData = json_encode($data);

// Stream context options
$options = [
    "http" => [
        "method"  => "POST",
        "header"  => "Content-Type: application/json\r\n" .
                     "Accept: application/json\r\n",
        "content" => $jsonData,
        "ignore_errors" => true
    ]
];

$context = stream_context_create($options);

// Execute POST
$response = file_get_contents($url, false, $context);

if ($response === false) {
    http_response_code(500);
    echo "Authorization request failed.";
    exit;
}

// Decode JSON response
$json = json_decode($response, true);

if (!$json || empty($json["channels"][0])) {
    http_response_code(500);
    echo "Invalid response from authorize API.";
    exit;
}

// Get final redirect URL
$finalUrl = $json["channels"][0];

// Redirect user to the authorized stream
header("Location: " . $finalUrl);
exit;
