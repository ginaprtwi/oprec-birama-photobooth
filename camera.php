<?php
session_start();

if (empty($_SESSION['email'])) {
    header('Location: email.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Foto | Photobooth</title>
    <link rel="stylesheet" href="assets/css/style.css?v=20260922-camera">
</head>
<body class="camera-page">
    <main class="page-shell camera-shell camera-only">
        <span id="step-number" class="visually-hidden">02</span>
        <div class="camera-card">
            <div class="video-wrap">
                <video id="camera-preview" autoplay playsinline muted></video>
                <div id="countdown" class="countdown" aria-live="polite"></div>
                <div class="scanline"></div>
            </div>
            <div class="camera-controls">
                <div class="capture-status visually-hidden"><span class="status-dot"></span><span id="status-text">Menyiapkan kamera...</span></div>
                <span id="photo-progress" class="visually-hidden">0 / 3 photos</span>
                <button id="start-capture" class="button button-primary camera-button" type="button" aria-label="Ambil foto" disabled></button>
            </div>
        </div>
        <canvas id="capture-canvas" hidden></canvas>
    </main>
    <script src="assets/js/camera.js?v=20260923-obs"></script>
</body>
</html>
