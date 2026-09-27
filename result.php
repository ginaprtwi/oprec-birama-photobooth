<?php
session_start();

if (empty($_SESSION['email']) || empty($_SESSION['result_file'])) {
    header('Location: email.php');
    exit;
}

$resultUrl = 'uploads/' . basename($_SESSION['result_file']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Foto | Photobooth</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="result-page">
    <main class="page-shell result-shell result-center">
        <div class="result-image-wrap">
            <img src="<?= htmlspecialchars($resultUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Hasil foto photobooth">
        </div>
        <div class="result-actions">
            <a class="button button-secondary" href="camera.php">RETAKE</a>
            <a class="button button-secondary" href="<?= htmlspecialchars($resultUrl, ENT_QUOTES, 'UTF-8') ?>" download="birama-photobooth.png">DOWNLOAD</a>
            <form action="send_email.php" method="post">
                <button class="button button-primary" type="submit">KIRIM</button>
            </form>
        </div>
    </main>
</body>
</html>
