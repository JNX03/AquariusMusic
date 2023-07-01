<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = $_POST['reason'];

    if (!empty($reason)) {
        file_put_contents('system_status.txt', 'closed:' . $reason);
    }
}

header('Location: admin.php');
exit;
?>
