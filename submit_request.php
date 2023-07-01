<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $song = $_POST['song'];

    if (!empty($song)) {
        file_put_contents('song_requests.txt', $song . PHP_EOL, FILE_APPEND);
    }
}

header('Location: index.php');
exit;
?>
