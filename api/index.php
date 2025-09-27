<?php
// Target URL
$url = "https://beesports.net/authorize-channel";

// The channel URL you want to authorize
$channelUrl = "https://live_tv.starcdnup.com/TNT_Sports_1/index.m3u8";

// JSON body
$data = [
    "channel" => $channelUrl
];
$jsonData = json_encode($data);

// Stream context options for POST request
$options = [
    "http" => [
        "method"  => "POST",
        "header"  => "Content-Type: application/json\r\n" .
                     "Accept: application/json\r\n",
        "content" => $jsonData,
        "ignore_errors" => true
    ]
];

$context  = stream_context_create($options);

// Execute POST request
$response = file_get_contents($url, false, $context);

// Check for errors
if ($response === false) {
    echo "Error sending request.";
} else {
    echo $response;
}
