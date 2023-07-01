<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $song = $_POST['song'];

        if (!empty($song)) {
            $requestsFile = "song_requests.txt";
            $song = trim($song);

            file_put_contents($requestsFile, $song . "\n", FILE_APPEND);
        }
    }

    header("Location: index.php");
    exit();
?>
