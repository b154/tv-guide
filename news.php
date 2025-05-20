<?php
// List your stream URLs here, in priority order.
$streams = [
    'https://linear417-gb-hls1-prd-ak.cdn.skycdp.com/100e/Content/HLS_001_1080_30/Live/channel(skynews)/index_1080-30.m3u8',
    'https://backup-stream-url.com/stream.m3u8',
];

// Check each stream in order
foreach ($streams as $url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_NOBODY => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Kodi-Compatible)', // optional spoof
    ]);

    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($httpCode === 200 && strpos($contentType, 'application/vnd.apple.mpegurl') !== false) {
        header("Location: $url", true, 302);
        exit;
    }
}

// If no streams worked
http_response_code(503);
echo "No available stream at this time.";
