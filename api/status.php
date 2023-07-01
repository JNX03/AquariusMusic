<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'open') {
        file_put_contents('system_status.txt', 'open:');
    } elseif ($action === 'close') {
        $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
        file_put_contents('system_status.txt', 'closed:' . $reason);
    }
}
