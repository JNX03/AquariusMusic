<?php
// Get song requests
$requests = file('song_requests.txt', FILE_IGNORE_NEW_LINES);

if (count($requests) > 0) {
    echo '<ul>';
    foreach ($requests as $request) {
        // Get the timestamp
        $timestamp = date('Y-m-d H:i:s');

        echo '<li>' . $timestamp . ' - ' . $request . ' <a href="remove_request.php?song=' . urlencode($request) . '"> - Mark as done</a></li>';
    }
    echo '</ul>';
} else {
    echo 'No song requests.';
}
?>
