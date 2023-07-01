<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AquariusMusic Box</title>
    <link rel="icon" type="image/png" href="/assets/image/icon/icon.ico" />
    <!-- Custom fonts for this template -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/assets/css/myStyle.css" rel="stylesheet">
    <link href="/assets/css/myStyle2.css" rel="stylesheet">
    <link href="/assets/css/myStyleResponsive.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            font-family: 'Kanit', sans-serif !important;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #efeff6;
        }

        .send-btn {
            background-color: #007bff;
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .song-input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            font-size: 16px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .song-input:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .title {
            color: #007bff;
            font-size: 30px;
            margin-top: 10px;
        }

        .status {
            font-size: 14px;
        }

        .status-open {
            color: green;
        }

        .search-results {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .search-results li {
            cursor: pointer;
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 3px;
            margin-bottom: 5px;
        }

        .search-results li:hover {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>
    <div id="mainVue" class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                        <img src="/assets/image/logo.png" alt="Logo" width="100">
                        <?php
                        // Check if the system is closed
                        $systemClosed = false;
                        $reason = '';

                        if (file_exists('system_status.txt')) {
                            $fileContents = file_get_contents('system_status.txt');
                            $status = explode(':', $fileContents);
                            $systemClosed = ($status[0] === 'closed');
                            if (isset($status[1])) {
                                $reason = $status[1];
                            }
                        }

                        if ($systemClosed) {
                            echo '<h3 class="status">System closed due to: <span style="color: red;">' . $reason . '</span></h3>';
                        } else {
                            echo '<h3 class="status status-open">Song Request is Open</h3>';
                            echo '<h2 class="title">AquariusMusic</h2>';
                            echo '<form action="submit_request.php" method="POST" class="d-flex">';
                            echo '    <input class="song-input" type="text" name="song" placeholder="Enter song name" required v-model="SongRequest" @keydown.enter.prevent="onSubmitRequest">';
                            echo '    <button class="send-btn ml-2" type="submit" @click="onSubmitRequest">Send</button>';
                            echo '</form>';
                            echo '<ul v-if="searchResults.length > 0" class="search-results">';
                            echo '    <li v-for="song in searchResults" @click="selectSong(song)">{{ song.name }}</li>';
                            echo '</ul>';
                            echo '<div v-if="requestSent" class="alert alert-success mt-3">Your music request has been sent!</div>';
                        }
                        ?>
                    </div>
                    <hr>
                    <div class="divLoginFooter">
                        <small>
                            AquariusMusic box ©️2023-2024 <br>
                            System created by <a href="https://www.instagram.com/jean_netis/">@jean_netis (JN03)</a> <br>
                            <span @click="openDiscord()" class="discord-icon">
                                <a href="https://discord.gg/2AakxxBTR7"><img src="https://assets-global.website-files.com/6257adef93867e50d84d30e2/636e0a6a49cf127bf92de1e2_icon_clyde_blurple_RGB.png" alt="Discord" width="20"></a>
                            </span> <span @click="openInstagram()" class="instagram-icon">
                                <a href="https://www.instagram.com/studentcouncil.prc/"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/768px-Instagram_icon.png" alt="Instagram" width="20"></a>
                            </span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.min.js"></script>

    <script>
        const app = new Vue({
            el: '#mainVue',
            data: {
                SongRequest: '',
                searchResults: [],
                requestSent: false
            },
            methods: {
                onSubmitRequest() {
                    if (this.SongRequest.trim() !== '') {
                        this.requestSent = true;
                        setTimeout(() => {
                            this.requestSent = false;
                            this.SongRequest = '';
                        }, 3000);
                    }
                },
                selectSong(song) {
                    this.SongRequest = song.name;
                    this.searchResults = [];
                }
            }
        });
    </script>
</body>

</html>
