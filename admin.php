<?php
session_start();

// Check if the form is submitted (login)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perform your authentication logic here
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform authentication checks, e.g., against a database or predefined values
    if ($username === 'admin' && $password === 'password') {
        // Authentication successful, generate and store device identifier
        $deviceIdentifier = generateDeviceIdentifier();
        $_SESSION['loggedin'] = true;
        $_SESSION['deviceIdentifier'] = $deviceIdentifier;
        // Redirect to admin panel
        header('Location: admin.php');
        exit;
    } else {
        // Authentication failed, display an error message
        $error = 'Invalid username or password';
    }
}

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Check if the device identifier matches
    if ($_SESSION['deviceIdentifier'] !== generateDeviceIdentifier()) {
        // Device not authorized, logout the user and redirect to login
        unset($_SESSION['loggedin']);
        unset($_SESSION['deviceIdentifier']);
        header('Location: admin.php');
        exit;
    }

    // User is logged in and device is authorized, show the admin panel
    $systemStatus = getSystemStatus();
    $isClosed = ($systemStatus['status'] === 'closed');
    $reason = $systemStatus['reason'];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>AquariusMusic Admin</title>
        <link rel="icon" type="image/png" href="/assets/image/icon/icon.ico" />
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Function to update the song list
            function updateSongList() {
                $.ajax({
                    url: 'get_song_requests.php',
                    success: function(data) {
                        // Update the song list
                        $('#song-list').html(data);
                    }
                });
            }

            // Call the updateSongList function initially
            $(document).ready(function() {
                updateSongList();
            });

            // Call the updateSongList function every 5 seconds
            setInterval(updateSongList, 5000);

            // Function to handle opening/closing the system
            function toggleSystemStatus() {
                var action = '<?php echo ($isClosed ? "open" : "close"); ?>';
                $.ajax({
                    url: 'system_status.php',
                    type: 'POST',
                    data: { action: action },
                    success: function() {
                        location.reload();
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="container">
            <h1 class="title">AquariusMusic Admin</h1>
            <link rel="icon" type="image/png" href="/assets/image/icon/icon.ico" />

            <h2 class="sub-title">Song Requests</h2>
            <div id="song-list">Loading song requests...</div>

            <h2 class="sub-title">
                <?php
                if ($isClosed) {
                    echo "Open System";
                } else {
                    echo "Close System";
                }
                ?>
            </h2>
            <?php if ($isClosed) { ?>
                <form action="open_system.php" method="POST">
                    <input type="hidden" name="action" value="open">
                    <button type="submit">Open System</button>
                </form>
            <?php } else { ?>
                <form action="close_system.php" method="POST">
                    <input type="text" name="reason" placeholder="Enter reason to close">
                    <button type="submit">Close System</button>
                </form>
            <?php } ?>

            <div class="footer">
                AquariusMusic box ©️2023-2024 | System created by @jean_netis (JN03)
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// User is not logged in, show the login form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="title">AquariusMusic Admin Login</h1>
        <link rel="icon" type="image/png" href="/assets/image/icon/icon.ico" />

        <?php if (isset($error)) { ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php } ?>

        <form action="admin.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Login">
        </form>

        <div class="footer">
            AquariusMusic box ©️2023-2024 | System created by @jean_netis (JN03)
        </div>
    </div>
</body>
</html>
<?php
// Function to generate a unique device identifier
function generateDeviceIdentifier()
{
    return sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
}

// Function to get the system status
function getSystemStatus()
{
    $statusFile = 'system_status.txt';
    if (file_exists($statusFile)) {
        $statusData = file_get_contents($statusFile);
        $status = explode(':', $statusData);
        if (count($status) === 2) {
            return array('status' => trim($status[0]), 'reason' => trim($status[1]));
        }
    }
    // Default status if file doesn't exist or data is invalid
    return array('status' => 'open', 'reason' => '');
}
