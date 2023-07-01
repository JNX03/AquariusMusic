<?php
if (isset($_GET['song'])) {
    $song = $_GET['song'];

    $requests = file('song.txt', FILE_IGNORE_NEW_LINES);
    $index = array_search($song, $requests);

    if ($index !== false) {
        unset($requests[$index]);
        file_put_contents('song.txt', implode(PHP_EOL, $requests));
    }
}

header('Location: admin.php');
exit;
?>
