<?php
// search_songs.php

// Check if the required parameters are present
if (!isset($_GET['BQAkcsdJPrCdZp6KqxzhCQoMUzkUYiubYLchmODKR8nOys9saUu36Hihdtk1WYJmLt0bgSQXG2Rw42k0OGN2PmkKXrJAAPWOEyl7anrycIUWxxBbKBI80N7NblNzTwxe']) || !isset($_GET['query'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$accessToken = $_GET['access_token'];
$query = $_GET['query'];

// Create the Spotify API endpoint URL for searching tracks
$searchEndpoint = 'https://api.spotify.com/v1/search?type=track&q=' . urlencode($query);

// Set up the cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $searchEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

// Execute the cURL request
$response = curl_exec($ch);

// Check if the request was successful
if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to execute request']);
    exit;
}

// Parse the response as JSON
$data = json_decode($response, true);

// Check if the response contains any tracks
if (!isset($data['tracks']) || !isset($data['tracks']['items']) || empty($data['tracks']['items'])) {
    http_response_code(404);
    echo json_encode(['error' => 'No tracks found']);
    exit;
}

// Extract the relevant information from the search results
$tracks = $data['tracks']['items'];
$searchResults = [];

foreach ($tracks as $track) {
    $searchResults[] = [
        'id' => $track['id'],
        'name' => $track['name'],
        'artist' => $track['artists'][0]['name'],
        'album' => $track['album']['name'],
        'image' => $track['album']['images'][0]['url']
    ];
}

// Return the search results as JSON
echo json_encode(['tracks' => $searchResults]);
