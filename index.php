<?php
session_start();

// Mulai sesi baru agar hasil dari pengunjung sebelumnya tidak terbawa.
$_SESSION['email'] = null;
$_SESSION['photos'] = [];
$_SESSION['result_file'] = null;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pers Birama | Photobooth</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="welcome-page">
    <main class="welcome-shell">
        <div class="welcome-copy">
            <a class="button button-primary start-button" href="camera.php">START</a>
        </div>
    </main>
</body>
</html>
