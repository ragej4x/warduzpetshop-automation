<?php
// Dropbox Access Token (read it from a file)
$accessToken = trim(file_get_contents('live-monitor/access_token.txt'));

// Check if access token was read successfully
if (!$accessToken) {
    die("Error: Access token not found.");
}

// Local file to upload
$localFilePath = 'live-monitor/config.txt';

// Dropbox destination path
$dropboxPath = '/live-monitor/config.txt'; // Path in Dropbox

// Read the file contents
$fileContents = file_get_contents($localFilePath);

if ($fileContents === false) {
    die("Error reading the file.");
}

// Dropbox API endpoint for file upload
$url = 'https://content.dropboxapi.com/2/files/upload';

// Dropbox API headers
$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/octet-stream',
    'Dropbox-API-Arg: ' . json_encode([
        'path' => $dropboxPath,         // The path where the file will be uploaded
        'mode' => 'overwrite',          // Overwrite the existing file
        'autorename' => true,           // Auto-rename if there's a conflict (not needed for overwrite)
        'mute' => false                 // Don't mute notifications
    ]),
];

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

// Close cURL session
curl_close($ch);
?>
